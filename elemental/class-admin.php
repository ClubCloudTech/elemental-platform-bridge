<?php
/**
 * Manages the configuration settings for the video plugin .
 *
 * @package MyVideoRoomExtrasPlugin\Admin
 */

declare(strict_types=1);

namespace ElementalPlugin;

use \MyVideoRoomPlugin\Admin as MVRAdmin;
use ElementalPlugin\Membership\Library\MembershipShortCode;
use \MyVideoRoomPlugin\SiteDefaults;


/**
 * Class Admin
 */
class Admin {

	const SHORTCODE_TAG = 'elemental_';
	/**
	 * Initialise menu items.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_shortcode( self::SHORTCODE_TAG . 'proxytest', array( $this, 'proxy_test_function' ) );
	}

	/**
	 * Add the admin menu page.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			'Elemental Configuration',
			'Elemental',
			'manage_options',
			'my-video-room-extras',
			array( $this, 'create_extras_admin_page' ),
			'dashicons-menu-alt3'
		);
	}
	/**
	 * Create the extra admin page contents.
	 */
	public function create_extras_admin_page(): void {
     // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification.Recommended -- Not required
		$active_tab = $_GET['tab'] ?? null;
		\wp_enqueue_script( 'mvr-admin-ajax-js' );
		Factory::get_instance( MVRAdmin::class )->init();
		$tabs = array(
			'admin-settings-plugin'     => 'Plugin Settings',
			'admin-settings-membership' => 'Membership Settings',
			'admin-settings-bookings'   => 'WooComm Bookings Integration',
			'admin-settings-wcfm'       => 'WCFM Store Integration',
		);

		if ( ! $active_tab || ! isset( $tabs[ $active_tab ] ) ) {
			$active_tab = array_key_first( $tabs );
		}

		$messages = array();
		$render   = include __DIR__ . '/views/admin/' . $active_tab . '.php';

		echo $render( $active_tab, $tabs, $messages );
	}

	/**
	 * A shortcode to Proxy Functions to Front End for Testing
	 * Used to Setup basic settings
	 * Used to run whatever is in here from the /test page
	 *
	 * @return null
	 */
	public function proxy_test_function() {
		// Factory::get_instance( Security::class )->activate_module();
		Factory::get_instance( MembershipShortCode::class )->render_membership_shortcode();
		return null;
	}
}

