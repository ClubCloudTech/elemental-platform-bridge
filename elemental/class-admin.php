<?php
/**
 * Manages the configuration settings for the video plugin .
 *
 * @package MyVideoRoomExtrasPlugin\Admin
 */

declare(strict_types=1);

namespace MyVideoRoomExtrasPlugin;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;

/**
 * Class Admin
 */
class Admin extends Shortcode {

	/**
	 * Initialise the menu item.
	 */
	public function install() {
		add_action( 'myvideoroom_admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add the admin menu page.
	 *
	 * @param string $parent_slug The parent menu slug.
	 */
	public function add_admin_menu( string $parent_slug ) {
		add_submenu_page(
			$parent_slug,
			'Extras',
			'Extras',
			'manage_options',
			'my-video-room-extras',
			array( $this, 'create_extras_admin_page' )
		);
	}

	/**
	 * Create the extra admin page contents.
	 */
	public function create_extras_admin_page(): void {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification.Recommended -- Not required
		$active_tab = $_GET['tab'] ?? null;

		$tabs = array(
			'admin-settings-sitedefault'               => 'Default Video Settings',
			SiteDefaults::MODULE_SITE_VIDEO_ADMIN_PAGE => SiteDefaults::MODULE_SITE_VIDEO_DISPLAY,
			'admin-settings-personalvideo'             => 'Personal Meeting Settings',
			'admin-settings-buddypress'                => 'BuddyPress Video Settings',
			'admin-settings-bookings'                  => 'WooComm Bookings Integration',
			'admin-settings-wcfm'                      => 'WCFM Store Integration',
			'admin-settings-templates'                 => 'Room Template Browser',
			'admin-settings-roombuilder'               => 'Room Builder',
			SiteDefaults::MODULE_SECURITY_ADMIN_PAGE   => SiteDefaults::MODULE_SECURITY_DISPLAY,
		);

		if ( ! $active_tab || ! isset( $tabs[ $active_tab ] ) ) {
			$active_tab = array_key_first( $tabs );
		}

		$messages = array();
		$render   = require __DIR__ . '/views/admin/' . $active_tab . '.php';
// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped -- Not required as function has escaping within it.
		echo $render( $active_tab, $tabs, $messages );
	}

}

