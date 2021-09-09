<?php
/**
 * Shortcodes fro woocommerce bookings
 *
 * @package MyVideoRoomExtrasPlugin\WoocommerceBookings
 */

namespace MyVideoRoomExtrasPlugin\WoocommerceBookings;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Library\WordPressUser;
use MyVideoRoomExtrasPlugin\Shortcode as Shortcode;
use MyVideoRoomExtrasPlugin\Library\MeetingIdGenerator;
use MyVideoRoomExtrasPlugin\Shortcode\MyVideoRoomApp;


/**
 * Class ShortCodeConstructor
 */
class ShortCodeConstructor extends Shortcode {


	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'invitemenu', array( $this, 'invite_menu_shortcode' ) );
		$this->add_shortcode( 'searchwcfm', array( $this, 'search_wcfm_shortcode' ) );
		$this->add_shortcode( 'searchyouzergroups', array( $this, 'search_youzer_groups_shortcode' ) );
		$this->add_shortcode( 'test', array( $this, 'test_shortcode' ) );
	}

	/*
		Constructs the MyVideoRoom App Shortcode correctly with right settings
		# Arguments - Shortcode Type, Order Number, VendorID(optional), XProfile Field Number, BookingID, and Time Offset)
		# Returns - a correctly formatted shortcode, or rejection of Booking information*/
	public function shortcode_build( $sc_type, $ordernum, $vendor_id, $xprofile_field, $booking_id, $time_offset, $showpast ) {
		global $WCFM, $WCFMmp;
		if ( $booking_id != '' && is_array( $booking_id ) == false ) {

			$booking_is_valid = $this->get_instance( WCHelpers::class )->validate_booking( $booking_id );

			if ( ! $booking_is_valid ) {        // trapping blank entry
				return 'Invalid Booking Number is Entered';
			}
		}
		if ( is_array( $booking_id ) == true ) {
			$bookingout = array();
			foreach ( $booking_id as $bookingitem ) {
				$vendor_temp = $this->get_instance( \MyVideoRoomExtrasPlugin\WooCommerceBookings\WCHelpers::class )->validate_booking_time( $bookingitem, (int) $time_offset, 'singleid', $xprofile_field );
				if ( $vendor_temp != '' ) {
					array_push( $bookingout, $vendor_temp );
				}
			}
			$booking_id = $bookingout;
		}
		if ( $sc_type == 'merchantsimple' ) {
			$hasbookingflag   = true;
			$merchant_flag    = true;
			$window_bookingid = $booking_id;
			$sc_type          = 'multibooking';
		} elseif ( $sc_type == 'merchant' ) {
			$merchant_flag = true;
			$sc_type       = 'multibooking';
		}
		if ( $sc_type == 'simple' || $sc_type == 'multibooking' ) {
		}       //Reject Gate of No Type Input
		else {
			return 'Invalid Shortcode Type';
		}
		// debug only return "<br>1336 Booking -".$booking_id." Order Num ".$ordernum. " SC Type -".$sc_type. " X Profile Setting - ".$xprofile_field ." Vendor ID".$vendor_id."<br>";
		// set up default parameters
		$time_offsetdisplay = $time_offset / 60;
		$current_time       = current_time( 'timestamp' );
		if ( is_array( $booking_id ) == true ) {
			$orderdetail = $booking_id;
		} elseif ( $sc_type == 'merchant' || $merchant_flag == true ) {
			$orderdetail = apply_filters( 'wcfm_wcb_include_bookings', '' );
		} else {
			$orderdetail = \WC_Booking_Data_Store::get_booking_ids_from_order_id( $ordernum );
		}//Option 3 you must be a user - so pull your individual bookings.

		// Set cases where we have booking ID and order number to simple to bypass heavier logic in multi
		if ( $booking_id != '' && $ordernum != '' && $merchant_flag == false ) {
			$sc_type = 'simple';
		}
		if ( $vendor_id == '' && count( $orderdetail ) <= 1 ) {
			$vendor_id = $orderdetail[0]['vendorid'];
		}
		// Get Vendor IDs for Single Bookings
		if ( $vendor_id == '' && $booking_id != '' ) {
			if ( is_array( $booking_id ) == true ) {
				$bookfirstid = $booking_id[0];
			} else {
				$bookfirstid = $booking_id;
			}
			$info_post    = get_wc_booking( $bookfirstid );
			$info_post_id = $info_post->product_id;
			$vendor_id    = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $info_post_id );
		}
		if ( count( $orderdetail ) <= 1 && $booking_id == '' ) {
			$booking_id = $orderdetail[0];
		}
		// return "<br>982 Booking -".$booking_id." Order Num ".$ordernum. " SC Type -".$sc_type. " X Profile Setting - ".$xprofile_field ." Vendor ID".$vendor_id."<br>";
		if ( ! $vendor_id ) {   // if We have Vendor - Get Store Info
			$layout_id = $this->xprofile_build( $xprofile_field, 0, $vendor_id );
		}
		$store_slug = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $booking_id, 'store_slug', 0 );
		$store_name = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $booking_id, 'store_name', 0 );
		// debug only return  $store_name." Indicated Storename at 990<br>";
		if ( ! $layout_id && is_array( $booking_id ) ) {
			$layout_id = $this->xprofile_build( $xprofile_field, $booking_id[0], 0 );
		} else {
			$layout_id = $this->xprofile_build( $xprofile_field, $booking_id, 0 );
		}
		// return $layout_id;
		if ( $layout_id == 'A Vendor Blank Setting Was Returned from Xprofile Constructor- User Does not Have this Setting Applied' ) {
			$layout_id = xprofile_get_field_data( 2560, 1 ); // get site details from user1's backup site field
		}
		// debug onlyreturn "Both provided -Booking ID ".$booking_id." and Order Num ".$ordernum. " Storename was - ".$store_name."<br>";
		switch ( $sc_type ) {
			case 'simple':
				$myvideoroom_app_app = MyVideoRoomApp::create_instance(
					$this->get_instance( SiteDefaults::class )->room_map( 'bookings', $booking_id ),
					$layout_id,
				);

				$outdatas = array(
					'validcount' => 1,
					'message'    => $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'customer', $store_name ),
					'shortcode'  => $myvideoroom_app_app->output_shortcode(),
				);

				return $outdatas;
				break;
			case 'multibooking':
				$outpastarray           = array();  // Set up counters and prepare arrays
				$outfuturearray         = array();
				$outbookarray           = array();
				$out_to_shortcode_array = array();
				// debug only return "got to 1382 Multibooking<br>";
				$invalid_count      = 0;
				$order_window_count = 0;
				$futurecount        = 0;
				foreach ( $orderdetail as $booking_id ) { // implement time filtration and reject past - or early meetings
					$booking         = get_wc_booking( $booking_id );
					$start_date      = $booking->get_start_date();
					$end_date        = $booking->get_end_date();
					$end_timestamp   = strtotime( $end_date );
					$start_timestamp = strtotime( $start_date );
					$current15_time  = $current_time + $time_offset;
					// Get Store Information for Friendly Display
					$store_name = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $booking_id, 'store_slug', 0 );

					if ( $current_time >= $end_timestamp and $showpast == 'true' ) {
						$orderpast = '<div style="font-size:1.5em;color:black">Booking ' . $booking_id . ' occurs in the past and can no longer be accessed... <br></div>';
						array_push( $outpastarray, $orderpast );
					} elseif ( $current15_time < $start_timestamp ) {
						$orderfuture = $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'merchant', 0 );
						// $orderfuture = '<div style="font-size:1.5em;line-height: 1.3;color:black">Booking ' . $booking_id . ' - '.$start_date.''.print_r( $booking).'<br></div>';
						$futurecount ++;
						array_push( $outfuturearray, $orderfuture );
					} //Room Window for entry is open - push options into two arrays.
					elseif ( $current_time < $end_timestamp ) {
						$order_window_count ++;
						$window_bookingid = $booking_id;
						$menuchoice       = '<div style="font-size:1.5em;line-height: 1.3;color:black"><a href="' . get_site_url() . '/go?booking=' . $booking_id . '">' . $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'merchantinfo', 0 ) . '</a> <br></div>';
						array_push( $outbookarray, $menuchoice );
					}
				}     //return "Order Past- ".$orderpastcount. "- Order Future - ".$orderfuturecount." - Order Window- ".$order_window_count. " Total Loops ".$nobookarray_count."<br>";
				// In Case there is only one viable option - Get Data for Message and call the Shortcode
				if ( $order_window_count == 1 || $hasbookingflag == true ) {
					$store_name = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $window_bookingid, 'store_name', 0 );
					$layout_id  = $this->xprofile_build( $xprofile_field, $window_bookingid, 0 );

					$booking_id = $window_bookingid;

					if ( $merchant_flag ) {

						$myvideoroom_app_app = MyVideoRoomApp::create_instance(
							$this->get_instance( SiteDefaults::class )->room_map( 'bookings', $booking_id ),
							$layout_id,
						);

						$myvideoroom_app_app->enable_admin()->enable_lobby();

						$outdatas = array(
							'validcount'   => $order_window_count,
							'invalidcount' => $invalid_count,
							'futurecount'  => $futurecount,
							'message'      => $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'merchant', $store_name ),
							'shortcode'    => $myvideoroom_app_app->output_shortcode(),
						);

						return $outdatas;
					} else {
						$myvideoroom_app_app = MyVideoRoomApp::create_instance(
							$this->get_instance( SiteDefaults::class )->room_map( 'bookings', $booking ),
							$layout_id,
						);

						$outdatas = array(
							'validcount'   => $order_window_count,
							'invalidcount' => $invalid_count,
							'futurecount'  => $futurecount,
							'message'      => $this->get_instance( WCHelpers::class )->get_booking_header( $booking_id, 'customer', $store_name ),
							'shortcode'    => $myvideoroom_app_app->output_shortcode(),
						);

						return $outdatas;
					}
				} else {
					$outdatas = array(
						'validcount'   => $order_window_count,
						'futurecount'  => $futurecount,
						'invalidcount' => $invalid_count,
						'pastcount'    => count( $outpastarray ),
						'past'         => $outpastarray,
						'future'       => $outfuturearray,
						'shortcode'    => $outbookarray,
					);
				}

				return $outdatas;
				break;    // end Multibooking
		}//end case Multibook
	}

	/**
	 * Constructs Xprofile Field Settings from Fields
	 *
	 * @param $xprofile_field
	 * @param $booking_id
	 * @param $vendor_id
	 *
	 * @return string Returns the MyVideoRoom template to use
	 */
	public function xprofile_build( $xprofile_field, $booking_id, $vendor_id ) {
		$default_return = 'boardroom';

		global $WCFM;

		if ( ! $booking_id && ! $vendor_id ) {
			// trapping blank entry.
			return 'Function Needs either a Booking Number OR a Vendor ID<br>';
		}

		if ( ! $xprofile_field ) {
			return 'Error- X-Profile Field is required<br>';
		}
		if ( $vendor_id ) {
			$return_field = \xprofile_get_field_data( $xprofile_field, $vendor_id );
			if ( ! $return_field ) {
				// setting default of boardroom across the site.
				return $default_return;
			} else {
				return $return_field;
			}
		}
		if ( ! $vendor_id ) {
			$info_post    = \get_wc_booking( $booking_id );
			$info_post_id = $info_post->get_product_id();
			$vendor_id    = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $info_post_id );
			$return_field = \xprofile_get_field_data( $xprofile_field, $vendor_id );
			if ( ! $return_field ) {
				return $default_return;
			} else {
				return $return_field;
			}
		}
	}


	/**
	 * Constructs Invites for meetings
	 * Arguments - Invite - the invite number    # Function is called on to support Shortcode meeting functions
	 */
	public function invite( $invite, $direction, $input ) {

		if ( $input && ! $invite && 'out' === $direction ) {
			return MeetingIdGenerator::get_meeting_hash_from_user_id( $input );
		}

		if ( ! $invite && ! $input && 'user' !== $direction ) {
			return null;
		}

		if ( isset( $input ) ) {

			$user = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_identifier_string( $input );

			if ( ! $user ) {
				return 'Invalid Username or Email entered<br>';
			}

			$user_id = $user->ID;

		}

		switch ( $direction ) {
			case 'in':
				$user_id = MeetingIdGenerator::get_user_id_from_meeting_hash( $invite );
				break;
			case 'user':
				if ( $invite ) {
					$user_id = MeetingIdGenerator::get_meeting_hash_from_user_id( $invite );
				}

				break;
			case 'out':
				$user_id = MeetingIdGenerator::get_meeting_hash_from_user_id( $user_id );
				break;
			default:
				// @TODO @Fred - what should happen here? Throw an error?
		}

		return $user_id;

	}

	/**
	 * A Shortcode to Return Header Displays and Meeting Invites correctly in Sequences for Menus
	 * This is meant to be the new universal formatting invite list
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function invite_menu_shortcode( $params = array() ) {
		$type   = $params['type'] ?? 'host';
		$host   = $params['host'] ?? htmlspecialchars( $_GET['host'] ?? '' );
		$invite = $params['invite'] ?? htmlspecialchars( $_GET['invite'] ?? '' );

		if ( $type === 'host' ) {
			$user           = wp_get_current_user();
			$user_id        = $user->ID;
			$out_meeting_id = $this->invite( $user_id, 'user', null );

			return get_site_url() . '/meet/?invite=' . $out_meeting_id;
		}
		// @TODO Fred- fix buddypress dependency and use our own get user ID function

		if ( in_array( $type, array( 'guestname', 'guestlink' ), true ) ) {
			// note this scenario requires the input in the header GET of an invite number.
			$input   = $host;
			$user_id = $this->invite( $invite, 'in', null );

			$user    = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_identifier_string( $input );
			$user_id = $user->ID;

			$invite = $this->invite( $user_id, 'user', null );

			$user_detail = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $user_id );

			if ( 'guestname' === $type ) {
				return $user_detail->display_name;
			} elseif ( 'guestlink' === $type && $invite ) {
				return get_site_url() . '/meet/?invite=' . $invite;
			}
		}

	}

	/**
	 * A Shortcode to Return WCFM Search
	 * This is meant to be the new universal formatting invite list
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function search_wcfm_shortcode( $params = array() ) {
		$raw_host = $params['host'] ?? htmlspecialchars( $_GET['s'] ?? '' );
		$host     = preg_replace( '/[^A-Za-z0-9\-]/', ' ', $raw_host );

		return do_shortcode( '[wcfm_stores theme="compact" has_search="no" search_term ="' . $host . '" has_map = "no"]' );

	}

	/**
	 * A Shortcode to Return WCFM Search
	 * This is meant to be the new universal formatting invite list
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function search_youzer_groups_shortcode( $params = array() ) {
		$raw_host = $params['host'] ?? htmlspecialchars( $_GET['s'] ?? '' );
		$host     = preg_replace( '/[^A-Za-z0-9\-]/', ' ', $raw_host );

		return do_shortcode( '[youzer_members per_page="12" meta_key="_wcfm_vendor"  search_terms ="' . $host . '"]' );

	}

	/**
	 * Test
	 * This is meant to be the A scra[ testing] function
	 *
	 * @param array $params
	 */
	function test_shortcode( $params = array() ) {
		$user = get_user_meta( 164 );
		print_r( $user );
		// return print_r(wp_load_alloptions() );
	}
}
