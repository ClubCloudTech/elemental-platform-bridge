<?php
/**
 * Setup Functions
 *
 * @package ElementalPlugin\Setup
 */

namespace ElementalPlugin\Setup;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\DAO\RoomMap;
use ElementalPlugin\Factory;


/**
 * Class Setup
 */
class Setup {


	const TABLE_NAME = 'myvideoroom_extras_room_post_mapping';



	/**
	 * Create Meet Center Handler
	 *
	 * @return null as its a database function.
	 */
	public function create_meeting_page() {

		return Factory::get_instance( RoomAdmin::class )->create_and_check_page(
			SiteDefaults::ROOM_NAME_PERSONAL_MEETING,
			SiteDefaults::ROOM_TITLE_PERSONAL_MEETING,
			SiteDefaults::ROOM_SLUG_PERSONAL_MEETING,
			SiteDefaults::ROOM_SHORTCODE_PERSONAL_MEETING
		);

	}

	/**
	 * Create Bookings Handler
	 *
	 * @return null as its a database function.
	 */
	public function create_bookings_center_page() {
		return Factory::get_instance( RoomAdmin::class )->create_and_check_page(
			SiteDefaults::ROOM_NAME_BOOKINGS_CENTER,
			SiteDefaults::ROOM_TITLE_BOOKINGS_CENTER,
			SiteDefaults::ROOM_SLUG_BOOKINGS_CENTER,
			SiteDefaults::ROOM_SHORTCODE_BOOKINGS_CENTER
		);
	}
	/**
	 * Create Site VideoRoom Handler
	 *
	 * @return null as its a database function.
	 */
	public function create_site_videoroom_page() {
		return Factory::get_instance( RoomAdmin::class )->create_and_check_page(
			SiteDefaults::ROOM_NAME_SITE_VIDEO,
			SiteDefaults::ROOM_TITLE_SITE_VIDEO,
			SiteDefaults::ROOM_SLUG_SITE_VIDEO,
			SiteDefaults::ROOM_SHORTCODE_SITE_VIDEO
		);
	}


	/**
	 * Initialise_default_video_settings - adds default settings to the main room table on Plugin setup.
	 * Also called by site settings default page every time its accessed to ensure default settings still there.
	 * Needed as the default settings are the ones users default to in case no room preference. Without it all rooms without config break.
	 *
	 * @return string - message and changes to db.
	 */
	public function initialise_default_video_settings() {

		// Site Default - Entire Site.
		// Factory::get_instance( RoomMap::class )->room_default_settings_install( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'boardroom', 'default', false );

		return '<h2>Default Settings Updated</h2>';
	}


