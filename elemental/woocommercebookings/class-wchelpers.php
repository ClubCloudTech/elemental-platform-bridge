<?php
/**
 * Helpers for Woocommerce Bookings
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WoocommerceBookings;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Shortcode\MyVideoRoomApp;
use ElementalPlugin\WCFM\Library\WCFMHelpers;

/**
 * Class WCHelpers
 */
class WCHelpers extends Shortcode {



	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'getmystore', array( $this, 'get_my_store' ) );
		$this->add_shortcode( 'store', array( $this, 'get_store' ) );
		$this->add_shortcode( 'slug', array( $this, 'get_slug' ) );
		$this->add_shortcode( 'upcoming', array( $this, 'get_vendor_upcoming_bookings' ) );

		add_filter( 'woocommerce_email_attachments', array( $this, 'jp_add_ics_to_woocommerce_emails_filter' ), 10, 3 );
	}

	/**
	 * Returns upcoming booking id's sorted by time
	 *
	 * @param string $number the number of bookings to return.
	 *
	 * @return array an Array with Booking IDs sorted soonest to latest
	 */
	public function get_vendor_upcoming_bookings( $number = '' ) {
		if ( ! $number ) {
			// number to get bookings monitors from.
			$number = $this->get_instance( SiteDefaults::class )->defaults( 'numberupcomingbookings' );
		}

		$current_time     = current_time( 'timestamp' );
		$max_time_window  = $current_time + $this->get_instance( SiteDefaults::class )->defaults( 'timewindowfilter' );
		$vendor_bookings  = apply_filters( 'wcfm_wcb_include_bookings', '' ); // get all bookings for vendor          @TODO - Is this the best way to filter?? We are pulling every booking ever
		$out_future_array = array();

		foreach ( $vendor_bookings as $booking_id ) {
			// implement time filtration and reject past - or early meetings.

			$booking         = \get_wc_booking( $booking_id );
			$start_date      = $booking->get_start_date();
			$end_date        = $booking->get_end_date();
			$end_timestamp   = strtotime( $end_date );
			$start_timestamp = strtotime( $start_date );

			if ( $current_time < $end_timestamp && $start_timestamp < $max_time_window ) {
				// $orderfuture = $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'dateonly', 0 );     @TODO - Is this the best way to filter?? We are pulling every booking ever

				array_push( $out_future_array, $booking_id );
			} //Room Window for entry is open - push options into two arrays.

		}
		// sort array by soonest to latest- and checking if there is anything to display.
		$outsort = array_column( $out_future_array, 'startstamp' );
		array_multisort( $outsort, SORT_ASC, $out_future_array );
		$returnarray = array();
		$returncount = count( $out_future_array );
		if ( $returncount < $number ) {
			$number = $returncount;
		}
		// Displaying IF there is content.
		if ( $number >= 1 ) {

			for ( $x = 0; $x <= $number - 1; $x ++ ) {
				array_push( $returnarray, $out_future_array[ $x ] );
			}

			return $returnarray;
		} else {
			return array();
		}
	}

	/**
	 * Returns Staff Store Parent Name
	 *
	 * @param string $type
	 *
	 * @return int|mixed|string|null
	 */
	public function get_my_store( $type = 'storename' ) {

		$user_id = \get_current_user_id();

		if ( 'visitor' === $type ) {
			$id = \get_the_author_meta( 'ID' );
			if ( ! $id ) {
				$id = \bp_displayed_user_id();
			}

			$user = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $id );

			return $this->get_instance( SiteDefaults::class )->defaults( 'marketplace_url' ) . $user->user_nicename . '/';
		}

		$parent_id  = $this->get_instance( WCFMHelpers::class )->staff_to_parent( $user_id );
		$store_user = \wcfmmp_get_store( $parent_id );
		$store_info = $store_user->get_shop_info();

		if ( 'id' === $type ) {
			return $parent_id;
		} elseif ( 'url' === $type ) {
			$user = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $parent_id );

			return $this->get_instance( SiteDefaults::class )->defaults( 'marketplace_url' ) . $user->user_nicename . '/' . $this->get_instance( SiteDefaults::class )->defaults( 'video_storefront_slug' );
		} else {
			return $store_info['store_name'];
		}
	}

	/**
	 * Extracts the Current Store name and format it correctly
	 * Used by Merchant Pages to generate Hyper-links for Video  - the WCFM shortcode doesnt format the Store Name Correctly
	 * May be deprecated by Use of CCname
	 *
	 * @param string $type
	 *
	 * @return int|mixed|void|null
	 */
	public function get_store( $type = '' ) {
		// 'id' argument for url switch function to call from BP only
		global $WCFM;

		if ( 'id' === $type ) {
			$store_id = $this->get_instance( WCFMHelpers::class )->staff_to_parent( get_current_user_id() );
		} else {
			$post     = get_post();
			$store_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );
		}

		$store_user = \wcfmmp_get_store( $store_id );
		$store_info = $store_user->get_shop_info();
		$store_name = isset( $store_info['store_name'] ) ? \esc_html( $store_info['store_name'] ) : __( 'N/A', 'wc-multivendor-marketplace' );
		$store_name = apply_filters( 'wcfmmp_store_title', $store_name, $store_id );

		if ( 'id' === $type ) {
			return $store_id;
		} else {
			return $store_name;
		}
	}

	/**
	 * Gets Slug from a Store
	 *
	 * @param array $params
	 *
	 * @return mixed|string
	 */
	public function get_slug( $params = array() ) {
		global $WCFM;

		if ( $params['type'] ) {
			$type = $params['type'];
		} else {
			$type = 'store';
		}

		if ( $params['id'] ) {
			$id = $params['id'];
		} else {
			$id = 'id';
		}

		if ( 'login' === $type ) {
			$user = \wp_get_current_user();

			return $user->user_login;
		}

		if ( $id ) {
			$store_id = $id;
		} else {
			$store_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( \get_post()->ID );
		}

		// get vendor from store.
		$store_user = \wcfmmp_get_store( $store_id );
		$store_info = $store_user->get_shop_info();
		$store_data = $store_info['store_slug'];

		if ( 'user' === $type ) {
			$user = \wp_get_current_user();
			return $user->user_nicename;
		}

		return $store_data;
	}

	/**
	 * Returns Order Information by Signed in User
	 *
	 * @param $order
	 * @param $time_offset
	 * @param false       $showpast
	 *
	 * @return array  an Array with VendorID, Store Name, Product ID, and Product Name (or multiple arrays)
	 */
	public function bookings_by_order( $order, $time_offset, $showpast = false ) {
		global $WCFM;

		$current_time = current_time( 'timestamp' );
		$order_detail = \WC_Booking_Data_Store::get_booking_ids_from_order_id( $order );

		$no_bookings        = array();
		$bookings           = array();
		$future_count       = 0;
		$invalid_count      = 0;
		$order_window_count = 0;

		foreach ( $order_detail as $booking_id ) {
			// implement time filtration and reject past - or early meetings.
			$booking         = \get_wc_booking( $booking_id );
			$start_date      = $booking->get_start_date();
			$end_date        = $booking->get_end_date();
			$end_timestamp   = strtotime( $end_date );
			$start_timestamp = strtotime( $start_date );
			$current15_time  = $current_time + $time_offset;

			// Get Store Information for Friendly Display.
			$infoposta  = \get_wc_booking( $booking_id );
			$infopostb  = $infoposta->product_id;
			$vendor_id  = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $infopostb );
			$store_user = \wcfmmp_get_store( $vendor_id );
			$store_info = $store_user->get_shop_info();
			$store_name = $store_info['store_name'];

			if ( $current_time >= $end_timestamp && $showpast ) {
				$orderpast = '<div style="font-size:1.50em;line-height: 1.5;color:black">Booking ' . $booking_id . ' occurs in the past and can no longer be accessed. <br></div>';
				array_push( $no_bookings, $orderpast );
			} elseif ( $current15_time < $start_timestamp ) {
				$orderfuture = $this->get_booking_header( $booking_id, 'customer', 0 );
				array_push( $no_bookings, $orderfuture );
				$future_count ++;
			} elseif ( $current_time < $end_timestamp ) {
				// Room Window for entry is open - push options into two arrays.
				$order_window_count ++;
				$menuchoice = '<div style="font-size:1.5em;line-height: 1.5;color:black"><a href="' . get_site_url() . '/go?booking=' . $booking_id . '&order=' . $order . '">Booking - ' . $booking_id . ' with ' . $store_name . ' Starts at: ' . $start_date . '..</a> <br></div>';
				array_push( $bookings, $menuchoice );
			}
		}

		return array(
			'validcount'   => $order_window_count,
			'invalidcount' => $invalid_count,
			'future_count' => $future_count,
			'validlinks'   => $bookings,
			'rejections'   => $no_bookings,
		);
	}

	/**
	 * Returns Product Information and Vendor Info from Order Numbers
	 *
	 * @param $order
	 *
	 * @return array|string n Array with VendorID, Store Name, Product ID, and Product Name (or multiple arrays)
	 */
	public function orderinfo_by_ordernum( $order ) {
		global $WCFM;

		$order = $order + 0;
		if ( ! $order ) {
			return 'Blank Order';
		}
		$orderinfo = \wc_get_order( $order );

		$items  = $orderinfo->get_items();
		$output = array();
		foreach ( $items as $item ) {
			$product_name = $item->get_name();
			$product_id   = $item->get_product_id();
			$vendor_id    = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
			$store_user   = \wcfmmp_get_store( $vendor_id );
			$store_info   = $store_user->get_shop_info();
			$store_name   = $store_info['store_slug'];
			$out_data     = array(
				'productname' => $product_name,
				'productid'   => $product_id,
				'vendorid'    => $vendor_id,
				'storename'   => $store_name,
			);
			array_push( $output, $out_data );
		}

		return $output;
	}

	/**
	 * Club Cloud - A Function to Validate a Order Number - and Ensure it exists
	 *
	 * @param string $ordnum Woocommerce Order Number (postID) - passed into it as string.
	 *
	 * @return bool False for invalid Number  - True for valid order number
	 */
	public function validate_order( $ordnum ): bool {
		if ( 'shop_order' === get_post_type( $ordnum ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Club Cloud - A Function to Filter Expired Bookings
	 *
	 * @param $booking            BookingID - passed into it as string
	 * @param $time_offset        TimeOffset (to check how long in future we allow meetings to be entered)
	 * @param $return_menu_option if Menu items for multiple bookings need to be constructed
	 * @param $xprofile_field
	 *
	 * @return array|bool|int|mixed|string|void Formatted Error Message or Booking Object if Booking exists
	 */
	public function validate_booking_time( $booking, $time_offset, $return_menu_option, $xprofile_field ) {
		if ( ! $this->validate_booking( $booking ) ) {
			// reject invalid bookings.
			return;
		}

		// Get booking detail and calculate timestamps.
		$current_time    = current_time( 'timestamp' );
		$bookingdetail   = \get_wc_booking( $booking );
		$start_date      = $bookingdetail->get_start_date();
		$end_date        = $bookingdetail->get_end_date();
		$end_timestamp   = strtotime( $end_date );
		$start_timestamp = strtotime( $start_date );
		$current15_time  = $current_time + $time_offset;

		// Time Filter Section - Filter out Too late- then Too far in future - then look at in time window.

		if ( $current_time >= $end_timestamp ) {
			// too late - after time window.
			if ( 'singlebook' === $return_menu_option || 'checkonly' === $return_menu_option ) {
				return true;
			}
		} elseif ( $current15_time < $start_timestamp ) {
			// Too Far in the future - not in window.
			if ( 'singlebook' === $return_menu_option || 'checkonly' === $return_menu_option ) {
				return true;
			}
		} elseif ( $current15_time > $start_timestamp && $current_time < $end_timestamp ) {
			// In Time Window.
			if ( 'checkonly' === $return_menu_option ) {
				return $this->get_booking_header( $booking, 'customer', 0 );
			} elseif ( 'singleid' === $return_menu_option ) {
				return $booking;
			}
		}

		// Construct Messages.
		if ( in_array(
			$return_menu_option,
			array(
				'singlebook',
				'message',
				'singleid',
				'merchantbook',
				'messagecustomer',
			),
			true
		)
		) {
			// return $return_menu_option." RetMenu at 1669<br>";
			// Get template for room
			$xprofile_setting = $this->get_instance( ShortCodeConstructor::class )->xprofile_build( $xprofile_field, $booking, 0 );
			if ( ! $xprofile_setting ) {
				// get site details from user1's backup site field.
				$xprofile_setting = \xprofile_get_field_data( 2560, 1 );
			}

			switch ( $return_menu_option ) {
				case 'singleid':
					return $booking;

				case 'message':
					$store_name = $this->orderinfo_by_booking( $booking, 'store_name', 0 );
					return $this->get_booking_header( $booking, 'merchant', $store_name );

				case 'messagecustomer':
					$store_name = $this->orderinfo_by_booking( $booking, 'store_name', 0 );
					return $this->get_booking_header( $booking, 'customer', $store_name );

				case 'merchantbook':
					$myvideoroom_app_app = MyVideoRoomApp::create_instance(
						$this->get_instance( SiteDefaults::class )->room_map( 'bookings', $booking ),
						$xprofile_setting,
					)->enable_admin();

					return $myvideoroom_app_app->output_shortcode();

				case 'singlebook':
					$myvideoroom_app_app = MyVideoRoomApp::create_instance(
						$this->get_instance( SiteDefaults::class )->room_map( 'bookings', $booking ),
						$xprofile_setting,
					)->enable_reception();

					return $myvideoroom_app_app->output_shortcode();

			}
		} else {
			return true;
		}
	}

	/**
	 * Club Cloud A function to format Merchant helpful information in Bookings
	 * Arguments- BookingID - passed into it as string Returns - Formatted Message to Merchant or Error
	 *
	 * @param $booking_id
	 * @param $sc_type
	 * @param $merchant_name
	 *
	 * @return array|int|mixed|string
	 */
	public function get_booking_header( $booking_id, $sc_type, $merchant_name ) {
		$booking_is_valid = $this->validate_booking( (int) $booking_id );

		// trapping blank entry.
		if ( ! $booking_is_valid ) {
			return 'Invalid Booking Number is Entered.';
		}

		$dp            = \get_wc_booking( $booking_id );
		$booking_start = gmdate( 'F j, Y, g:i a', $dp->get_start() );

		// adding Merchant Name Information.
		if ( ! $merchant_name ) {
			$merchant_name = $this->orderinfo_by_booking( $booking_id, 'store_name', 0 );
		}

		// formatting Customer Information.
		$customer_id   = $dp->get_customer_id();
		$customer_info = \get_userdata( $customer_id );
		$user_nice     = $customer_info->user_nicename;

		if ( 'dateonly' === $sc_type ) {
			$out_data = array();
			array_push( $out_data, $dp->get_start(), $dp->get_end(), $booking_id );

			return $out_data;
		}

		switch ( $sc_type ) {
			case 'merchant':
				return '<div style="font-size:1.5em;line-height: 1.5;color:black">Booking: ' . $booking_id . ' Starts: ' . $booking_start . ' with Customer: ' . $user_nice . '<br></div>';

			case 'merchantinfo':
				return 'Booking: ' . $booking_id . ' Starts: ' . $booking_start . ' with Customer: ' . $user_nice;

			case 'customer':
				return '<div style="font-size:1.5em;line-height: 1.5;color:black">Booking: ' . $booking_id . ' Starts: ' . $booking_start . ' with Merchant: ' . $merchant_name . '<br></div>';

			case 'customerid':
				return $customer_id;

			default:
				return 'Invalid Shortcode Argument<br>';
		}
	}

	/**
	 * This function/filter will add ics files from bookings created with WooCommerce Bookings to
	 * the Processing and Completed emails sent from WooCommerce itself.
	 *
	 * @param array  $attachments Current array of attachments being filtered.
	 * @param string $email_id    The id of the email being sent.
	 * @param object $order       The order for which the email is being sent.
	 *
	 * @return array The filtered list of attachments.
	 */
	public function jp_add_ics_to_woocommerce_emails_filter( $attachments, $email_id, $order ): array {
		// The woocommerce email ids for which you want to attach the ics files.
		$available = array(
			'customer_processing_order',
			'customer_completed_order',
		);

		// Check to make sure we have a match.
		if ( in_array( $email_id, $available, true ) ) {

			// Get the booking ids from the order, and get the exporter object.
			$booking_ids = \WC_Booking_Data_Store::get_booking_ids_from_order_id( $order->get_id() );
			$generate    = new \WC_Bookings_ICS_Exporter();

			// Go through each id and add the attachments.
			foreach ( $booking_ids as $booking_id ) {
				$booking       = get_wc_booking( $booking_id );
				$attachments[] = $generate->get_booking_ics( $booking );

				// If the object is not unset, then the New Booking Email is sent twice.
				unset( $booking );
			}
		}

		return $attachments;
	}

	/**
	 * Club Cloud - A Function to Validate a Booking ID - and Ensure it exists
	 *
	 * @param int $booking_id The booking id.
	 *
	 * @return boolean
	 */
	public function validate_booking( $booking_id ): bool {
		// reject blank bookings.
		if ( ! $booking_id ) {
			return false;
		}

		// get booking from woocommerce.
		$booking = get_wc_booking( $booking_id );

		// check if there is an order ID in the booking object (impossible not to have one if it is real).
		return ! ! ( $booking && $booking->get_order_id() );
	}

	/**
	 * Returns Product Information and Vendor Info from Booking Numbers
	 *
	 * @param $booking_id
	 * @param $field_option
	 * @param $vendor_id
	 *
	 * @return mixed|string an Array with VendorID, Store Name, Product ID, and Product Name (or multiple arrays)
	 */
	public function orderinfo_by_booking( $booking_id, $field_option, $vendor_id ) {
		global $WCFM;

		if ( $vendor_id && 'store_slug' === $field_option ) {
			$store_user = \wcfmmp_get_store( $vendor_id );
			$store_info = $store_user->get_shop_info();
			$store_slug = $store_info['store_slug'];

			return $store_slug;
		}
		$user = \wp_get_current_user();
		if ( ! $booking_id && ! $vendor_id ) {
			return 'Err CC105 - Blank ID';
		}  //reject blank booking and vendor numbers

		/*
		if ( is_array( $booking_id) ){

		}
		else*/  $booking_value = $this->validate_booking( $booking_id );

		if ( $booking_id && ! $booking_value ) {
			return 'Invalid or Deleted Booking';
		} //reject invalid booking numbers if entered
		$booking    = \get_wc_booking( $booking_id );
		$product_id = $booking->product_id;
		$user_roles = $this->get_instance( UserRoles::class );

		if ( ! $vendor_id || $user_roles->is_wcfm_shop_staff() ) {
			$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );
		}

		$store_user = \wcfmmp_get_store( $vendor_id );
		$store_info = $store_user->get_shop_info();
		$store_slug = $store_info['store_slug'];
		$store_name = $store_info['store_name'];

		switch ( $field_option ) {
			case 'store_slug':
				return $store_slug;

			case 'store_name':
				return $store_name;

			case 'vendorid':
				return $vendor_id;
		}
	}

	/**
	 * Returns Order Information by Signed in User
	 *
	 * @param $user_id
	 *
	 * @return array an Array with VendorID, Store Name, Product ID, and Product Name (or multiple arrays)
	 */
	public function orders_by_user( $user_id ): array {
		// Get all customer orders.
		$customer_orders = get_posts(
			array(
				'numberposts' => - 1,
				'meta_key'    => '_customer_user',
				'orderby'     => 'date',
				'order'       => 'DESC',
				'meta_value'  => $user_id,
				'post_type'   => \wc_get_order_types(),
				'post_status' => array_keys( \wc_get_order_statuses() ),
				'post_status' => array( 'wc-processing', 'wc-completed' ),
			)
		);

		$order_array = array();

		foreach ( $customer_orders as $customer_order ) {
			$orderq        = \wc_get_order( $customer_order );
			$bookingscheck = \WC_Booking_Data_Store::get_booking_ids_from_order_id( $customer_order->ID );
			$array_count   = count( $bookingscheck ) . 'Bookings Count<br>';
			if ( $array_count >= 1 ) {
				$order_array[] = $orderq->get_id();
			}
		}

		return $order_array;
	}
}
