<?php 
/*!
 * Jetpack CRM
 * https://jetpackcrm.com
 *
 * WooSync: Contact Tabs
 *  Adds extra tabs to contact single view: vitals tab set
 */
namespace Automattic\JetpackCRM;

// block direct access
defined( 'ZEROBSCRM_PATH' ) || exit( 0 );

/**
 * WooSync Contact Tabs class
 */
class Woo_Sync_Contact_Tabs {

    /**
     * The single instance of the class.
     */
    protected static $_instance = null;

    /**
     * Setup WooSync Contact Tabs
     * Note: This will effectively fire after core settings and modules loaded
     * ... effectively on tail end of `init`
     */
    public function __construct( ) {

        // Initialise Hooks
        $this->init_hooks();

    }
        

    /**
     * Main Class Instance.
     *
     * Ensures only one instance of Woo_Sync_Contact_Tabs is loaded or can be loaded.
     *
     * @since 2.0
     * @static
     * @see 
     * @return Woo_Sync_Contact_Tabs main instance
     */
    public static function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    /**
     * Initialise Hooks
     */
    private function init_hooks( ){

        // add in tabs
        add_filter( 'jetpack-crm-contact-vital-tabs', array( $this, 'append_info_tabs' ) , 10, 2 );


    }



    /**
     * Wire in ant applicable tabs (subs, memberships, bookings)
     */
    public function append_info_tabs( $array, $id ) {

        if ( !is_array($array) ){
            $array = array();
        }

        // Woo Subscriptions
        if ( function_exists( 'wcs_get_users_subscriptions' ) ){
            $array[] = array(
                'id' => 'woocommerce-subscriptions-tab',
                'name'    => __( 'Subscriptions', 'zero-bs-crm' ),
                'content' => $this->generate_subscriptions_tab_html( $id ),
                );
        }

        // Woo Memberships
        if ( function_exists( 'wc_memberships_get_user_memberships' ) ){
            $array[] = array(
                'id' => 'woocommerce-memberships-tab',
                'name' => __('Memberships', 'zero-bs-crm'),
                'content' => $this->generate_memberships_tab_html( $id )
                );
        }

        // Woo Bookings
        if ( class_exists( 'WC_Bookings_Controller' ) ){
            $array[] = array(
                'id' => 'woocommerce-bookings-tab',
                'name' => __('Upcoming Bookings', 'zero-bs-crm'),
                'content' => $this->generate_bookings_tab_html( $id )
                );
        }

        return $array;

    }


    /**
     * Draw the Woo Bookings Contact Vitals tab
     */
    private function generate_bookings_tab_html( $object_id = -1 ){

        global $zbs;

        // return html
        $html = '';

        // retrieve bookings
        $bookings = $zbs->modules->woosync->get_future_woo_bookings_for_object( $object_id );

        if ( count( $bookings ) > 0 ){

            $html .=  '<div class="table-wrap woo-sync-book">';
                $html .= '<table class="ui single line table">';
                    $html .= '<thead>';
                    $html .= '<tr>';
                        $html .= '<th scope="col" class="booking-id">' . __( 'ID', 'woocommerce-bookings' ) . '</th>';
                        $html .= '<th scope="col" class="booked-product">' . __( 'Booked', 'woocommerce-bookings' ) . '</th>';
                        $html .= '<th scope="col" class="booking-start-date">' . __( 'Start Date', 'woocommerce-bookings') . '</th>';
                        $html .= '<th scope="col" class="booking-end-date">' . __( 'End Date', 'woocommerce-bookings' ) . '</th>';
                        $html .= '<th scope="col" class="booking-status">' .  __( 'Status', 'woocommerce-bookings' )  . '</th>';
                    $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                foreach ( $bookings as $booking ){

                        $html .= '<tr>';
                            $html .= '<td class="booking-id">' . esc_html( $booking->get_id() ) . '</td>';
                            $html .= '<td class="booked-product">';
                                if ( $booking->get_product() && $booking->get_product()->is_type( 'booking' ) ) : 
                                $html .= '<a href="' . esc_url( get_permalink( $booking->get_product()->get_id() ) ) . '">';
                                    $html .=  esc_html( $booking->get_product()->get_title() );
                                $html .= '</a>';
                                endif; 
                            $html .= '</td>';

                            
                            $status = esc_html( wc_bookings_get_status_label( $booking->get_status() ) );

                            $html .= '<td class="booking-start-date">' . esc_html( $booking->get_start_date() ) . '</td>';
                            $html .= '<td class="booking-end-date">' . esc_html( $booking->get_end_date() ) . '</td>';
                            $html .=  '<td><span class="ui label ' . strtolower( $status ) . '">'.$status.'</span></td>';
      
                        $html .= '</tr>';
                    }

                    $html .= '</tbody>';
                $html .= '</table>';
            $html .= '</div>';

        } else {

            $html .=  '<div class="ui message info blue"><i class="ui icon info circle"></i>' . __( "This contact does not have any upcoming WooCommerce Bookings.", 'zero-bs-crm' ) . '</div>';
        
        }

        return $html;
    }
    


