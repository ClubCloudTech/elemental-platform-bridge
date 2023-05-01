<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Module\Membership\Library\MembershipAjax.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\UserHelpers;
use ElementalPlugin\Module\Membership\DAO\MembershipDAO;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Membership;

/**
 * Class MembershipAjax - Provides the Membership Ajax Control.
 */
class MembershipAjax {

	/**
	 * Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 * @return mixed
	 */
	public function membership_ajax_handler() {
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_membership', 'security', false );

		$action_taken     = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$membership_level = Factory::get_instance( Ajax::class )->get_string_parameter( 'level' );
		$set_value        = Factory::get_instance( Ajax::class )->get_string_parameter( 'value' );
		$email            = Factory::get_instance( Ajax::class )->get_string_parameter( 'email' );
		$first_name       = Factory::get_instance( Ajax::class )->get_string_parameter( 'first_name' );
		$last_name        = Factory::get_instance( Ajax::class )->get_string_parameter( 'last_name' );
		$user_id          = Factory::get_instance( Ajax::class )->get_string_parameter( 'userid' );
		$nonce            = Factory::get_instance( Ajax::class )->get_string_parameter( 'nonce' );

		/*
		* Update Membership Limit section.
		*
		*/
		if ( 'update_db' === $action_taken ) {

			$update = Factory::get_instance( MembershipDAO::class )->update_membership_limit( \intval( $set_value ), \intval( $membership_level ) );

			if ( $update ) {
				$response['feedback'] = \esc_html__( 'Limit Updated', 'elementalplugin' );
			} else {
				$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $membership_level . '->' . $set_value;
			}
			return \wp_send_json( $response );
		}

		/*
		* Update Store Template Function
		*
		*/
		if ( 'update_template' === $action_taken ) {

			$update = Factory::get_instance( MembershipDAO::class )->update_template( \intval( $set_value ), \intval( $membership_level ) );

			if ( $update ) {
				$response['feedback'] = \esc_html__( 'Template Updated', 'elementalplugin' );
			} else {
				$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $membership_level . '->' . $set_value;
			}
			return \wp_send_json( $response );
		}

		/*
		* Update Landing Template Section.
		*
		*/
		if ( 'update_landing_template' === $action_taken ) {

			$update = Factory::get_instance( MembershipDAO::class )->update_landing_template( \intval( $set_value ), \intval( $membership_level ) );

			if ( $update ) {
				$response['feedback'] = \esc_html__( 'Landing Template Updated', 'elementalplugin' );
			} else {
				$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $membership_level . '->' . $set_value;
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
			$response['available'] = Factory::get_instance( UserHelpers::class )->verify_user_by_email_ajax( $email );
			return \wp_send_json( $response );
		}

		/*
		* Create User.
		*
		*/
		if ( 'create_user' === $action_taken ) {

			$success_state        = Factory::get_instance( MembershipUser::class )->create_sponsored_account_user( $first_name, $last_name, $email );
			$response['feedback'] = $success_state['feedback'];
			if ( true === $success_state['status'] ) {
				$response['status']  = true;
				$response['table']   = Factory::get_instance( MembershipShortCode::class )->generate_sponsored_account_table();
				$response['counter'] = Factory::get_instance( MembershipShortCode::class )->render_remaining_account_count();
			} else {
				$response['status'] = false;
			}

			return \wp_send_json( $response );
		}

		/*
		* Delete User.
		*
		*/
		if ( 'delete_user' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, Membership::MEMBERSHIP_NONCE_PREFIX_DU . strval( $user_id ) );
			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			$my_user_id  = \get_current_user_id();
			$user_parent = Factory::get_instance( MemberSyncDAO::class )->get_parent_by_child( $user_id );
			if ( $user_parent !== $my_user_id ) {
				$response['feedback'] = \esc_html__( 'You are not the parent of this account, you can not delete it.', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			$message                  = \esc_html__( 'delete this user ? This operation can not be undone', 'elementalplugin' );
			$approved_nonce           = wp_create_nonce( $user_id . 'approved' );
			$button_approved          = Factory::get_instance( MembershipShortCode::class )->basket_nav_bar_button( Membership::MEMBERSHIP_NONCE_PREFIX_DU, esc_html__( 'Delete User', 'elemental' ), null, $approved_nonce, $user_id );
			$response['confirmation'] = Factory::get_instance( MembershipShortCode::class )->membership_confirmation( $message, $button_approved );

			return \wp_send_json( $response );
		}

		if ( 'delete_final' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, $user_id . 'approved' );
			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			$delete_user = Factory::get_instance( MembershipUser::class )->delete_wordpress_user( $user_id );
			$delete_db   = Factory::get_instance( MemberSyncDAO::class )->delete_child_account( $user_id );

			if ( true === $delete_user ) {
				$response['feedback'] = \esc_html__( 'User Deleted Successfully', 'elementalplugin' );
				$response['table']    = Factory::get_instance( MembershipShortCode::class )->generate_sponsored_account_table();
				$response['counter']  = Factory::get_instance( MembershipShortCode::class )->render_remaining_account_count();
			} else {
				$response['feedback'] = \esc_html__( 'Error Deleting User', 'elementalplugin' );
			}

			return \wp_send_json( $response );
		}

		$response = \apply_filters( 'elemental_membership_ajax_response', $response );

		return \wp_send_json( $response );
	}
}
