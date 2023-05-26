<?php
/**
 * Helper functions for WordPress User Roles
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Library\LoginHandler;
use ElementalPlugin\Module\Membership\Library\MembershipUser;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\Menus\ElementalMenus;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Class UserRoles
 */
class UserRoles {



	/**
	 * The target user
	 *
	 * @var \WP_User|null
	 */
	private ?\WP_User $user;

	// ---

	/**
	 * UserRoles constructor.
	 *
	 * @param \WP_User|null $user - the user object.
	 */
	public function __construct( \WP_User $user = null ) {
		if ( $user ) {
			$this->user = $user;
		} else {
			$this->user = \wp_get_current_user();
		}
	}

	// ---

	/**
	 * Is the current WordPress user an administrator?
	 *
	 * @return bool
	 */
	public function is_wordpress_administrator(): bool {
		return $this->user_has_role( 'administrator' );
	}

	/**
	 * Is the current WordPress user a Tenant Account or WCFM Vendor?
	 *
	 * @param int $user_id - the user_id.
	 * @return bool
	 */
	public function is_tenant_account( int $user_id = null ): bool {

		if ( $user_id ) {
			$user       = \get_user_by( 'id', $user_id );
			$this->user = $user;
		} else {
			$user = \wp_get_current_user();
		}
		$wcfm_vendor = $this->is_wcfm_vendor();
		$tenant_user = $this->user_has_role( Membership::MEMBERSHIP_ROLE_TENANT );

		if ( $wcfm_vendor || $tenant_user ) {
			return true;
		}
		return false;

	}
		/**
	 * Is the current WordPress user a Tenant Admin Account or WCFM Staff?
	 *
	 * @param int $user_id - the user_id.
	 * @return bool
	 */
	public function is_tenant_admin_account( int $user_id = null ): bool {

		if ( $user_id ) {
			$user       = \get_user_by( 'id', $user_id );
			$this->user = $user;
		} else {
			$user = \wp_get_current_user();
		}
		$wcfm_staff  = $this->is_wcfm_shop_staff();
		$tenant_user = $this->user_has_role( Membership::MEMBERSHIP_ROLE_TENANT_ADMIN );

		if ( $wcfm_staff || $tenant_user ) {
			return true;
		}
		return false;
	}


	/**
	 * Is the current WordPress user a WCFM Vendor?
	 *
	 * @param int $user_id - the user_id.
	 * @return bool
	 */
	public function is_wcfm_vendor( int $user_id = null ): bool {

		if ( $user_id ) {
			$user       = \get_user_by( 'id', $user_id );
			$this->user = $user;
		} else {
			$user = \wp_get_current_user();
		}
		return $this->user_has_role( 'wcfm_vendor' );

	}

	/**
	 * Is the current WordPress user a WCFM Shop Staff Member?
	 *
	 * @param int $user_id - the user_id.
	 * @return bool
	 */
	public function is_wcfm_shop_staff( int $user_id = null ): bool {
		if ( $user_id ) {
			$user       = \get_user_by( 'id', $user_id );
			$this->user = $user;
		} else {
			$user = \wp_get_current_user();
		}
		return $this->user_has_role( 'shop_staff' );
	}

	/**
	 * Is the current WordPress user a WCFM Store Manager?
	 *
	 * @return bool
	 */
	public function is_wcfm_store_manager(): bool {
		return $this->user_has_role( 'store_manager' );
	}

	/**
	 * Does the user have a certain role
	 *
	 * @param string $role The role to check.
	 *
	 * @return bool
	 */
	private function user_has_role( string $role ): bool {
		return ( $this->user && in_array( $role, $this->user->roles, true ) );
	}

	/**
	 * Get current user WordPress Roles
	 *
	 * @param int $user_id - the user_id.
	 * @return array of roles
	 */
	public function get_user_roles( int $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user_meta  = get_userdata( $user_id );
		$user_roles = $user_meta->roles;

		return $user_roles;

	}

	/**
	 * Get current user WordPress Roles
	 *
	 * @return array of roles
	 */
	public function is_sponsored_account() {
		return $this->user_has_role( 'Sponsored' );
	}

