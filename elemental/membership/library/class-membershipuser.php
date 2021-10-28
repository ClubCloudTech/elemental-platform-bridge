<?php
/**
 * Membership User Account Management Functions
 *
 * @package ElementalPlugin\Membership\Library\class-membershipUser.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Membership\Membership;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class MembershipUser {


	/**
	 * Create WordPress user from Membership form Ajax call.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 *
	 * @return bool
	 */
	public function create_wordpress_user( string $first_name, string $last_name, string $email ): bool {
		$quota_available = Factory::get_instance( MembershipUMP::class )->child_account_available_number();
		if ( strlen( $first_name ) < 3 || strlen( $last_name ) < 3 || ! \sanitize_email( $email ) || \username_exists( $email ) || 0 === $quota_available ) {
			return false;
		}

		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			return false;
		}
		// Notify User of Password.
		$notify_user_status = $this->notify_new_child_user( $password, $email, $first_name );
		// Update Additional User Parameters.
		wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $first_name,
				'display_name' => $first_name . ' ' . $last_name,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'role'         => Membership::MEMBERSHIP_ROLE_NAME,
			)
		);
		// Update Parent/Sponsor Database.
		$parent_id = \get_current_user_id();
		Factory::get_instance( MemberSyncDAO::class )->register_child_account( $user_id, $parent_id );

		return true;
	}

	/**
	 * Create WordPress user from Organisation Add form Ajax call.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $email      - User Email.
	 *
	 * @return ?int
	 */
	public function create_organisation_wordpress_user( string $first_name, string $email ): ?int {
		if ( strlen( $first_name ) < 5 || ! \sanitize_email( $email ) || \username_exists( $email ) ) {
			return false;
		}

		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			return false;
		}
		// Notify User of Password.
		$notify_user_status = $this->notify_new_child_user( $password, $email, $first_name );
		// Update Additional User Parameters.
		$user_id = wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $first_name,
				'display_name' => $first_name,
				'first_name'   => $first_name,
			)
		);
		return $user_id;
	}
	/**
	 * Send WordPress Notification Mail to New User.
	 *
	 * @param string $password      - the generated password.
	 * @param string $email_address - the User Email Address.
	 * @param string $first_name    - the User First Name.
	 *
	 * @return bool
	 */
	public function notify_new_child_user( string $password, string $email_address, string $first_name ) {

		$template = include __DIR__ . '/../views/email-template.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			\esc_html__( ' Welcome to ', 'myvideoroom' ) . get_bloginfo( 'name' ),
			$template( $password, $email_address, $first_name ),
			$headers
		);
		return $status;
	}

	/**
	 * Get the list of User Sponsored Accounts
	 *
	 * @param  int $parent_id - The user ID of the parent - uses currently logged in user if blank.
	 * @return array
	 */
	public function get_sponsored_users( int $parent_id = null ) :array {
		if ( ! $parent_id ) {
			$parent_id = \get_current_user_id();
		}

		$sponsored_objects = Factory::get_instance( MemberSyncDAO::class )->get_all_child_accounts( $parent_id );

		$return_array = array();

		foreach ( $sponsored_objects as $account ) {
			$user = \get_user_by( 'ID', $account['user_id'] );

			$record_array                 = array();
			$record_array['user_id']      = $account['user_id'];
			$record_array['created']      = date_i18n( get_option( 'date_format' ), $account['timestamp'] );
			$record_array['parent_id']    = $account['parent_id'];
			$record_array['display_name'] = $user->display_name;
			$record_array['email']        = $user->user_email;

			\array_push( $return_array, $record_array );
		}
		return $return_array;
	}
	/**
	 * Delete WordPress user from Membership form Ajax call.
	 *
	 * @param int $user_id - The User_ID to be deleted.
	 *
	 * @return bool
	 */
	public function delete_wordpress_user( int $user_id ): bool {

		$user_info          = get_userdata( $user_id );
		$current_user_roles = $user_info->roles;
		include_once ABSPATH . 'wp-admin/includes/user.php';

		if ( in_array( 'administrator', $current_user_roles ) ) {
			return false;
		} else {
			if ( wp_delete_user( $user_id ) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}
