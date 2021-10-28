<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package elemental/membership/library/class-onboardajax.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;

/**
 * Class OnboardAjax - Provides the Onboard Ajax Control.
 */
class OnboardAjax {

	/**
	 * Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 * @return mixed
	 */
	public function onboard_ajax_handler() {
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_onboard', 'security', false );

		if ( isset( $_POST['action_taken'] ) ) {
			$action_taken = sanitize_text_field( wp_unslash( $_POST['action_taken'] ) );
		}
		if ( isset( $_POST['level'] ) ) {
			$membership_level = sanitize_text_field( wp_unslash( $_POST['level'] ) );
		}
		if ( isset( $_POST['value'] ) ) {
			$set_value = sanitize_text_field( wp_unslash( $_POST['value'] ) );
		}
		if ( isset( $_POST['email'] ) ) {
			$email = \sanitize_email( wp_unslash( $_POST['email'] ) );
		}
		if ( isset( $_POST['first_name'] ) ) {
			$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
		}
		if ( isset( $_POST['last_name'] ) ) {
			$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );
		}
		if ( isset( $_POST['userid'] ) ) {
			$user_id = sanitize_text_field( wp_unslash( $_POST['userid'] ) );
		}
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
		}

		/*
		* Check Login.
		*
		*/
		if ( 'check_login' === $action_taken ) {

			if ( \is_user_logged_in() ) {
				$response['login'] = true;
			} else {
				$response['login'] = false;
			}
			return \wp_send_json( $response );
		}

		/*
		* Check Email is Available.
		*
		*/
		if ( 'check_email' === $action_taken ) {

			$email_exists = get_user_by( 'email', $email );

			if ( $email_exists ) {
				$response['available'] = false;
			} else {
				$response['available'] = true;
			}
			return \wp_send_json( $response );
		}

		/*
		* Create User.
		*
		*/
		if ( 'create_user' === $action_taken ) {
			if ( isset( $_POST['membership'] ) ) {
				$membership = sanitize_text_field( wp_unslash( $_POST['membership'] ) );
			}
			$user_id = Factory::get_instance( MembershipUser::class )->create_organisation_wordpress_user( $first_name, $email );
			if ( $user_id ) {
				if ( ! is_user_logged_in() ) {
					$user_obj = \get_user_by( 'id', $user_id );
					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
					do_action( 'wp_login', $email, $user_obj );
				}
				$response['feedback']   = true;
				$response['membership'] = $membership . 'dbma';

				$this->wcfm_choose_membership( intval( $membership ) );
				$response['table'] = Factory::get_instance( OnboardShortcode::class )->render_wcfm_step( $user_id );

			} else {
				$response['feedback'] = false;
			}
			return \wp_send_json( $response );
		}

		/*
		* Get Checkout.
		*
		*/
		if ( 'get_checkout' === $action_taken ) {
			$response['status'] = 'Basket';
			$response['table']  = \do_shortcode( '[woocommerce_checkout]' );
			return \wp_send_json( $response );
		}

		die();
	}

	/**
	 * WCFM Choose Membership Plan
	 * Sets WCFM Parameters to Ready Basket
	 */
	public function wcfm_choose_membership( int $membership ) {
		global $WCFM, $WCFMvm, $_SESSION;
		// error_log( 'inside main function' );
			// Session store
		if ( WC()->session ) {
			do_action( 'woocommerce_set_cart_cookies', true );
			WC()->session->set( 'wcfm_membership', $membership );

			if ( is_user_logged_in() && wcfm_has_membership() ) {
				WC()->session->set( 'wcfm_membership_mode', 'upgrade' );
			} else {
				WC()->session->set( 'wcfm_membership_mode', 'new' );
			}

			if ( WC()->session->get( 'wcfm_membership_free_registration' ) ) {
				WC()->session->__unset( 'wcfm_membership_free_registration' );
			}
		}

			$method = 'by_url';
			do_action( 'wcfmvm_after_choosing_membership', $membership );

		if ( $membership ) {
			$wcfm_membership_registration_messages = get_wcfmvm_membership_registration_messages();
			$has_error                             = false;
			$subscription_pay_mode                 = 'by_wc';
			$wcfm_membership                       = absint( $membership );
			if ( is_user_logged_in() ) {
				$member_id = get_current_user_id();
				update_user_meta( $member_id, 'temp_wcfm_membership', $wcfm_membership );
			}

			$subscription          = (array) get_post_meta( $wcfm_membership, 'subscription', true );
			$subscription_pay_mode = isset( $subscription['subscription_pay_mode'] ) ? $subscription['subscription_pay_mode'] : 'by_wcfm';
			$subscription_product  = isset( $subscription['subscription_product'] ) ? $subscription['subscription_product'] : '';
			if ( ( $subscription_pay_mode === 'by_wc' ) && $subscription_product ) {
				WC()->cart->empty_cart();
				WC()->cart->add_to_cart( $subscription_product );
			}
		}

				/*
				if ( $subscription_pay_mode == 'by_wc' ) {
					if ( $method == 'by_url' ) {
						wp_safe_redirect( wc_get_checkout_url() );
					} else {
						echo '{"status": true, "message": "' . $wcfm_membership_registration_messages['registration_success'] . '", "redirect": "' . wc_get_checkout_url() . '"}';
					}
				} else {
					if ( $method == 'by_url' ) {
						wp_safe_redirect( add_query_arg( 'vmstep', 'registration', get_wcfm_membership_url() ) );
					} else {
						echo '{"status": true, "message": "' . $wcfm_membership_registration_messages['registration_success'] . '", "redirect": "' . add_query_arg( 'vmstep', 'payment', get_wcfm_membership_url() ) . '"}';
					}
				} */
			// }

		/*
		if ( $method == 'by_url' ) {
			wp_safe_redirect( add_query_arg( 'vmstep', 'registration', get_wcfm_membership_url() ) );
		} else {
			echo '{"status": true, "redirect": "' . add_query_arg( 'vmstep', 'registration', get_wcfm_membership_url() ) . '"}';
		}*/
	}

}
