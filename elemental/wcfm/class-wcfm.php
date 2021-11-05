<?php
/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WCFM;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\Library\WCFMShortcodes;

/**
 * Class WCFM Connect
 */
class WCFM {

	const SHORTCODE_DISPLAY_PRODUCT = 'wcfm_store_products';
	const SHORTCODE_STORE_FIELDS    = 'wcfm_store_fields';

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		Factory::get_instance( WCFMSearch::class )->init();
		Factory::get_instance( WCFMShortcodes::class )->init();
		add_shortcode( self::SHORTCODE_DISPLAY_PRODUCT, array( Factory::get_instance( WCFMTools::class ), 'display_products' ) );
		add_shortcode( self::SHORTCODE_STORE_FIELDS, array( Factory::get_instance( WCFMTools::class ), 'wcfm_store_display' ) );
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
