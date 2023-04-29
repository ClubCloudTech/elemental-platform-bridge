<?php
/**
 * Membership User Account Management Functions
 *
 * @package ElementalPlugin\Module\Membership\Library\class-membershipUser.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Module\UltimateMembershipPro\ElementalUMP;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class MembershipUser {

	const USER_META_KEY_PENDING   = 'elemental_user_pending';
	const USER_META_VALUE_PENDING = 'elemental_onboard_pending';

	const USERSUBS_META_KEY_PENDING                = 'elemental_usersubs_pending';
	const USERSUBS_META_VALUE_PENDING              = 'elemental_onboardsubs_pending';
	const USERSUBS_META_VALUE_ONBOARD_DATA_PENDING = 'elemental_onboardsubs_data_pending';
	const USERSUBS_META_KEY_MEMBERSHIP_PRODUCT     = 'elemental_membership_product';

	/**
	 * Create WordPress user from Membership form Ajax call.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 *
	 * @return array
	 */
	public function create_sponsored_account_user( string $first_name, string $last_name, string $email ): array {
		$quota_available = Factory::get_instance( MembershipUMP::class )->child_account_available_number();
		$return_array    = array();

		if ( 0 === $quota_available ) {
			$return_array['status']   = false;
			$return_array['feedback'] = \esc_html__( 'Insufficient Quota for Assignment', 'elementalplugin' );
			return $return_array;
		}

		if ( strlen( $first_name ) < 3 || strlen( $last_name ) < 3 || ! \sanitize_email( $email ) || \username_exists( $email ) ) {
			$return_array['status']   = false;
			$return_array['feedback'] = \esc_html__( 'Incorrect Validation, First Name, Last Name, or Email', 'elementalplugin' );
			return $return_array;
		}

		$password = wp_generate_password( 12, false );

		$user_id = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			$return_array['feedback'] = \esc_html__( 'WordPress User Account Creation Error', 'elementalplugin' );
			$return_array['status']   = false;
			return $return_array;
		}
		$sync_result['data'] = array();
		// Notify User of Password.
		$this->notify_user_credential( $password, $email, $first_name, $sync_result['data'] );
		// Update Additional User Parameters.
		wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $first_name,
				'display_name' => $first_name . ' ' . $last_name,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'role'         => Membership::MEMBERSHIP_ROLE_SPONSORED,
			)
		);

		// Add Subscription- User Sponsor account class.
		$subscription_id = intval( get_option( ElementalUMP::SETTING_UMP_SPONSORED_SUBSCRIPTION_ID ) );
		Factory::get_instance( UMPMemberships::class )->add_user_ump_subscription( $user_id, $subscription_id );

		// Update Parent/Sponsor Database.
		$parent_id = \get_current_user_id();
		\do_action( 'elemental_post_sponsored_user_add', $user_id, $parent_id );
		Factory::get_instance( MemberSyncDAO::class )->register_child_account( $user_id, $parent_id );

		// Return Ajax Call.
		$return_array['feedback'] = \esc_html__( 'User Created as ', 'elementalplugin' ) . $user_id;
		$return_array['status']   = true;
		return $return_array;
	}

	/**
	 * Create Admin Account WordPress user from Membership form Ajax call.
	 *
	 *  TODO - NOT YET IMPLEMENTED FOR ADMINs - use for WCFM refactor.
	 *
	 * @param string $first_name - User First Name.
	 * @param string $last_name  - User Last Name.
	 * @param string $email      - User Email.
	 *
	 * @return array
	 */
	public function create_admin_account_user( string $first_name, string $last_name, string $email ): array {
		$quota_available = Factory::get_instance( MembershipUMP::class )->child_account_available_number();
		$return_array    = array();

		if ( 0 === $quota_available ) {
			$return_array['status']   = false;
			$return_array['feedback'] = \esc_html__( 'Insufficient Quota for Assignment', 'elementalplugin' );
			return $return_array;
		}

		if ( strlen( $first_name ) < 3 || strlen( $last_name ) < 3 || ! \sanitize_email( $email ) || \username_exists( $email ) ) {
			$return_array['status']   = false;
			$return_array['feedback'] = \esc_html__( 'Incorrect Validation, First Name, Last Name, or Email', 'elementalplugin' );
			return $return_array;
		}

		// Check with the Sync Engine that this does not exist in a node already.
		$check_result = \apply_filters( 'elemental_pre_user_add', $email );

		if ( $check_result['status'] ) {
			$return_array['feedback'] = \esc_html__( 'Employee with ' . $email . 'already exists.', 'elementalplugin' );
			$return_array['status']   = false;
			return $return_array;
		}

		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			$return_array['feedback'] = \esc_html__( 'WordPress User Account Creation Error', 'elementalplugin' );
			$return_array['status']   = false;
			return $return_array;
		}

		$sync_result = \apply_filters( 'elemental_post_user_add', $user_id, $first_name, $last_name, $email, $password );

		if ( ! $sync_result['status'] ) {
			$return_array['feedback'] = \esc_html__( '"Employee synchronization error', 'elementalplugin' );
			$return_array['status']   = false;
			return $return_array;
		}

		// Notify User of Password.
		$this->notify_user_credential( $password, $email, $first_name, $sync_result['data'] );
		// Update Additional User Parameters.
		wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $first_name,
				'display_name' => $first_name . ' ' . $last_name,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'role'         => Membership::MEMBERSHIP_ROLE_TENANT_ADMIN,
			)
		);

		// Update Parent/Sponsor Database.
		$parent_id = \get_current_user_id();

		\do_action( 'elemental_post_user_add', $user_id, $parent_id );

		Factory::get_instance( MemberSyncDAO::class )->register_child_account( $user_id, $parent_id );
		$return_array['feedback'] = \esc_html__( 'User Created as ', 'elementalplugin' ) . $user_id;
		return $return_array;
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

		// Check with the Sync Engine that this does not exist in a node already.
		$pre_check = \apply_filters( 'elemental_pre_tenant_add', $first_name, $email );

		if ( false === $pre_check ) {
			return false;
		}

		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			return false;
		}
		// Notify User of Password.
		$this->notify_user_credential( $password, $email, $first_name );
		// Update Additional User Parameters.
		wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $first_name,
				'display_name' => $first_name,
				'first_name'   => $first_name,
			)
		);
		$meta_key   = self::USER_META_KEY_PENDING;
		$meta_value = self::USER_META_VALUE_PENDING;
		add_user_meta(
			$user_id,
			$meta_key,
			$meta_value
		);

		// Check with the Sync Engine that this does not exist in a node already.
		\apply_filters( 'elemental_post_tenant_add', $first_name, $email, $password );

		return $user_id;
	}

	/**
	 * Create WordPress user from Indivual Subscriber Add form Ajax call.
	 *
	 * @param int $membership - the membership ID if exists.
	 * @return integer|null
	 */
	public function create_tenant_user( int $membership = null ): ?int {
		$first_name = Factory::get_instance( Ajax::class )->get_string_parameter( 'first_name' );
		$last_name  = Factory::get_instance( Ajax::class )->get_string_parameter( 'last_name' );
		$email      = Factory::get_instance( Ajax::class )->get_string_parameter( 'email' );
		$company    = Factory::get_instance( Ajax::class )->get_string_parameter( 'company' );

		// Validations.
		if (
			strlen( $first_name ) < 3 ||
			strlen( $last_name ) < 3 ||
			! \sanitize_email( $email ) ||
			\username_exists( $email )
			) {
			return false;
		}

		$password = wp_generate_password( 12, false );
		$user_id  = wp_create_user( $email, $password, $email );
		if ( ! $user_id ) {
			return false;
		}
		// Notify User of Password.
		$this->notify_user_credential( $password, $email, $first_name );
		// Update Additional User Parameters.
		wp_update_user(
			array(
				'ID'           => $user_id,
				'nickname'     => $company,
				'display_name' => $company,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'role'         => Membership::MEMBERSHIP_ROLE_TENANT,
			)
		);
		$meta_key   = self::USERSUBS_META_KEY_PENDING;
		$meta_value = self::USERSUBS_META_VALUE_PENDING;
		add_user_meta(
			$user_id,
			$meta_key,
			$meta_value
		);
		if ( $membership ) {
			$member_meta_key = self::USERSUBS_META_KEY_MEMBERSHIP_PRODUCT;
			add_user_meta(
				$user_id,
				$member_meta_key,
				$membership
			);
		}

		do_action( 'update_individual_subs_registered_fields', $user_id );

		return $user_id;
	}
	/**
	 * Send WordPress Notification Mail to New User.
	 *
	 * @param string $password      - the generated password.
	 * @param string $email_address - the User Email Address.
	 * @param string $first_name    - the User First Name.
	 * @param array  $data           - generated employees data.
	 *
	 * @return bool
	 */
	public function notify_user_credential( string $password, string $email_address, string $first_name, array $data = array() ) {

		$template = include __DIR__ . '/../views/email-template.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			\esc_html__( ' Welcome to ', 'elementalplugin' ) . get_bloginfo( 'name' ),
			$template( $password, $email_address, $first_name, $data ),
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
			$record_array['account_type'] = $account['account_type'];
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

		if ( $current_user_roles && in_array( 'administrator', $current_user_roles, true ) ) {
			return false;
		} else {
			if ( wp_delete_user( $user_id ) ) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Checks if a user is an onboarding pending user.
	 *
	 * @param int $user_id - The User_ID to be verified.
	 *
	 * @return bool
	 */
	public function is_user_onboarding( int $user_id ): bool {
		$meta_key = get_user_meta( $user_id, self::USER_META_KEY_PENDING );
		if ( self::USER_META_VALUE_PENDING === $meta_key[0] ) {

			return true;

		} else {

			return false;

		}
	}

	/**
	 * Checks if a user is an onboarding pending user.
	 *
	 * @param int $user_id - The User_ID to be verified.
	 *
	 * @return bool
	 */
	public function is_user_subscription_onboarding( int $user_id ): bool {
		$meta_key = get_user_meta( $user_id, self::USERSUBS_META_KEY_PENDING );
		if ( self::USERSUBS_META_VALUE_PENDING === $meta_key[0] ) {

			return true;

		} else {

			return false;

		}
	}

	/**
	 * Get Parent Account Meta
	 *
	 * @param int    $user_id - The User_ID to be verified.
	 * @param string $meta_key - The key of the Information you want returned.
	 *
	 * @return ?string
	 */
	public function get_store_meta_info( int $user_id, string $meta_key ): ?string {

		if ( $this->is_sponsored_account( $user_id ) ) {
			$parent_info = Factory::get_instance( MemberSyncDAO::class )->get_parent_by_child( $user_id );

		} elseif ( Factory::get_instance( WCFMTools::class )->am_i_staff( $user_id ) ) {
			$parent_info = Factory::get_instance( WCFMHelpers::class )->staff_to_parent( $user_id );

		} elseif ( Factory::get_instance( WCFMTools::class )->am_i_storeowner( $user_id ) ) {
			$parent_info = $user_id;
		}
		if ( ! $parent_info ) {
			return null;
		} else {
			$meta_value = get_user_meta( $parent_info, $meta_key );
			return $meta_value;
		}
	}

	/**
	 * Is User a Sponsored Account ?
	 *
	 * @param int $user_id - The User_ID to be verified.
	 *
	 * @return bool
	 */
	public function is_sponsored_account( int $user_id ): bool {
		$user_info = get_userdata( $user_id );
		if ( ! $user_info ) {
			return false;
		}
		$current_user_roles = $user_info->roles;
		include_once ABSPATH . 'wp-admin/includes/user.php';

		if ( in_array( 'Sponsored', $current_user_roles, true ) ) {
			return true;
		} else {
			return false;
		}
	}
}
