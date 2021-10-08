<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Membership\Library\MembershipAjax.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;

/**
 * Class MembershipAjax - Provides the Membership Ajax Control.
 */
class MembershipAjax {


	/** Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 * @return mixed
	 */
	public function membership_ajax_handler() {
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_membership', 'security', false );

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

		/*
		* Update Display Name section.
		*
		*/
		if ( 'update_db' === $action_taken ) {

			$update = Factory::get_instance( MembershipDAO::class )->update_membership_limit( \intval( $set_value ), \intval( $membership_level ) );

			if ( $update ) {
				$response['feedback'] = \esc_html__( 'Display Name Update Updated', 'myvideoroom' );
			} else {
				$response['feedback'] = \esc_html__( 'Display Name Update Failed', 'myvideoroom' ) . $membership_level . '->' . $set_value;
			}
			return \wp_send_json( $response );
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

			$success = Factory::get_instance( MembershipUser::class )->create_wordpress_user( $first_name, $last_name, $email );

			if ( $success ) {
				$response['feedback'] = true;
			} else {
				$response['feedback'] = false;
			}
			return \wp_send_json( $response );
		}
		die();
	}

}