	/**
	 * Returns HTML that can be used to render the Subscriptions Table.
	 * When no WordPress user is found a <div> is returned with an info message.
	 * When no subscriptions are found a <div> is returned with an info message.
	 *
	 * @param integer $object_id Contact ID.
	 * @return string HTML that can be used to render the Subscriptions Table.
	 */
	private function generate_subscriptions_tab_html( $object_id = -1 ) {

		$subscriptions = array();
		if ( zeroBSCRM_getClientPortalUserID( $object_id ) > 0 ) {
			// Retrieve any subs against the main email or aliases.
			$subscriptions = $this->get_contact_subscriptions( $object_id );
		}
		if ( count( $subscriptions ) === 0 ) {
			return '<div class="ui message info blue"><i class="ui icon info circle"></i>' . __( 'This contact does not have any WooCommerce Subscriptions yet.', 'zero-bs-crm' ) . '</div>';
		}

		$html = '';
		$html .= '<div class="table-wrap woo-sync-subs">';
		$html .= '<table class="ui single line table">';
		$html .= '<thead><tr>';
		$html .= '<th>' . __( 'Subscription', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Status', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Amount', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Start', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Renews', 'zero-bs-crm' ) . '</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';

		foreach ( $subscriptions as $order_id ) {
			$order        = wc_get_order( $order_id );
			$status       = $order->get_status();
			$date_created = $order->get_date_created();
			$date_renew   = $order->get_date( 'next_payment_date' );
			$price        = $order->get_formatted_order_total();
			$name         = '';
			$sub_link     = admin_url( "post.php?post={$order_id}&action=edit" );
			$created      = jpcrm_uts_to_date_str( strtotime( $date_created ) );
			$next         = jpcrm_uts_to_date_str( strtotime( $date_renew ) );

			$html .= '<tr>';
			$html .= '<td><a href="' . esc_url( $sub_link ) . '">' . $name . __( ' Subscription #', 'zero-bs-crm' ) . $order_id . '</a></td>';
			$html .= '<td><span class="ui label ' . $status . '">' . $status . '</span></td>';
			$html .= '<td>' . $price . '</td>';
			$html .= '<td>' . $created . '</td>';
			$html .= '<td>' . $next . '</td>';
			$html .= '</tr>';

		}

		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';

		return $html;

	}


