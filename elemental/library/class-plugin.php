<?php
/**
 * The entry point for the plugin
 *
 * @package class-plugin.php
 */

declare( strict_types=1 );

namespace ElementalPlugin\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\BuddyPress\ElementalBP;
use ElementalPlugin\Module\WCFM\Library\FiltersUtilities;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Membership\Library\LoginHandler;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\Menus\ElementalMenus;
use ElementalPlugin\Module\Sandbox\Sandbox;
use ElementalPlugin\Module\Integration\Integration;
use ElementalPlugin\Module\Search\Search;
use ElementalPlugin\Module\UltimateMembershipPro\ElementalUMP;
use ElementalPlugin\Module\WCFM\WCFM;
use ElementalPlugin\Library\Admin;
use ElementalPlugin\Module\Pagemanage\Pagemanage;

/**
 * Class Plugin
 */
class Plugin {

	const PLUGIN_NAME_SPACE = 'elemental';
	const SHORTCODE_PREFIXS = array( 'mvr_', 'cc' );
	const ELEMENTAL_SLUG    = 'elemental';

	/**
	 * Plugin constructor.
	 */
	public function __construct() {

		Factory::get_instance( Admin::class )->init();
		Factory::get_instance( Membership::class )->init();
		Factory::get_instance( ElementalUMP::class )->init();
		Factory::get_instance( WCFM::class )->init();
		Factory::get_instance( FiltersUtilities::class )->init();
		Factory::get_instance( Search::class )->init();
		Factory::get_instance( ElementalBP::class )->init();
		Factory::get_instance( ElementalMenus::class )->init();
		Factory::get_instance( Sandbox::class )->init();
		Factory::get_instance( Integration::class )->init();
		Factory::get_instance( Pagemanage::class )->init();
		$this->styles();
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Handles Login Redirect Handlers.
		add_action( 'init', array( Factory::get_instance( LoginHandler::class ), 'elemental_login_switch_hook' ) );
		add_action( 'wp_login', array( Factory::get_instance( LoginHandler::class ), 'elemental_add_staff_account_cookie_hook' ), 10, 2 );

	}
	/**
	 * Stylesheet Enqueue.
	 */
	private function styles() {
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();

		wp_register_style(
			'elemental',
			plugins_url( '../assets/css/elemental.css', __FILE__ ),
			false,
			$plugin_version
		);
		\wp_enqueue_style( 'elemental' );

		// Frontend NonMobile Style.

		\add_action(
			'wp_enqueue_scripts',
			function () {
				\wp_enqueue_style(
					'elemental-frontend-css',
					\plugins_url( '../assets/css/frontend.css', __FILE__ ),
					false,
					Factory::get_instance( Version::class )->get_plugin_version(),
					'(min-width: 640px)'
				);
			},
		);

		// Front End Mobile.
		\add_action(
			'wp_enqueue_scripts',
			function () {
				\wp_enqueue_style(
					'myvideoroom-frontend-mobile-css',
					\plugins_url( '../assets/css/frontend-mobile.css', __FILE__ ),
					false,
					Factory::get_instance( Version::class )->get_plugin_version(),
					'(max-width: 640px)'
				);
			},
		);
		// Tabbed Frame Navigation.
		wp_register_script(
			'elemental-admin-tabs',
			plugins_url( '../assets/js/tabbed.js', __FILE__ ),
			array( 'jquery-ui' ),
			$plugin_version,
			true
		);
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
