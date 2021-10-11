<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Membership\Library\MembershipAjax.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Membership\Membership;

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
		if ( isset( $_POST['userid'] ) ) {
			$user_id = sanitize_text_field( wp_unslash( $_POST['userid'] ) );
		}
		if ( isset( $_POST['nonce'] ) ) {
			$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
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
				$response['table']    = Factory::get_instance( MembershipShortCode::class )->generate_child_account_table();
				$response['counter']  = Factory::get_instance( MembershipShortCode::class )->render_remaining_account_count();
			} else {
				$response['feedback'] = false;
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
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'myvideoroom' );
				return \wp_send_json( $response );
			}
			$my_user_id  = \get_current_user_id();
			$user_parent = Factory::get_instance( MemberSyncDAO::class )->get_parent_by_child( $user_id );
			if ( $user_parent !== $my_user_id ) {
				$response['feedback'] = \esc_html__( 'You are not the parent of this account, you can not delete it.', 'myvideoroom' );
				return \wp_send_json( $response );
			}
			$message                  = \esc_html__( 'delete this user ? This operation can not be undone', 'myvideoroom' );
			$approved_nonce           = wp_create_nonce( $user_id . 'approved' );
			$button_approved          = Factory::get_instance( MembershipShortCode::class )->basket_nav_bar_button( Membership::MEMBERSHIP_NONCE_PREFIX_DU, esc_html__( 'Delete User', 'my-video-room' ), null, $approved_nonce, $user_id );
			$response['confirmation'] = Factory::get_instance( MembershipShortCode::class )->membership_confirmation( $message, $button_approved );

			return \wp_send_json( $response );
		}

		if ( 'delete_final' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, $user_id . 'approved' );
			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'myvideoroom' );
				return \wp_send_json( $response );
			}
			$delete_user = Factory::get_instance( MembershipUser::class )->delete_wordpress_user( $user_id );
			$delete_db   = Factory::get_instance( MemberSyncDAO::class )->delete_child_account( $user_id );

			if ( true === $delete_user ) {
				$response['feedback'] = \esc_html__( 'User Deleted Successfully', 'myvideoroom' );
				$response['table']    = Factory::get_instance( MembershipShortCode::class )->generate_child_account_table();
				$response['counter']  = Factory::get_instance( MembershipShortCode::class )->render_remaining_account_count();
			} else {
				$response['feedback'] = \esc_html__( 'Error Deleting User', 'myvideoroom' );
			}

			return \wp_send_json( $response );
		}
		die();
	}
}
