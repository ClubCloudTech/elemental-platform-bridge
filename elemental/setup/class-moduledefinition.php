<?php
/**
 * Setup Functions - Module Definition File- Config Modules will get initialised here.
 *
 * @package MyVideoRoomExtrasPlugin\Setup
 */

namespace MyVideoRoomExtrasPlugin\Setup;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\DAO\ModuleConfig;
use MyVideoRoomExtrasPlugin\Factory;

/**
 * Class Module Definition
 */
class ModuleDefinition extends SiteDefaults {

	/**
	 * Site Default Module Definition
	 */
	public function define_default_modules() {

		// Function defines just the basics of each module - have to install more updated config per module.
		// Gets ID's and names from Parameters in Site Defaults.

		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_DEFAULT_VIDEO_NAME, SiteDefaults::MODULE_DEFAULT_VIDEO_ID );
		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_SITE_VIDEO_NAME, SiteDefaults::MODULE_SITE_VIDEO_ID );
		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_PERSONAL_MEETING_NAME, SiteDefaults::MODULE_PERSONAL_MEETING_ID );

		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_BUDDYPRESS_NAME, SiteDefaults::MODULE_BUDDYPRESS_ID );
			Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_BUDDYPRESS_GROUP_NAME, SiteDefaults::MODULE_BUDDYPRESS_GROUP_ID );
			Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_BUDDYPRESS_USER_NAME, SiteDefaults::MODULE_BUDDYPRESS_USER_ID );
			Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_BUDDYPRESS_FRIENDS_NAME, SiteDefaults::MODULE_BUDDYPRESS_FRIENDS_ID );

		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_SECURITY_NAME, SiteDefaults::MODULE_SECURITY_ID );

		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_WC_BOOKINGS_NAME, SiteDefaults::MODULE_WC_BOOKINGS_ID );
		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_WCFM_NAME, SiteDefaults::MODULE_WCFM_ID );
		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_TEMPLATES_NAME, SiteDefaults::MODULE_TEMPLATES_ID );

		// Initialise.
		$this->initialise_site_default_modules();

	}



	/**
	 * Register Additional Modules in DB If Needed
	 */
	public function add_additional_modules_in_db( int $module_id, string $module_name, $initialise_callback_function = null ) {
			// Exit on no input.
		if ( ! $module_id && ! $module_name ) {
			return null;
		}

				Factory::get_instance( ModuleConfig::class )->register_module_in_db( $module_name, $module_id );

			// Initialise.
		if ( $initialise_callback_function ) {
				return $initialise_callback_function;
		}
			return null;
	}





	/**
	 * Configure Site Video Room Defaults
	 */
	public function initialise_site_default_modules() {
		global $wpdb;

		Factory::get_instance( ModuleConfig::class )->update_enabled_status( SiteDefaults::MODULE_SITE_VIDEO_ID, true );
		Factory::get_instance( ModuleConfig::class )->update_enabled_status( SiteDefaults::MODULE_PERSONAL_MEETING_ID, true );
		Factory::get_instance( ModuleConfig::class )->update_enabled_status( SiteDefaults::MODULE_BUDDYPRESS_FRIENDS_ID, true );
		Factory::get_instance( ModuleConfig::class )->register_module_in_db( SiteDefaults::MODULE_SECURITY_NAME, SiteDefaults::MODULE_SECURITY_ID );

	}


}//end class

