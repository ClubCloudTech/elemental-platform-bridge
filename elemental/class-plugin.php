<?php
/**
 * The entry point for the plugin
 *
 * @package ElementalPlugin
 */

declare( strict_types=1 );

namespace ElementalPlugin;

/**
 * Class Plugin
 */
class Plugin {


	const SHORTCODE_PREFIXS = array( 'mvr_', 'cc' );

	/**
	 * Plugin constructor.
	 */
	public function __construct() {

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
