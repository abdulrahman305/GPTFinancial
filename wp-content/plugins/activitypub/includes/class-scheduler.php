<?php
/**
 * Scheduler class file.
 *
 * @package Activitypub
 */

namespace Activitypub;

use Activitypub\Activity\Activity;
use Activitypub\Activity\Base_Object;
use Activitypub\Scheduler\Post;
use Activitypub\Scheduler\Actor;
use Activitypub\Scheduler\Comment;
use Activitypub\Collection\Actors;
use Activitypub\Collection\Outbox;
use Activitypub\Collection\Followers;
use Activitypub\Transformer\Factory;

/**
 * Scheduler class.
 *
 * @author Matthias Pfefferle
 */
class Scheduler {

	/**
	 * Allowed batch callbacks.
	 *
	 * @var array
	 */
	private static $batch_callbacks = array();

	/**
	 * Initialize the class, registering WordPress hooks.
	 */
	public static function init() {
		self::register_schedulers();

		self::$batch_callbacks = array(
			Dispatcher::$callback,
			array( Dispatcher::class, 'retry_send_to_followers' ),
		);

		// Follower Cleanups.
		\add_action( 'activitypub_update_followers', array( self::class, 'update_followers' ) );
		\add_action( 'activitypub_cleanup_followers', array( self::class, 'cleanup_followers' ) );

		// Event callbacks.
		\add_action( 'activitypub_async_batch', array( self::class, 'async_batch' ), 10, 99 );
		\add_action( 'activitypub_reprocess_outbox', array( self::class, 'reprocess_outbox' ) );
		\add_action( 'activitypub_outbox_purge', array( self::class, 'purge_outbox' ) );

		\add_action( 'post_activitypub_add_to_outbox', array( self::class, 'schedule_outbox_activity_for_federation' ) );
		\add_action( 'post_activitypub_add_to_outbox', array( self::class, 'schedule_announce_activity' ), 10, 4 );

		\add_action( 'update_option_activitypub_outbox_purge_days', array( self::class, 'handle_outbox_purge_days_update' ), 10, 2 );
	}

	/**
	 * Register handlers.
	 */
	public static function register_schedulers() {
		Post::init();
		Actor::init();
		Comment::init();

		/**
		 * Register additional schedulers.
		 *
		 * @since 5.0.0
		 */
		do_action( 'activitypub_register_schedulers' );
	}

	/**
	 * Schedule all ActivityPub schedules.
	 */
	public static function register_schedules() {
		if ( ! \wp_next_scheduled( 'activitypub_update_followers' ) ) {
			\wp_schedule_event( time(), 'hourly', 'activitypub_update_followers' );
		}

		if ( ! \wp_next_scheduled( 'activitypub_cleanup_followers' ) ) {
			\wp_schedule_event( time(), 'daily', 'activitypub_cleanup_followers' );
		}

		if ( ! \wp_next_scheduled( 'activitypub_reprocess_outbox' ) ) {
			\wp_schedule_event( time(), 'hourly', 'activitypub_reprocess_outbox' );
		}

		if ( ! wp_next_scheduled( 'activitypub_outbox_purge' ) ) {
			wp_schedule_event( time(), 'daily', 'activitypub_outbox_purge' );
		}
	}

	/**
	 * Un-schedule all ActivityPub schedules.
	 *
	 * @return void
	 */
	public static function deregister_schedules() {
		wp_unschedule_hook( 'activitypub_update_followers' );
		wp_unschedule_hook( 'activitypub_cleanup_followers' );
		wp_unschedule_hook( 'activitypub_reprocess_outbox' );
		wp_unschedule_hook( 'activitypub_outbox_purge' );
	}

	/**
	 * Update followers.
	 */
	public static function update_followers() {
		$number = 5;

		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
			$number = 50;
		}

		/**
		 * Filter the number of followers to update.
		 *
		 * @param int $number The number of followers to update.
		 */
		$number    = apply_filters( 'activitypub_update_followers_number', $number );
		$followers = Followers::get_outdated_followers( $number );

