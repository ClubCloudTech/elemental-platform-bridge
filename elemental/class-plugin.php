<?php
/**
 * The entry point for the plugin
 *
 * @package MyVideoRoomExtrasPlugin
 */

declare( strict_types=1 );

namespace MyVideoRoomExtrasPlugin;

/**
 * Class Plugin
 */
class Plugin {

	const SHORTCODE_PREFIXS = array( 'mvr_', 'cc' );

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		Factory::get_instance( Admin::class )->install();

		Factory::get_instance( Core\FiltersUtilities::class )->install();
		Factory::get_instance( Core\HeaderSwitches::class )->install();
		Factory::get_instance( Core\MenuHelpers::class )->install();
		Factory::get_instance( Core\PageSwitches::class )->install();
		Factory::get_instance( Core\SiteDefaults::class )->install();
		Factory::get_instance( Core\URLSwitch::class )->install();
		Factory::get_instance( Core\VideoControllers::class )->install();
		Factory::get_instance( WoocommerceBookings\WCHelpers::class )->install();

		Factory::get_instance( WoocommerceBookings\Connect::class )->install();
		Factory::get_instance( WoocommerceBookings\ShortCodeConstructor::class )->install();

		Factory::get_instance( BuddyPress\BuddyPress::class )->install();
		Factory::get_instance( BuddyPress\BuddyPressVideo::class )->install();
		Factory::get_instance( WCFM\WCFMHelpers::class )->install();
		Factory::get_instance( WCFM\WCFMConnect::class )->install();
		Factory::get_instance( MVR\ViewConnections::class )->install();
		Factory::get_instance( Setup\RoomAdmin::class )->install();
		Factory::get_instance( Shortcode\UserVideoPreference::class )->install();

		Factory::get_instance( Library\ShortCodeHelpers::class )->install();

		Factory::get_instance( MVR\PageSwitches::class )->install();
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
