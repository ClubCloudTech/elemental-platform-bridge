<?php
/**
 * The entry point for the plugin
 *
 * @package ElementalPlugin
 */

declare( strict_types=1 );

namespace ElementalPlugin;

use ElementalPlugin\Library\Version;
use ElementalPlugin\Membership\Membership;
use ElementalPlugin\ShortCode\ShortCodeTab;
use ElementalPlugin\WCFM\WCFMSearch;

/**
 * Class Plugin
 */
class Plugin {


	const SHORTCODE_PREFIXS = array( 'mvr_', 'cc' );

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		Factory::get_instance( Admin::class )->init();
		Factory::get_instance( Membership::class )->init();
		Factory::get_instance( ShortCodeTab::class )->init();
		Factory::get_instance( WCFMSearch::class )->init();

		$this->styles();
	}
	/**
	 * Stylesheet Enqueue.
	 */
	private function styles() {
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		wp_register_style(
			'elemental',
			plugins_url( 'assets/css/elemental.css', __FILE__ ),
			false,
			$plugin_version . \wp_rand( 1, 20000 )
		);
		\wp_enqueue_style( 'elemental' );
	}

	/**
	 * Initializer function, returns a instance of the plugin
	 *
	 * @return object
	 */
	public static function init() {

		return Factory::get_instance( self::class );
	}
}
