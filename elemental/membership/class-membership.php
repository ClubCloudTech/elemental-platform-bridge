<?php
/**
 * Membership Package
 * Application for Child Account Management
 *
 * @package ElementalPlugin\Membership
 */

namespace ElementalPlugin\Membership;

use ElementalPlugin\Admin;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Membership\Library\MembershipAjax;
use ElementalPlugin\Membership\Library\MembershipShortCode;
use ElementalPlugin\Membership\Library\MembershipUMP;

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
		\add_action( 'wp_ajax_elemental_membershipadmin_ajax', array( Factory::get_instance( MembershipAjax::class ), 'membership_ajax_handler' ), 10, 2 );

		// Enqueue Script Ajax Handling.
		\wp_register_script(
			'elemental-membership-js',
			\plugins_url( '/js/membershipadmin.js', \realpath( __FILE__ ) ),
			array( 'jquery' ),
			Factory::get_instance( Version::class )->get_plugin_version() . \wp_rand( 40, 30000 ),
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

		add_shortcode( self::SHORTCODE_TAG, array( Factory::get_instance( MembershipShortCode::class ), 'render_membership_shortcode' ) );

	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

		Factory::get_instance( MembershipDAO::class )->install_membership_mapping_table();
		Factory::get_instance( MemberSyncDAO::class )->install_membership_sync_table();
		$this->create_membership_role();
	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_membership_config_page(): string {
		\wp_enqueue_script( 'elemental-membership-js' );
		$membership_levels = Factory::get_instance( MembershipUMP::class )->get_ump_memberships();
		return ( include __DIR__ . '/views/table-output.php' )( $membership_levels );
	}

	/**
	 * Create Membership Role for Sponsored Account
	 */
	public function create_membership_role(): void {
		global $wp_roles;
		$edr = $wp_roles->get_role( 'Subscriber' );
		add_role( self::MEMBERSHIP_ROLE_NAME, self::MEMBERSHIP_ROLE_DESCRIPTION, $edr->capabilities );
	}

}


