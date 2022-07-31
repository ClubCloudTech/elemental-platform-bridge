<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Module\Membership\Library\MembershipAjax.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\HttpPost;
use ElementalPlugin\Module\Membership\DAO\MembershipDAO;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;

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

		if ( isset( $_POST['action_taken'] ) ) {
			$action_taken = sanitize_text_field( wp_unslash( $_POST['action_taken'] ) );
		}
		if ( isset( $_POST['level'] ) ) {
			$membership_level = sanitize_text_field( wp_unslash( $_POST['level'] ) );
		}
		if ( isset( $_POST['value'] ) ) {
			$set_value =  wp_unslash( $_POST['value'] );
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

			$success_state        = Factory::get_instance( MembershipUser::class )->create_wordpress_user( $first_name, $last_name, $email );
			$response['feedback'] = $success_state;
			if ( 'Success' === $success_state ) {
				$response['status']  = true;
				$response['table']   = Factory::get_instance( MembershipShortCode::class )->generate_child_account_table();
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
			$button_approved          = Factory::get_instance( MembershipShortCode::class )->basket_nav_bar_button( Membership::MEMBERSHIP_NONCE_PREFIX_DU, esc_html__( 'Delete User', 'my-video-room' ), null, $approved_nonce, $user_id );
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
				$response['table']    = Factory::get_instance( MembershipShortCode::class )->generate_child_account_table();
				$response['counter']  = Factory::get_instance( MembershipShortCode::class )->render_remaining_account_count();
			} else {
				$response['feedback'] = \esc_html__( 'Error Deleting User', 'elementalplugin' );
			}

			return \wp_send_json( $response );
		}

		/*
		* Update Sandbox section.
		*
		*/
		if ( 'update_sandbox' === $action_taken ) {
			if ( isset( $_POST['field'] ) ) {
				$set_field = Factory::get_instance( Encryption::class )->decrypt_string( $_POST['field'] );
			}
			if ( 'enabled' === $set_field || 'admin_enforced' === $set_field ) {
				$set_value = Factory::get_instance( HttpPost::class )->get_control_checkbox( 'checkbox' );
			}
			if ( 'owner_user_name' === $set_field || 'column_priority' === $set_field ) {
				$set_value = \intval( $set_value );
			}
			$level  = $membership_level;
			$update = Factory::get_instance( SandBoxDao::class )->update_by_field( $set_value, $set_field, $level );

			$response['feedback'] = \esc_html__( 'Success ', 'elementalplugin' );

			return \wp_send_json( $response );
		}

				/*
		* Update Sandbox section.
		*
		*/
		if ( 'tab_sort' === $action_taken ) {
			if ( isset( $_POST['user'] ) ) {
				$user_id = Factory::get_instance( Encryption::class )->decrypt_string( sanitize_text_field( wp_unslash( $_POST['user'] ) ) );
			}
			if ( isset( $_POST['levels'] ) ) {
				$data        = sanitize_text_field( wp_unslash( $_POST['levels'] ) );
				$place_order = \explode( ',', $data );

				$place_index = 1;
				foreach ( $place_order as $place ) {
					$update = Factory::get_instance( SandBoxDao::class )->update_by_field( $place_index, 'column_priority', intval( $place ) );
					$place_index++;
				}
			}

			$response['feedback'] = \esc_html__( 'Success', 'elementalplugin' ) . $user_id;

			return \wp_send_json( $response );
		}
		die();
	}
}
