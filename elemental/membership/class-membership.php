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
use ElementalPlugin\Membership\Library\MembershipAjax;
use ElementalPlugin\Membership\Library\MembershipShortCode;

/**
 * Class Membership
 */
class Membership {
	const TABLE_NAME_MEMBERSHIPS = 'elemental_memberships';
	const TABLE_NAME_MEMBERSYNC  = 'elemental_membersync';
	const SHORTCODE_TAG          = Admin::SHORTCODE_TAG . 'membership';

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
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_membership_config_page(): string {
		// Returns all rooms with null roomtype, or a specific room with Room Type.
		\wp_enqueue_script( 'elemental-membership-js' );
		$membership_levels = $this->get_ump_memberships();
		return ( require __DIR__ . '/views/table-output.php' )( $membership_levels );
	}

	/**
	 * Get the list of Subscription Levels
	 *
	 * @return array
	 */
	public function get_ump_memberships() :array {
		$ihc_data          = get_option( 'ihc_levels' );
		$membership_levels = array_keys( $ihc_data );
		$return_array      = array();

		foreach ( $membership_levels as $level => $value ) {
			$record_array               = array();
			$record_array['level']      = $value;
			$record_array['label']      = $ihc_data[ $value ]['label'];
			$record_array['badge_url']  = $ihc_data[ $value ]['badge_image_url'];
			$record_array['price_text'] = $ihc_data[ $value ]['price_text'];
			$record_array['limit']      = Factory::get_instance( MembershipDAO::class )->get_limit_by_membership( intval( $value ) );

			\array_push( $return_array, $record_array );
		}
		return $return_array;
	}

}


