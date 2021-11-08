<?php
/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WCFM;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\Library\WCFMFilters;
use ElementalPlugin\WCFM\Library\WCFMHelpers;
use ElementalPlugin\WCFM\Library\WCFMSearch;
use ElementalPlugin\WCFM\Library\WCFMShortcodes;
use ElementalPlugin\WCFM\Library\WCFMStyling;
use ElementalPlugin\WCFM\Library\WCFMTools;

/**
 * Class WCFM Connect
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

}