	/**
	 * Generate HTML for memberships contact vitals tab
	 */
	private function generate_memberships_tab_html( $object_id = -1 ) {

		$data = $this->get_contact_memberships( $object_id );

		if ( $data['message'] === 'notfound' || count( $data['memberships'] ) <= 0 ) {

			return '<div class="ui message info blue"><i class="ui icon info circle"></i>' . __( 'This contact does not have any WooCommerce Memberships yet.', 'zero-bs-crm' ) . '</div>';
		
		}

		$memberships = $data['memberships'];

		$html = '';
		$html .= '<div class="table-wrap woo-sync-mem">';
		$html .= '<table class="ui single line table">';
		$html .= '<thead><tr>';
		$html .= '<th>' . __( 'Membership', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Status', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Name', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Start', 'zero-bs-crm' ) . '</th>';
		$html .= '<th>' . __( 'Expires', 'zero-bs-crm' ) . '</th>';
		$html .= '</tr></thead>';
		$html .= '<tbody>';

		foreach ( $memberships as $membership ) {

			// populate fields
			$member_id    = $membership->id;
			$status       = $this->display_membership_status( $membership->get_status() );
			$name         = $membership->plan->name;
			$date_expires = $membership->get_end_date( 'Y-m-d H:i:s' );
			$date_created = $membership->get_start_date();
			$created      = jpcrm_uts_to_date_str( strtotime( $date_created ) );

			if ( empty( $date_expires ) ) {

				$expires = __( 'Never', 'zero-bs-crm' );

			} else {

				$expires = jpcrm_uts_to_date_str( strtotime( $date_expires ) );

			}

			$membership_link = admin_url( 'post.php?post=' . $membership->id . '&action=edit' );

			$html .= '<tr>';
			$html .= '<td><a href="' . esc_url( $membership_link ) . '">' . sprintf( __( ' Membership #%s', 'zero-bs-crm' ), $member_id ) . '</a></td>';
			$html .= '<td><span class="ui label ' . $status . '">' . $status . '</span></td>';
			$html .= '<td>' . $name . '</td>';
			$html .= '<td>' . $created . '</td>';
			$html .= '<td>' . $expires . '</td>';
			$html .= '</tr>';

		}

		$html .= '</tbody>';
		$html .= '</table>';
		$html .= '</div>';

		return $html;
	}


    /**
     * Helper to display membership statuses
     */
    private function display_membership_status( $status = '' ){

        $woocommerce_statuses = array(
            'wcm-active'                => __('active', 'zero-bs-crm'),
            'wcm-complimentary'         => __('complimentary', 'zero-bs-crm'),
            'wcm-pending'               => __('pending', 'zero-bs-crm'),
            'wcm-delayed'               => __('delayed', 'zero-bs-crm'),
            'wcm-pending-cancelletion'  => __('pending cancellation', 'zero-bs-crm'),
            'wcm-paused'                => __('paused', 'zero-bs-crm'),
            'wcm-expired'               => __('expired', 'zero-bs-crm'),
            'wcm-cancelled'             => __('cancelled', 'zero-bs-crm')
        );

        $display_status = $status;

        if ( array_key_exists( $status, $woocommerce_statuses ) ){

            $display_status = $woocommerce_statuses[$status];

        }

        return $display_status;

    }


    /**
     * Retrieves memberships for a contact
     */
    private function get_contact_memberships( $object_id = -1 ){

        $wp_id = zeroBS_getCustomerWPID( $object_id );

        if ( $wp_id > 0 ){

            return array(
                'message' => 'success',
                'memberships' => wc_memberships_get_user_memberships( $wp_id )
            );

        } else {

            return array(
                'message' => 'notfound',
                'memberships' => array()
            );

        }
    }

	/**
	 * Retrieves any Woo Subscriptions against a contact
	 *
	 * @param int $object_id Contact ID.
	 */
	private function get_contact_subscriptions( $object_id = -1 ) {
		$all_sub_statuses = array_keys( wcs_get_subscription_statuses() );

		$subscription_ids = array();

		if ( $object_id > 0 ) {

			// 1 - get the subscription IDs for the attached wp user
			$user_id = zeroBS_getCustomerWPID( $object_id );
			if ( $user_id > 0 ) {
				$args = array(
					'customer_id' => $user_id,
					'status'      => $all_sub_statuses,
					'type'        => 'shop_subscription',
					'return'      => 'ids',
				);

				$subscription_ids = wc_get_orders( $args );
			}

			// 2 - find subs for all emails (inc aliases)
			global $zbs;
			$emails = $zbs->DAL->contacts->getContactEmails( $object_id ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( is_array( $emails ) ) {

				foreach ( $emails as $email ) {
					if ( empty( $email ) ) {
						continue;
					}

					$args = array(
						'billing_email' => $email,
						'status'        => $all_sub_statuses,
						'type'          => 'shop_subscription',
						'return'        => 'ids',
					);

					$subscription_ids_by_email = wc_get_orders( $args );
					$subscription_ids          = array_merge( $subscription_ids, $subscription_ids_by_email );
				}
			}

			// 3 - remove any duplicate IDs between array_1 and array_2
			$subscription_ids = array_unique( $subscription_ids, SORT_REGULAR );
		}

		return $subscription_ids;
	}
}
