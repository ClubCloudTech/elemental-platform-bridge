<?php
/**
 * Membership Package
 * Application for Child Account Management
 *
 * @package ElementalPlugin\Module\Membership
 */

namespace ElementalPlugin\Module\Membership;

use ElementalPlugin\Admin;
use ElementalPlugin\Factory;
use ElementalPlugin\Module\Membership\Onboard;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Membership\Library\LoginHandler;
use ElementalPlugin\Module\Membership\Library\MembershipAjax;
use ElementalPlugin\Module\Membership\Library\MembershipSetup;
use ElementalPlugin\Module\Membership\Library\MembershipShortCode;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;

/**
 * Class Membership
 */
class Membership {

	const TABLE_NAME_MEMBERSHIPS      = 'elemental_memberships';
	const TABLE_NAME_MEMBERSYNC       = 'elemental_membersync';
	const SHORTCODE_TAG               = Admin::SHORTCODE_TAG . 'membership';
	const MEMBERSHIP_ROLE_NAME        = 'Sponsored';
	const MEMBERSHIP_ROLE_DESCRIPTION = 'Sponsored Account';
	const MEMBERSHIP_NONCE_PREFIX_DU  = 'delete_user_';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		Factory::get_instance( LoginHandler::class )->init();
		\add_action( 'wp_ajax_elemental_membershipadmin_ajax', array( Factory::get_instance( MembershipAjax::class ), 'membership_ajax_handler' ), 10, 2 );

		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		// Enqueue Script Ajax Handling.
		\wp_register_script(
			'elemental-membership-js',
			\plugins_url( '/js/membershipadmin.js', \realpath( __FILE__ ) ),
			array( 'jquery' ),
			$plugin_version . \wp_rand( 40, 30000 ),
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

		add_shortcode( self::SHORTCODE_TAG, array( Factory::get_instance( MembershipShortCode::class ), 'render_membership_shortcode' ) );

	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {
		Factory::get_instance( MembershipSetup::class )->activate();
	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_membership_config_page(): string {
		\wp_enqueue_script( 'elemental-membership-js' );
		$membership_levels = Factory::get_instance( UMPMemberships::class )->get_ump_memberships();
		return ( include __DIR__ . '/views/membership/table-output.php' )( $membership_levels );
	}
}


