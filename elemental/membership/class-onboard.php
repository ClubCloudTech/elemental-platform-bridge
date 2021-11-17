<?php
/**
 * Membership Package
 * Application for Child Account Management
 *
 * @package elemental/membership/class-onboard.php
 */

namespace ElementalPlugin\Membership;

use ElementalPlugin\Admin;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Membership\Library\OnboardAjax;
use ElementalPlugin\Membership\Library\OnboardShortcode;
use ElementalPlugin\Membership\Library\WooCommerceHelpers;

/**
 * Class Onboard
 */
class Onboard {

	const TABLE_NAME_MEMBERSHIPS = 'elemental_memberships';
	const TABLE_NAME_MEMBERSYNC  = 'elemental_membersync';
	const SHORTCODE_TAG          = Admin::SHORTCODE_TAG . 'onboarding';


	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		Factory::get_instance( WooCommerceHelpers::class )->init();
		add_shortcode( self::SHORTCODE_TAG, array( Factory::get_instance( OnboardShortcode::class ), 'render_onboarding_shortcode' ) );

				// Enqueue Script Ajax Handling.
				\wp_register_script(
					'elemental-onboard-js',
					\plugins_url( '/js/onboardadmin.js', \realpath( __FILE__ ) ),
					array( 'jquery' ),
					Factory::get_instance( Version::class )->get_plugin_version() . \wp_rand( 40, 30000 ),
					true
				);
				// Localize script Ajax Upload.
				$script_data_array = array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'elemental_onboard' ),
				);

				wp_localize_script(
					'elemental-onboard-js',
					'elemental_onboardadmin_ajax',
					$script_data_array
				);

				wp_register_style(
					'elemental-onboard-css',
					plugins_url( '/css/onboardadmin.css', __FILE__ ),
					false,
					Factory::get_instance( Version::class )->get_plugin_version() . \wp_rand( 40, 30000 )
				);

				\add_action( 'wp_ajax_elemental_onboardadmin_ajax', array( Factory::get_instance( OnboardAjax::class ), 'onboard_ajax_handler' ), 10, 2 );
				\add_action( 'wp_ajax_nopriv_elemental_onboardadmin_ajax', array( Factory::get_instance( OnboardAjax::class ), 'onboard_ajax_handler' ), 10, 2 );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

		Factory::get_instance( MembershipDAO::class )->install_membership_mapping_table();
		Factory::get_instance( MemberSyncDAO::class )->install_membership_sync_table();
	}
}


