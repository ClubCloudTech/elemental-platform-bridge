<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package elemental/membership/library/class-onboardajax.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\Onboard;
use ElementalPlugin\Module\WooCommerceSubscriptions\Library\SubscriptionHelpers;
use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\UserHelpers;

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

		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$email        = Factory::get_instance( Ajax::class )->get_string_parameter( 'email' );
		$first_name   = Factory::get_instance( Ajax::class )->get_string_parameter( 'first_name' );
		$user_id      = Factory::get_instance( Ajax::class )->get_string_parameter( 'userid' );

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
			$response['available'] = Factory::get_instance( UserHelpers::class )->verify_user_by_email_ajax( $email );
			return \wp_send_json( $response );
		}

		/*
		* Create Organisation.
		*
		*/
		if ( 'create_org' === $action_taken ) {
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
				// Set Registration Process Cookie.
				Factory::get_instance( Onboard::class )->delete_setup_cookie();
				Factory::get_instance( Onboard::class )->create_setup_cookie();
				$response['feedback']   = true;
				$response['membership'] = $membership;

				$this->wcfm_choose_membership( intval( $membership ) );
				$response['table'] = Factory::get_instance( OnboardShortcode::class )->render_wcfm_step( $user_id );

			} else {
				$response['feedback'] = 'Error Creating Account';
			}
			return \wp_send_json( $response );
		}

		/*
		* Create User.
		*
		*/
		if ( 'create_tenant' === $action_taken ) {
			$membership = Factory::get_instance( Ajax::class )->get_string_parameter( 'membership' );
			$user_id    = Factory::get_instance( MembershipUser::class )->create_tenant_user( $membership );
			if ( $user_id ) {
				if ( ! is_user_logged_in() ) {
					$user_obj = \get_user_by( 'id', $user_id );
					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );
					do_action( 'wp_login', $email, $user_obj );
				}
				$response['feedback'] = true;

				// Set WooCommerce Checkout and return Checkout.
				$this->subscription_choose_membership( intval( $membership ) );
				$response['redirect'] = wc_get_checkout_url();

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
	 *
	 * @param int $membership - the membership type.
	 */
	public function wcfm_choose_membership( int $membership ) {

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
			do_action( 'wcfmvm_after_choosing_membership', $membership );

		if ( $membership ) {
			$subscription_pay_mode = 'by_wc';
			$wcfm_membership       = absint( $membership );
			if ( is_user_logged_in() ) {
				$member_id = get_current_user_id();
				update_user_meta( $member_id, 'temp_wcfm_membership', $wcfm_membership );
			}

			$subscription          = (array) get_post_meta( $wcfm_membership, 'subscription', true );
			$subscription_pay_mode = isset( $subscription['subscription_pay_mode'] ) ? $subscription['subscription_pay_mode'] : 'by_wcfm';
			$subscription_product  = isset( $subscription['subscription_product'] ) ? $subscription['subscription_product'] : '';
			if ( ( 'by_wc' === $subscription_pay_mode ) && $subscription_product ) {
				WC()->cart->empty_cart();
				WC()->cart->add_to_cart( $subscription_product );
			}
		}
	}
	/**
	 * WCFM Choose Membership Plan
	 * Sets WCFM Parameters to Ready Basket
	 *
	 * @param int $membership - the membership type.
	 */
	public function subscription_choose_membership( int $membership ) {
		Factory::get_instance( SubscriptionHelpers::class )->add_product_to_cart( $membership );

	}
}