		foreach ( $followers as $follower ) {
			$meta = get_remote_metadata_by_actor( $follower->get_id(), false );

			if ( empty( $meta ) || ! is_array( $meta ) || is_wp_error( $meta ) ) {
				Followers::add_error( $follower->get__id(), $meta );
			} else {
				$follower->from_array( $meta );
				$follower->update();
			}
		}
	}

	/**
	 * Cleanup followers.
	 */
	public static function cleanup_followers() {
		$number = 5;

		if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
			$number = 50;
		}

		/**
		 * Filter the number of followers to clean up.
		 *
		 * @param int $number The number of followers to clean up.
		 */
		$number    = apply_filters( 'activitypub_update_followers_number', $number );
		$followers = Followers::get_faulty_followers( $number );

		foreach ( $followers as $follower ) {
			$meta = get_remote_metadata_by_actor( $follower->get_url(), false );

			if ( is_tombstone( $meta ) ) {
				$follower->delete();
			} elseif ( empty( $meta ) || ! is_array( $meta ) || is_wp_error( $meta ) ) {
				if ( $follower->count_errors() >= 5 ) {
					$follower->delete();
					\wp_schedule_single_event(
						\time(),
						'activitypub_delete_actor_interactions',
						array( $follower->get_id() )
					);
				} else {
					Followers::add_error( $follower->get__id(), $meta );
				}
			} else {
				$follower->reset_errors();
			}
		}
	}

	/**
	 * Schedule the outbox item for federation.
	 *
	 * @param int $id     The ID of the outbox item.
	 * @param int $offset The offset to add to the scheduled time.
	 */
	public static function schedule_outbox_activity_for_federation( $id, $offset = 0 ) {
		$hook = 'activitypub_process_outbox';
		$args = array( $id );

		if ( false === wp_next_scheduled( $hook, $args ) ) {
			\wp_schedule_single_event(
				\time() + $offset,
				$hook,
				$args
			);
		}
	}

	/**
	 * Reprocess the outbox.
	 */
	public static function reprocess_outbox() {
		// Bail if there is a pending batch.
		if ( self::next_scheduled_hook( 'activitypub_async_batch' ) ) {
			return;
		}

		// Bail if there is a batch in progress.
		$key = \md5( \serialize( Dispatcher::$callback ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
		if ( self::is_locked( $key ) ) {
			return;
		}

		$ids = \get_posts(
			array(
				'post_type'      => Outbox::POST_TYPE,
				'post_status'    => 'pending',
				'posts_per_page' => 10,
				'fields'         => 'ids',
			)
		);

		foreach ( $ids as $id ) {
			self::schedule_outbox_activity_for_federation( $id );
		}
	}

	/**
	 * Purge outbox items based on a schedule.
	 */
	public static function purge_outbox() {
		$total_posts = (int) wp_count_posts( Outbox::POST_TYPE )->publish;
		if ( $total_posts <= 20 ) {
			return;
		}

		$days     = (int) get_option( 'activitypub_outbox_purge_days', 180 );
		$timezone = new \DateTimeZone( 'UTC' );
		$date     = new \DateTime( 'now', $timezone );

		$date->sub( \DateInterval::createFromDateString( "$days days" ) );

		$post_ids = get_posts(
			array(
				'post_type'   => Outbox::POST_TYPE,
				'post_status' => 'any',
				'fields'      => 'ids',
				'numberposts' => -1,
				'date_query'  => array(
					array(
						'before' => $date->format( 'Y-m-d' ),
					),
				),
			)
		);

		foreach ( $post_ids as $post_id ) {
			\wp_delete_post( $post_id, true );
		}
	}

	/**
	 * Update schedules when outbox purge days settings change.
	 *
	 * @param int $old_value The old value.
	 * @param int $value     The new value.
	 */
	public static function handle_outbox_purge_days_update( $old_value, $value ) {
		if ( 0 === (int) $value ) {
			wp_clear_scheduled_hook( 'activitypub_outbox_purge' );
		} elseif ( ! wp_next_scheduled( 'activitypub_outbox_purge' ) ) {
			wp_schedule_event( time(), 'daily', 'activitypub_outbox_purge' );
		}
	}

	/**
	 * Asynchronously runs batch processing routines.
	 *
	 * The batching part is optional and only comes into play if the callback returns anything.
	 * Beyond that it's a helper to run a callback asynchronously with locking to prevent simultaneous processing.
	 *
	 * @param callable $callback Callable processing routine.
	 * @params mixed   ...$args  Optional. Parameters that get passed to the callback.
	 */
	public static function async_batch( $callback ) {
		if ( ! in_array( $callback, self::$batch_callbacks, true ) || ! \is_callable( $callback ) ) {
			_doing_it_wrong( __METHOD__, 'The first argument must be a valid callback.', '5.2.0' );
			return;
		}

		$args = \func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue
		$key  = \md5( \serialize( $callback ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize

		// Bail if the existing lock is still valid.
		if ( self::is_locked( $key ) ) {
			\wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'activitypub_async_batch', $args );
			return;
		}

		self::lock( $key );

		$callback = array_shift( $args ); // Remove $callback from arguments.
		$next     = \call_user_func_array( $callback, $args );

		self::unlock( $key );

		if ( ! empty( $next ) ) {
			// Schedule the next run, adding the result to the arguments.
			\wp_schedule_single_event(
				\time() + 30,
				'activitypub_async_batch',
				\array_merge( array( $callback ), \array_values( $next ) )
			);
		}
	}


	/**
	 * Locks the async batch process for individual callbacks to prevent simultaneous processing.
	 *
	 * @param string $key Serialized callback name.
	 * @return bool|int True if the lock was successful, timestamp of existing lock otherwise.
	 */
	public static function lock( $key ) {
		global $wpdb;

		// Try to lock.
		$lock_result = (bool) $wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO `$wpdb->options` ( `option_name`, `option_value`, `autoload` ) VALUES (%s, %s, 'no') /* LOCK */", 'activitypub_async_batch_' . $key, \time() ) ); // phpcs:ignore WordPress.DB

		if ( ! $lock_result ) {
			$lock_result = \get_option( 'activitypub_async_batch_' . $key );
		}

		return $lock_result;
	}

	/**
	 * Unlocks processing for the async batch callback.
	 *
	 * @param string $key Serialized callback name.
	 */
	public static function unlock( $key ) {
		\delete_option( 'activitypub_async_batch_' . $key );
	}

	/**
	 * Whether the async batch callback is locked.
	 *
	 * @param string $key Serialized callback name.
	 * @return boolean
	 */
	public static function is_locked( $key ) {
		$lock = \get_option( 'activitypub_async_batch_' . $key );

		if ( ! $lock ) {
			return false;
		}

		$lock = (int) $lock;

		if ( $lock < \time() - 1800 ) {
			self::unlock( $key );
			return false;
		}

		return true;
	}

	/**
	 * Get the next scheduled hook.
	 *
	 * @param string $hook The hook name.
	 * @return int|bool The timestamp of the next scheduled hook, or false if none found.
	 */
	private static function next_scheduled_hook( $hook ) {
		$crons = _get_cron_array();
		if ( empty( $crons ) ) {
			return false;
		}

		// Get next event.
		$next = false;
		foreach ( $crons as $timestamp => $cron ) {
			if ( isset( $cron[ $hook ] ) ) {
				$next = $timestamp;
				break;
			}
		}

		return $next;
	}

	/**
	 * Send announces.
	 *
	 * @param int                            $outbox_activity_id The outbox activity ID.
	 * @param \Activitypub\Activity\Activity $activity           The activity object.
	 * @param int                            $actor_id           The actor ID.
	 * @param int                            $content_visibility The content visibility.
	 */
	public static function schedule_announce_activity( $outbox_activity_id, $activity, $actor_id, $content_visibility ) {
		// Only if we're in both Blog and User modes.
		if ( ACTIVITYPUB_ACTOR_AND_BLOG_MODE !== \get_option( 'activitypub_actor_mode', ACTIVITYPUB_ACTOR_MODE ) ) {
			return;
		}

		// Only if this isn't the Blog Actor.
		if ( Actors::BLOG_USER_ID === $actor_id ) {
			return;
		}

		// Only if the content is public or quiet public.
		if ( ACTIVITYPUB_CONTENT_VISIBILITY_PUBLIC !== $content_visibility ) {
			return;
		}

		// Only if the activity is a Create.
		if ( 'Create' !== $activity->get_type() ) {
			return;
		}

		if ( ! is_object( $activity->get_object() ) ) {
			return;
		}

		// Check if the object is an article, image, audio, video, event, or document and ignore profile updates and other activities.
		if ( ! in_array( $activity->get_object()->get_type(), Base_Object::TYPES, true ) ) {
			return;
		}

		$announce = new Activity();
		$announce->set_type( 'Announce' );
		$announce->set_actor( Actors::get_by_id( Actors::BLOG_USER_ID )->get_id() );
		$announce->set_object( $activity );

		$outbox_activity_id = Outbox::add( $announce, Actors::BLOG_USER_ID );

		if ( ! $outbox_activity_id ) {
			return;
		}

		// Schedule the outbox item for federation.
		self::schedule_outbox_activity_for_federation( $outbox_activity_id, 120 );
	}
}
