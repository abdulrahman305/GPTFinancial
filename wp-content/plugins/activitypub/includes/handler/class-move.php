<?php
/**
 * Move handler file.
 *
 * @package Activitypub
 */

namespace Activitypub\Handler;

use Activitypub\Http;
use Activitypub\Collection\Followers;

use function Activitypub\object_to_uri;

/**
 * Handle Move requests.
 *
 * @see https://www.w3.org/TR/activitystreams-vocabulary/#dfn-move
 * @see https://docs.joinmastodon.org/user/moving/
 * @see https://docs.joinmastodon.org/spec/activitypub/#Move
 */
class Move {
	/**
	 * Initialize the class, registering WordPress hooks.
	 */
	public static function init() {
		\add_action( 'activitypub_inbox_move', array( self::class, 'handle_move' ) );
		\add_filter( 'activitypub_get_outbox_activity', array( self::class, 'outbox_activity' ) );
	}

	/**
	 * Handle Move requests.
	 *
	 * @param array $activity The JSON "Move" Activity.
	 */
	public static function handle_move( $activity ) {
		$target = self::extract_target( $activity );
		$origin = self::extract_origin( $activity );

		if ( ! $target || ! $origin ) {
			return;
		}

		$target_object = Http::get_remote_object( $target );
		$origin_object = Http::get_remote_object( $origin );

		$verified = self::verify_move( $target_object, $origin_object );

		if ( ! $verified ) {
			return;
		}

		$target_follower = Followers::get_follower_by_actor( $target );
		$origin_follower = Followers::get_follower_by_actor( $origin );

		/*
		 * If the new target is followed, but the origin is not,
		 * everything is fine, so we can return.
		 */
		if ( $target_follower && ! $origin_follower ) {
			return;
		}

		/*
		 * If the new target is not followed, but the origin is,
		 * update the origin follower to the new target.
		 */
		if ( ! $target_follower && $origin_follower ) {
			$origin_follower->from_array( $target_object );
			$origin_follower->set_id( $target );
			$origin_id = $origin_follower->upsert();

			global $wpdb;
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$wpdb->update(
				$wpdb->posts,
				array( 'guid' => sanitize_url( $target ) ),
				array( 'ID' => sanitize_key( $origin_id ) )
			);

			// Clear the cache.
			wp_cache_delete( $origin_id, 'posts' );
			return;
		}

		/*
		 * If the new target is followed, and the origin is followed,
		 * move users and delete the origin follower.
		 */
		if ( $target_follower && $origin_follower ) {
			$origin_users = \get_post_meta( $origin_follower->get__id(), '_activitypub_user_id', false );
			$target_users = \get_post_meta( $target_follower->get__id(), '_activitypub_user_id', false );

			// Get all user ids from $origin_users that are not in $target_users.
			$users = \array_diff( $origin_users, $target_users );

			foreach ( $users as $user_id ) {
				\add_post_meta( $target_follower->get__id(), '_activitypub_user_id', $user_id );
			}

			$origin_follower->delete();
		}
	}

	/**
	 * Convert the object and origin to the correct format.
	 *
	 * @param \Activitypub\Activity\Activity $activity The Activity object.
	 * @return \Activitypub\Activity\Activity The filtered Activity object.
	 */
	public static function outbox_activity( $activity ) {
		if ( 'Move' === $activity->get_type() ) {
			$activity->set_object( object_to_uri( $activity->get_object() ) );
			$activity->set_origin( $activity->get_actor() );
			$activity->set_target( $activity->get_object() );
		}

		return $activity;
	}

	/**
	 * Extract the target from the activity.
	 *
	 * The ActivityStreams spec define the `target` attribute as the
	 * destination of the activity, but Mastodon uses the `object`
	 * attribute to move profiles.
	 *
	 * @param array $activity The JSON "Move" Activity.
	 *
	 * @return string|null The target URI or null if not found.
	 */
	private static function extract_target( $activity ) {
		if ( ! empty( $activity['target'] ) ) {
			return object_to_uri( $activity['target'] );
		}

		if ( ! empty( $activity['object'] ) ) {
			return object_to_uri( $activity['object'] );
		}

		return null;
	}

	/**
	 * Extract the origin from the activity.
	 *
	 * The ActivityStreams spec define the `origin` attribute as source
	 * of the activity, but Mastodon uses the `actor` attribute as source
	 * to move profiles.
	 *
	 * @param array $activity The JSON "Move" Activity.
	 *
	 * @return string|null The origin URI or null if not found.
	 */
	private static function extract_origin( $activity ) {
		if ( ! empty( $activity['origin'] ) ) {
			return object_to_uri( $activity['origin'] );
		}

		if ( ! empty( $activity['actor'] ) ) {
			return object_to_uri( $activity['actor'] );
		}

		return null;
	}

	/**
	 * Verify the move.
	 *
	 * @param array $target_object The target object.
	 * @param array $origin_object The origin object.
	 *
	 * @return bool True if the move is verified, false otherwise.
	 */
	private static function verify_move( $target_object, $origin_object ) {
		// Check if both objects are valid.
		if ( \is_wp_error( $target_object ) || \is_wp_error( $origin_object ) ) {
			return false;
		}

		// Check if both objects are persons.
		if ( 'Person' !== $target_object['type'] || 'Person' !== $origin_object['type'] ) {
			return false;
		}

		// Check if the target and origin are not the same.
		if ( $target_object['id'] === $origin_object['id'] ) {
			return false;
		}

		// Check if the target has an alsoKnownAs property.
		if ( empty( $target_object['also_known_as'] ) ) {
			return false;
		}

		// Check if the origin is in the alsoKnownAs property of the target.
		if ( ! in_array( $origin_object['id'], $target_object['also_known_as'], true ) ) {
			return false;
		}

		// Check if the origin has a movedTo property.
		if ( empty( $origin_object['movedTo'] ) ) {
			return false;
		}

		// Check if the movedTo property of the origin is the target.
		if ( $origin_object['movedTo'] !== $target_object['id'] ) {
			return false;
		}

		return true;
	}
}