	/**
	 * Install_user_video_preference_table - this is the main table for all User Room Config
	 *
	 * @return void
	 */
	public static function install_user_video_preference_table() {
		global $wpdb;
		$table_name = SiteDefaults::TABLE_NAME_USER_VIDEO_PREFERENCE;
		// Create Main Table for Room Config.
		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . $table_name . '` (
                           `user_id` BIGINT UNSIGNED NOT NULL,
                           `room_name` VARCHAR(255) NOT NULL,
                           `layout_id` VARCHAR(255) NULL,
                           `reception_id` VARCHAR(255) NULL,
                           `reception_enabled` BOOLEAN,
						   `reception_video_enabled` BOOLEAN,
						   `reception_video_url` VARCHAR(255) NULL,
						   `show_floorplan` BOOLEAN,
                           PRIMARY KEY (`user_id`, `room_name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		\dbDelta( $sql );

	}

	/**
	 * Install Room Mapping Config Table - Create Table for Mapping Meet/Go and Other Plugin Rooms to WP post IDs.
	 */
	public static function install_room_post_mapping_table() {
		global $wpdb;
		$table_name = SiteDefaults::TABLE_NAME_ROOM_MAP;
		$sql2       = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . $table_name . '` (
				
			`room_name` VARCHAR(255) NOT NULL,
			`post_id` BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (`post_id`, `room_name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		\dbDelta( $sql2 );
	}





	/**
	 * Install Module Config Table.
	 */
	public static function install_module_config_table() {
		global $wpdb;
		$table_name = SiteDefaults::TABLE_NAME_MODULE_CONFIG;
		// Create Main Table for Module Config.
		$sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . $table_name . '` (
						
						`module_id` BIGINT UNSIGNED NOT NULL,
						`module_name` VARCHAR(255) NOT NULL,
						`module_enabled` BOOLEAN,
						`module_status` VARCHAR(255) NULL,
						`module_param` VARCHAR(255) NULL,
						
						`status_feature1` VARCHAR(255) NULL,
						`status_feature2` VARCHAR(255) NULL,
						`status_feature3` VARCHAR(255) NULL,
						`status_feature4` VARCHAR(255) NULL,
						`status_feature5` VARCHAR(255) NULL,
						`status_feature6` VARCHAR(255) NULL,
						`status_feature7` VARCHAR(255) NULL,
						`status_feature8` VARCHAR(255) NULL,
						`status_feature9` VARCHAR(255) NULL,
						`status_feature10` VARCHAR(255) NULL,

						`info_feature1` VARCHAR(255) NULL,
						`info_feature2` VARCHAR(255) NULL,
						`info_feature3` VARCHAR(255) NULL,
						`info_feature4` VARCHAR(255) NULL,
						`info_feature5` VARCHAR(255) NULL,
						`info_feature6` VARCHAR(255) NULL,
						`info_feature7` VARCHAR(255) NULL,
						`info_feature8` VARCHAR(255) NULL,
						`info_feature9` VARCHAR(255) NULL,
						`info_feature10` VARCHAR(255) NULL,

						`param1_feature1` VARCHAR(255) NULL,
						`param1_feature2` VARCHAR(255) NULL,
						`param1_feature3` VARCHAR(255) NULL,
						`param1_feature4` VARCHAR(255) NULL,
						`param1_feature5` VARCHAR(255) NULL,
						`param1_feature6` VARCHAR(255) NULL,
						`param1_feature7` VARCHAR(255) NULL,
						`param1_feature8` VARCHAR(255) NULL,
						`param1_feature9` VARCHAR(255) NULL,
						`param1_feature10` VARCHAR(255) NULL,

						`param2_feature1` VARCHAR(255) NULL,
						`param2_feature2` VARCHAR(255) NULL,
						`param2_feature3` VARCHAR(255) NULL,
						`param2_feature4` VARCHAR(255) NULL,
						`param2_feature5` VARCHAR(255) NULL,
						`param2_feature6` VARCHAR(255) NULL,
						`param2_feature7` VARCHAR(255) NULL,
						`param2_feature8` VARCHAR(255) NULL,
						`param2_feature9` VARCHAR(255) NULL,
						`param2_feature10` VARCHAR(255) NULL,

						`param3_feature1` VARCHAR(255) NULL,
						`param3_feature2` VARCHAR(255) NULL,
						`param3_feature3` VARCHAR(255) NULL,
						`param3_feature4` VARCHAR(255) NULL,
						`param3_feature5` VARCHAR(255) NULL,
						`param3_feature6` VARCHAR(255) NULL,
						`param3_feature7` VARCHAR(255) NULL,
						`param3_feature8` VARCHAR(255) NULL,
						`param3_feature9` VARCHAR(255) NULL,
						`param3_feature10` VARCHAR(255) NULL,

						
						PRIMARY KEY (`module_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		\dbDelta( $sql );
		return Factory::get_instance( ModuleDefinition::class )->define_default_modules();

	}


	/**
	 * Install Module Security Config Table.
	 */
	public static function install_security_config_table() {
		global $wpdb;

		// Create Main Table for Module Config.
		$table_name = SiteDefaults::TABLE_NAME_SECURITY_CONFIG;
		$sql        = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . $table_name . '` (
					`user_id` BIGINT UNSIGNED NOT NULL,
					`room_name` VARCHAR(255) NOT NULL,
					`room_disabled` BOOLEAN,
					`anonymous_enabled` BOOLEAN,
					`allow_role_control_enabled` BOOLEAN,
					`block_role_control_enabled` BOOLEAN,
					`site_override_enabled` BOOLEAN,
					`restrict_group_to_members_enabled` VARCHAR(255) NULL,
					`allowed_roles` VARCHAR(255) NULL,
					`blocked_roles` VARCHAR(255) NULL,
					`allowed_users` VARCHAR(255) NULL,
					`blocked_users` VARCHAR(255) NULL,
					`bp_friends_setting` VARCHAR(255) NULL,
										
					`allowed_template_id` BIGINT UNSIGNED NULL,
					`blocked_template_id` BIGINT UNSIGNED NULL,
					
					`parameter1_bool` BOOLEAN,
					`parameter2_bool` BOOLEAN,
					`parameter3_bool` BOOLEAN,
					`parameter4_bool` BOOLEAN,
					`parameter5_bool` BOOLEAN,
					`parameter6_bool` BOOLEAN,
					`parameter7_bool` BOOLEAN,
					`parameter8_bool` BOOLEAN,

					`parameter1_setting1` VARCHAR(255) NULL,
					`parameter1_setting2` VARCHAR(255) NULL,
					`parameter1_setting3` VARCHAR(255) NULL,
					`parameter1_setting4` VARCHAR(255) NULL,

					`parameter2_setting1` VARCHAR(255) NULL,
					`parameter2_setting2` VARCHAR(255) NULL,
					`parameter2_setting3` VARCHAR(255) NULL,
					`parameter2_setting4` VARCHAR(255) NULL,

					`parameter3_setting1` VARCHAR(255) NULL,
					`parameter3_setting2` VARCHAR(255) NULL,
					`parameter3_setting3` VARCHAR(255) NULL,
					`parameter3_setting4` VARCHAR(255) NULL,

					`parameter4_setting1` VARCHAR(255) NULL,
					`parameter4_setting2` VARCHAR(255) NULL,
					`parameter4_setting3` VARCHAR(255) NULL,
					`parameter4_setting4` VARCHAR(255) NULL,
					
					`reception_enabled` BOOLEAN,
					PRIMARY KEY (`user_id`, `room_name`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		\dbDelta( $sql );

	}


}

