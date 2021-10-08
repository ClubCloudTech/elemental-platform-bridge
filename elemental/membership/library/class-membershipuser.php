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
	 * @param string $last_name - User Last Name.
	 * @param string $email - User Email.
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

		// Notify User of Password.
		$this->notify_new_child_user();

		return true;
	}

	public function notify_new_child_user() {
		// wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password );
	}

}
