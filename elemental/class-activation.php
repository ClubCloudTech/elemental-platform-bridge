<?php
/**
 * Installs and uninstalls the plugin - fred edit
 *
 * @package MyVideoRoomExtrasPlugin\Admin
 */

declare(strict_types=1);

namespace MyVideoRoomExtrasPlugin;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Setup\Setup;

/**
 * Class Activation
 */
class Activation {


	/**
	 * Activate the plugin
	 */
	public static function activate() {

		// Database Operations.

		// Install User Video Preference table.
		Factory::get_instance( Setup::class )->install_user_video_preference_table();

		// Install Room Post Mapping config table.
		Factory::get_instance( Setup::class )->install_room_post_mapping_table();

		// Install Module config table.
		Factory::get_instance( Setup::class )->install_module_config_table();

		// Install Room Security Config table.
		Factory::get_instance( Setup::class )->install_security_config_table();

		/*
			Default Rooms Creation
		*/

		// Generate Site Video Room Page.
		Factory::get_instance( Setup::class )->create_site_videoroom_page();

		// Generate Meet Page.
		Factory::get_instance( Setup::class )->create_meeting_page();

		// Generate Bookings Center Page - @TODO will have to move to be called from Plugin Config page.
		Factory::get_instance( Setup::class )->create_bookings_center_page();

		/*
			Set Base Room Layout Config
		*/

		// Initialise Site Default Settings.
		Factory::get_instance( Setup::class )->initialise_default_video_settings();
	}



	/**
	 * Remove the plugin
	 */
	public static function uninstall() {
		global $wpdb;
		$table_names = array(
			$wpdb->prefix . SiteDefaults::TABLE_NAME_MODULE_CONFIG,
			$wpdb->prefix . SiteDefaults::TABLE_NAME_ROOM_MAP,
			$wpdb->prefix . SiteDefaults::TABLE_NAME_USER_VIDEO_PREFERENCE,
			$wpdb->prefix . SiteDefaults::TABLE_NAME_SECURITY_CONFIG,
		);

		foreach ( $table_names as $table_name ) {
			$sql = "DROP TABLE IF EXISTS $table_name";
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
			$wpdb->query( $sql );
		}
	}
}