	/**
	 * Get the Name of the Tenant
	 *
	 * @param int $user_id - the user_id.
	 * @return string
	 */
	public function get_tenant_name( int $user_id = null ): ?string {
		$site_value = \get_option( ElementalMenus::SETTING_SUBSCRIPTION_MODE );
		if ( $user_id ) {
			$user       = \get_user_by( 'id', $user_id );
			$this->user = $user;
		} else {
			$user = \wp_get_current_user();
		}

		if ( 'true' === $site_value ) {
			return $user->display_name;
		} else {
			return get_user_meta( $user->ID, 'store_name', true );
		}
	}
	/**
	 * Get the Parent ID of the Tenant
	 *
	 * @param int $user_id - the user_id.
	 * @return string
	 */
	public function get_tenant_parent_id( int $user_id = null ) {
		$site_value = \get_option( ElementalMenus::SETTING_SUBSCRIPTION_MODE );
		// Send to WCFM handler if mode is WCFM.
		if ( 'true' !== $site_value ) {
			return Factory::get_instance( WCFMTools::class )->staff_to_parent( $user_id );
		}
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}
		//Get Parent ID.
		$meta_key = MembershipUser::TENANT_PARENT_META_KEY;

		$parent_id = get_user_meta(
			$user_id,
			$meta_key
		);
		if ( $parent_id[0] ) {
			return intval( $parent_id[0] );
		} else {
			return null;
		}
	}
	/**
	 * Get ID's of Tenant Admin/WCFM Staff Accounts.
	 * Finds Child Account IDs.
	 *
	 * @return ?array
	 */
	public function get_child_ids_staff_tenant_admins( int $parent_user_id = null ): ?array {
		if ( $parent_user_id ) {
			$user = \get_user_by( 'id', $parent_user_id );
		} else {
			$user           = \wp_get_current_user();
			$parent_user_id = $user->ID;
		}
		$site_value = \get_option( ElementalMenus::SETTING_SUBSCRIPTION_MODE );
		// Send to WCFM handler if mode is WCFM.
		if ( 'true' !== $site_value ) {
			return Factory::get_instance( WCFMTools::class )->elemental_get_staff_member_count( $parent_user_id );
		} else {
			$return_array   = array();
			$child_accounts = Factory::get_instance( MemberSyncDAO::class )->get_all_child_accounts( $parent_user_id, Membership::MEMBERSHIP_ROLE_TENANT_ADMIN );
			foreach ( $child_accounts as $child_account ) {
				array_push( $return_array, $child_account['user_id'] );
			}
			return $return_array;
		}
	}
	/**
	 * Login to Child Account.
	 * Finds Child Account ID - and signs in to Child Account.
	 *
	 * @param int $user_id - the id to login into.
	 * @return void
	 */
	public function login_to_child_account( int $user_id = null ): void {
		if ( ! $this->is_tenant_account() ) {
			return;
		}
		// Get Parent Account ID.
		if ( ! $user_id ) {
			$user_id = Factory::get_instance( LoginHandler::class )->decode_user_child_cookie();
		}

		$parent_id = $this->get_tenant_parent_id( $user_id );
		$my_id     = \get_current_user_id();

		// Calculate this is my child (if not exit for security).
		if ( $parent_id !== $my_id ) {
			return;
		}

		wp_logout();
		$user = wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user->user_email, $user );
	}
	/**
	 * Login to Parent Account.
	 * Finds Parent Account ID - and signs in as Parent Account.
	 *
	 * @return void
	 */
	public function login_to_parent_account(): void {

		if ( ! is_user_logged_in() || ! $this->is_tenant_admin_account() ) {
			return;
		}

		// Get Parent Account ID.
		$child_id  = \get_current_user_id();
		$parent_id = $this->get_tenant_parent_id( $child_id );
		if ( ! $parent_id ) {
			esc_html_e( 'No Parent Active Subscription Found, or parent account deleted', 'elementalplugin' );
			die();
		}
		wp_logout();
		wp_set_current_user( $parent_id );
		wp_set_auth_cookie( $parent_id );
		$new_user = \wp_get_current_user();
		do_action( 'wp_login', $new_user->user_email, $new_user );
	}
}
