<?php
/**
 * Membership Package
 * Application for Child Account Management
 *
 * @package ElementalPlugin\Module\Membership
 */

namespace ElementalPlugin\Module\Membership;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\Onboard;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Membership\Library\AccountShortcode;
use ElementalPlugin\Module\Membership\Library\LoginHandler;
use ElementalPlugin\Module\Membership\Library\MembershipAjax;
use ElementalPlugin\Module\Membership\Library\MembershipSetup;
use ElementalPlugin\Module\Membership\Library\MembershipShortCode;
use ElementalPlugin\Module\Membership\Library\MembershipUser;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;

/**
 * Class Membership
 */
class Membership {

	const TABLE_NAME_MEMBERSHIPS                   = 'elemental_memberships';
	const TABLE_NAME_MEMBERSYNC                    = 'elemental_membersync';
	const SHORTCODE_SPONSORED_BY_PARENT            = 'elemental_membership';
	const SHORTCODE_ALL_SPONSORED                  = 'elemental_all_sponsored_users';
	const ACCOUNT_TENANT_ADMIN_SHORTCODE           = 'elemental_tenant_admin';
	const ACCOUNT_ADMIN_SHORTCODE                  = 'elemental_account_admin';
	const ACCOUNT_SPONSORED_ADMIN_SHORTCODE        = 'elemental_sponsored_admin';
	const MEMBERSHIP_ROLE_SPONSORED                = 'sponsoredmembershipaccount';
	const MEMBERSHIP_ROLE_SPONSORED_DESCRIPTION    = 'Sponsored Tenant Account';
	const MEMBERSHIP_ROLE_TENANT                   = 'tenant';
	const MEMBERSHIP_ROLE_TENANT_DESCRIPTION       = 'Tenant Account';
	const MEMBERSHIP_ROLE_TENANT_ADMIN             = 'tenantadmin';
	const MEMBERSHIP_ROLE_TENANT_ADMIN_DESCRIPTION = 'Tenant Admin Account';
	const MEMBERSHIP_NONCE_PREFIX_DU               = 'elemental-delete-user-';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		Factory::get_instance( LoginHandler::class )->init();

		// Setup Ajax.
		\add_action( 'wp_ajax_elemental_membershipadmin_ajax', array( Factory::get_instance( MembershipAjax::class ), 'membership_ajax_handler' ), 10, 2 );

		// Hook Login to allow last logged in membership time.
		\add_action( 'wp_login', array( Factory::get_instance( MembershipUser::class ), 'set_last_login' ), 10, 2 );

		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		// Enqueue Script Ajax Handling.
		\wp_register_script(
			'elemental-membership-js',
			\plugins_url( '/js/membershipadmin.js', \realpath( __FILE__ ) ),
			array( 'jquery' ),
			$plugin_version,
			true
		);
		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_membership' ),

		);

		wp_localize_script(
			'elemental-membership-js',
			'elemental_membershipadmin_ajax',
			$script_data_array
		);

		Factory::get_instance( Onboard::class )->init();

		wp_register_script(
			'wcfm_membership_registration_js',
			plugins_url( '/js/thirdparty/wcfmvm-script-membership-registration.js', __FILE__ ),
			array( 'jquery' ),
			$plugin_version . \wp_rand( 1, 22000 ),
			true
		);
		$wcfm_registration_params = array(
			'your_store'        => __( 'your_store', 'wc-multivendor-membership' ),
			'is_strength_check' => apply_filters( 'wcfm_is_allow_password_strength_check', true ),
			'short'             => __( 'Too short', 'wc-frontend-manager' ),
			'weak'              => __( 'Weak', 'wc-frontend-manager' ),
			'good'              => __( 'Good', 'wc-frontend-manager' ),
			'strong'            => __( 'Strong', 'wc-frontend-manager' ),
			'password_failed'   => __( 'Password strength should be at least "Good".', 'wc-frontend-manager' ),
			'choose_select2'    => __( 'Choose ', 'wc-frontend-manager' ),
			'wcfm_ajax_nonce'   => wp_create_nonce( 'wcfm_ajax_nonce' ),
		);
		wp_localize_script(
			'wcfm_membership_registration_js',
			'wcfm_registration_params',
			$wcfm_registration_params
		);

		add_shortcode( self::SHORTCODE_ALL_SPONSORED, array( Factory::get_instance( MembershipShortCode::class ), 'all_sponsored_accounts_shortcode_worker' ) );
		add_shortcode( self::SHORTCODE_SPONSORED_BY_PARENT, array( Factory::get_instance( MembershipShortCode::class ), 'render_sponsored_account_shortcode' ) );
		add_shortcode( self::ACCOUNT_TENANT_ADMIN_SHORTCODE, array( Factory::get_instance( MembershipShortCode::class ), 'render_tenant_admin_account_shortcode' ) );
		add_shortcode( self::ACCOUNT_ADMIN_SHORTCODE, array( Factory::get_instance( AccountShortcode::class ), 'render_account_shortcode' ) );
		add_shortcode( self::ACCOUNT_SPONSORED_ADMIN_SHORTCODE, array( Factory::get_instance( MembershipShortCode::class ), 'render_sponsored_account_shortcode' ) );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {
		Factory::get_instance( MembershipSetup::class )->activate();
	}

	/**
	 * Dectivation Functions for Membership.
	 */
	public function de_activate() {
		Factory::get_instance( MembershipSetup::class )->de_activate();
	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_membership_config_page(): string {
		if ( $this->is_ump_available() ) {
			\wp_enqueue_script( 'elemental-membership-js' );
			$membership_levels = Factory::get_instance( UMPMemberships::class )->get_ump_memberships();
			return ( include __DIR__ . '/views/membership/table-output.php' )( $membership_levels );
		} else {
			return esc_html__( 'UMP is not installed', 'elementalplugin' );
		}
	}

	/**
	 * Is UMP Available - checks if BuddyPress is enabled.
	 *
	 * @return bool
	 */
	public function is_ump_available(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'indeed-membership-pro/indeed-membership-pro.php' );
	}
}


