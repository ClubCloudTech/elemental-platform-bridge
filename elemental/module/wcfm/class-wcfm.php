<?php
/**
 * Connect elemental to Woocommerce FrontEnd Manager Video
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\Module\WCFM;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMFilters;
use ElementalPlugin\Module\WCFM\Library\WCFMHelpers;
use ElementalPlugin\Module\WCFM\Library\WCFMShortcodes;
use ElementalPlugin\Module\WCFM\Library\WCFMStyling;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Class WCFM Connect..
 */
class WCFM {

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		Factory::get_instance( WCFMShortcodes::class )->init();
		Factory::get_instance( WCFMHelpers::class )->init();
		Factory::get_instance( WCFMTools::class )->init();
		Factory::get_instance( WCFMFilters::class )->init();
		Factory::get_instance( WCFMStyling::class )->init();

		// Remove WCFM Store Function from BuddyPress Profiles.
		remove_action( 'bp_member_options_nav', 'bp_wcfmmp_store_nav_item', 99 );
	}

	/**
	 * Activate Functions.
	 */
	public function activate() {

	}

	/**
	 * De-Activate Functions.
	 */
	public function de_activate() {

	}

	/**
	 * Is WCFM Active.
	 */
	public function is_wcfm_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		return is_plugin_active( 'wc-frontend-manager/wc_frontend_manager.php' );
	}

}
