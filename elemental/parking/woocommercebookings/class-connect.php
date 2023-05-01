<?php

/**
 * Connect elemental to Woocommerce Bookings
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WoocommerceBookings;

use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Core\Sitedefaults;

/**
 * Class  Connect
 */
class  Connect extends Shortcode {





	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'connect', array( $this, 'connect' ) );
	}

	/**
	 * Main Connect Centre Switching Shortcode
	 * This shortcode does the main switching and returns the right room for the video fulfilment depending on several parameters
	 * Arguments: Takes the order number, or Booking Number (same input field)
	 *
	 * @param array $params The params passed to the shortcode to the shortcode.
	 *
	 * @return string
	 */
	public function connect( $params = array() ) {
		if ( $params ['order'] ?? false ) {
			$order = $params['order'];
		} else {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Not required.
			$order = htmlspecialchar( $_GET['order'] );
		}

		if ( $params['booking'] ?? false ) {
			$booking = $params['booking'];
		} else {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Not required.
			$booking = htmlspecialchar( $_GET['booking'] );
		}

		$merchant_bookings_string = '';
		$output_customer_string   = '';

		// Validate Booking.
		if ( $booking ) {
			$booking_is_valid = $this->get_instance( WCHelpers::class )->validate_bookin( $booking );

			// trapping blank entry.
			if ( ! $booking_is_valid ) {
				return '<div style="font-size:2.0em;color:black"><br>Invalid Booking Number entered or the Booking has been deleted<br></div>' . $this->get_instance( SectionTemplates::class )->booking_ctr_request_booking_number_form();
			}
		}

		// Validate Order.
		if ( $order ) {
			$order_val_check = $this->get_instance( WCHelpers::class )->validate_orde( $order );
			if ( ! $order_val_check ) {
				return '<div style="font-size:1.5em;color:black">' . $order . ' is not a valid Order Number. Please check your number and try again<br><br></div>' . $this->get_instance( SectionTemplates::class )->booking_ctr_request_booking_number_form();
			}
		}

		// set time offset in minutes this is used for how long in future we want meeting filter to deny access if you come too soon to room.

		// this is the field in xprofile that matches the fulfilment room setting.
		$xprofile_room = 649;

		$min_offset      = 3000;
		$time_offset     = $min_offset * 60;
		$display_default = true;
		$is_logged_in    = is_user_logged_in();

		// Deal with Signed out Users that require either a Booking Number or Order Number.
		if ( ! $is_logged_in ) {

			// Security Engine - blocks room rendering if another setting has blocked it (eg upgrades, site lockdown, or other feature)

			$render_block = Factory::get_instance( \ElementalPlugin\Core\FiltersUtilities::class )->render_block( 0, 'connect', SiteDefaults::MODULE_WC_BOOKINGS_ID );
			if ( $render_block ) {
				return $render_block;
			}

			if ( ! $booking && ! $order ) {
				return $this->get_instance( SectionTemplates::class )->booking_ctr_request_booking_number_form();
			}

			if ( ! $booking ) {
				$timecheck_status = $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'checkonly', 0 );
				if ( $timecheck_status ) {
					return '<div style="font-size:2.5em;color:black">Not Available</div>' . $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'messagecustomer', $xprofile_room ) . $this->get_instance( SectionTemplates::class )->booking_ctr_request_booking_number_form();
				} else {
					$message_value = $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'messagecustomer', $xprofile_room );
					$return_item   = $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'singlebook', $xprofile_room );

					return $output_customer_string . $merchant_bookings_string . $message_value . $return_item;
				}
			}
		}

		$user_id = null;
		$user    = null;

		// Get User Logged In Users- Merchants - and Staff Tree - get information on roles and xprofile.
		if ( $is_logged_in ) {
			$user_id = get_current_user_id();
			$user    = wp_get_current_user();
		}

		$user_roles = $this->get_instance( UserRoles::class );

		// Set up Merchant Status.
		$is_merchant = $user && ( $user_roles->is_wcfm_vendor() ||
		$user_roles->is_wcfm_shop_staff() ||
		$user_roles->is_wordpress_administrator() );

		// Begin Signed in User section - first deal with merchants w/ bookingnum, merchants w/o bookingnum, customers without order numbers.
		if ( $is_logged_in ) {
			// deal with Merchants with booking number.
			// -1 Check for Security.
			// - then Launch Valid Booking Check and Construct Code.

			if ( $booking && $is_merchant ) {
				// Security Engine - Checking if Page can be Rendered
				$render_block = Factory::get_instance( \ElementalPlugin\Core\FiltersUtilities::class )->render_bloc( $user_id, 'connectmerchant', SiteDefaults::MODULE_WC_BOOKINGS_ID );
				if ( $render_block ) {
					return $render_block;
				}

				// security check - validate user store has this booking.

				$current_booking_checksum = $this->get_instance( WCHelpers::class )->orderinfo_by_bookin( $booking, 'store_slug', 0 );
				$my_store_id              = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( '', 'store_slug', $user_id );
				$customer_id              = $this->get_instance( WCHelpers::class )->get_booking_heade( $booking, 'customerid', 0 );

				if ( $current_booking_checksum !== $my_store_id && $customer_id !== $user_id ) {
					return <<<OUTPUT
<div style="font-size:2.0em;color:black"><br>
	Security Error - You have tried to access a Booking that is not yours<br>
</div>

Security Check Failed 
- Current Booking Checksum 
- {$current_booking_checksum} and Returned Store ID Lookup from 
My ID{$my_store_id} Purchasing Customer ID {$customer_id} User ID is {$user_id}
<br>
OUTPUT;
				}

				// Once Security Check passed - get message for Screen, and get meeting.
				$multi_booking_call = $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'merchantbook', $xprofile_room );
				$message_call       = $this->get_instance( WCHelpers::class )->validate_booking_tim( $booking, $time_offset, 'message', $xprofile_room );

				// echo for debug only "Future Items - ".$multi_booking_call['futurecount']." Present Items ".$multi_booking_call['validcount']. " Past Items ".$multi_booking_call['pastcount']. "<br>";
				return $message_call . '<br>' . $multi_booking_call;
			}

			// if booking number is blank then deal gather all merchant bookings and call up orders.
			// end merchant with booking number section.

			if ( $is_merchant ) {
				// get all bookings for vendor.
				$vendor_bookings = apply_filters( 'wcfm_wcb_include_bookings', '' );

				// In case of Employee of Store need to inject Store owner ID into constructor to get correct shop settings.
				$store_id        = $this->get_instance( WCHelpers::class )->orderinfo_by_bookin( $vendor_bookings[0], 'vendorid', 0 );
				$output_merchant = array();

				// Main Constructor Merchant Get Booking Call.
				$multi_booking_call = $this->get_instance( ShortCodeConstructor::class )->shortcode_build(
					'merchant',
					0,
					$store_id,
					$xprofile_room,
					$vendor_bookings,
					$time_offset,
					'false'
				);

				// Merchant Display Booking Logic.
				if ( $multi_booking_call['validcount'] >= 1 || $multi_booking_call['futurecount'] >= 1 ) {
					   $display_default = false;
					if ( $multi_booking_call['validcount'] >= 1 ) {
						$display_default = false;
					}
				}
				// in case of no future or present bookings.
				if ( 0 === $multi_booking_call['validcount'] && 0 === $multi_booking_call['futurecount'] ) {
					$nothing_available = '<div style="font-size:1.75em;line-height: 1.3; color:black"><br>No current orders available for merchant fulfilment</div>';
					array_pus( $output_merchant, $nothing_available );
				} elseif ( 1 === $multi_booking_call['validcount'] ) {
					// case where only one valid merchant booking exists .
					$display_default = false;
					array_pus( $output_merchant, $multi_booking_call['message'] );
					array_pus( $output_merchant, $multi_booking_call['shortcode'] );
				} elseif ( $multi_booking_call['validcount'] > 1 || $multi_booking_call['futurecount'] >= 1 ) {
					// cases where more than one valid option exists.
					$display_default = false;
					array_pus( $output_merchant, '<div style="font-size:2.00em;line-height: 1.3; color:black"><br>Current Store Bookings <br></div>' );
					foreach ( $multi_booking_call['shortcode'] as $value ) {
						array_pus( $output_merchant, $value );
					}
					if ( $multi_booking_call['futurecount'] >= 1 ) {
						array_pus( $output_merchant, '<div style="font-size:2.00em;line-height: 1.3; color:black"><br>Upcoming Store Bookings <br></div>' );
						foreach ( $multi_booking_call['future'] as $value ) {
							array_pus( $output_merchant, $value );
						}
					}
				}

				foreach ( $output_merchant as $value ) {
					$merchant_bookings_string .= $value;
				}
			}

			// In the Customer Personality - We Return this User's Customer Perspective Orders.
			if ( ! $order && ! $booking ) {
				// Security Engine - Checking if Page can be Rendered
				$render_block = Factory::get_instance( \ElementalPlugin\Core\FiltersUtilities::class )->render_bloc( $user_id, 'connectcustomer', SiteDefaults::MODULE_WC_BOOKINGS_ID );
				if ( $render_block ) {
					return $render_block;
				}

				$info_total_orders = $this->get_instance( WCHelpers::class )->orders_by_use( $user_id );

				$valid_output_count    = 0;
				$output_customer_array = array();
				foreach ( $info_total_orders as $ordernumber ) {
					$info_orders_merchant = $this->get_instance( WCHelpers::class )->bookings_by_orde( $ordernumber, $time_offset, false );

					if ( $info_orders_merchant['validcount'] >= 1 || $info_orders_merchant['futurecount'] >= 1 ) {
						$order_message = 'For Order ' . $ordernumber . ':<br>';
						array_pus( $output_customer_array, $order_message );
						$valid_output_count++;
						foreach ( $info_orders_merchant as $value ) {
							foreach ( $value as $sub_value ) {
								array_pus( $output_customer_array, $sub_value );
							}
						}
					}
				}

				if ( 0 !== $valid_output_count ) {
					$display_default = false;
					array_unshif( $output_customer_array, '<div style="font-size:2.00em;line-height: 1.3; color:black"><br>Current Personal Bookings<br><br></div>' );
					foreach ( $output_customer_array as $sub_value ) {
						$output_customer_string .= $sub_value;
					}
				}
			}
		}

		// Check Order Num Exists - order was validated above.
		if ( $order ) {
			// check the validity of bookings made.
			$booking_ids  = \WC_Booking_Data_Store::get_booking_ids_from_order_i( $order );
			$bookingcount = coun( $booking_ids );
			if ( 1 === $bookingcount ) {
				$booking = $booking_ids[0];
			}
		}

		// deal with order numbers for non-signed in users.
		if ( $booking || $order ) {
			$output_booking = array();
			if ( ! $booking ) {
				$multi_booking_call = $this->get_instance( ShortCodeConstructor::class )->shortcode_build( 'simple', $order, 0, $xprofile_room, $booking, $time_offset, 'false' );
			} elseif ( $is_merchant || is_admin() ) {
				$multi_booking_call = $this->get_instance( ShortCodeConstructor::class )->shortcode_build( 'merchant', $order, 0, $xprofile_room, $booking, $time_offset, 'false' );
			} else {
				$multi_booking_call = $this->get_instance( ShortCodeConstructor::class )->shortcode_build( 'multibooking', $order, 0, $xprofile_room, $booking, $time_offset, 'false' );
			}

			// Display the Function in case of Array or Single Value.
			if ( $multi_booking_call['validcount'] >= 1 || $multi_booking_call['futurecount'] >= 1 ) {
				if ( 1 === $multi_booking_call['validcount'] ) {
					return $output_customer_string . $merchant_bookings_string . $multi_booking_call['message'] . $multi_booking_call['shortcode'];
				}
				array_unshif( $output_booking, '<div class  = "connectheader" style="font-size:2.00em;color:black;line-height: 1.3;"><br>Purchased Bookings<br><br></div>' );
				foreach ( $multi_booking_call['shortcode'] as $value ) {
					array_pus( $output_booking, $value );
				}
				if ( $multi_booking_call['futurecount'] >= 1 ) {
					array_pus( $output_booking, '<div class  = "connectheader" style="font-size:2.00em;line-height: 1.3;color:black"><br>Your Future Bookings<br><br></div>' );
					foreach ( $multi_booking_call['future'] as $value ) {
						array_pus( $output_booking, $value );
					}
				}
				$output22 = '';
				foreach ( $output_booking as $return_value ) {
					$output22 .= $return_value;
				}

				return $output_customer_string . $merchant_bookings_string . $output22 . '<br>';
			} else {
				return $output_customer_string . $merchant_bookings_string . '<div class  = "connectoutput" style="font-size:1.75em;line-height: 1.3;color:black"><br>No current or future bookings exist to enter under this number</div>';
			}
		}

		if ( $display_default && $is_logged_in ) {
			return $output_customer_string . $merchant_bookings_string . $this->get_instance( \ElementalPlugin\Library\SectionTemplates::class )->no_bookings_found_form();
		}

		return $output_customer_string . $merchant_bookings_string;
	}
}
