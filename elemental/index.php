<?php
/**
 * Coadjute Elemental Plugin.
 *
 * @package ElementalPlugin
 */

declare(strict_types=1);

/**
 * Plugin Name:         Elemental Platform
 * Plugin URI:          https://coadjute.com
 * Description:         Adds user management, multi-entity onboarding, and integration support functions for Ultimate Member Pro, BuddyPress, WooCommerce, and WCFM.
 * Version:             0.93
 * Requires PHP:        7.4
 * Requires at least:   5.6
 * Author:              Fred Mocellin, Then by Coadjute team
 * Author URI:          https://clubcloud.tech/
 * License:             GPLv2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 */


namespace ElementalPlugin;

use ElementalPlugin\Library\Activation;
use ElementalPlugin\Library\Plugin;

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'get_plugin_data' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! class_exists( Plugin::class ) ) {
	/**
	 * Autoloader for classes in the Elemental Plugin.
	 *
	 * @param string $class_name The name of the class to autoload.
	 *
	 * @throws \Exception When file is not found.
	 *
	 * @return boolean
	 */
	function autoloader( string $class_name ): bool {
		if ( strpos( $class_name, 'ElementalPlugin' ) === 0 ) {
			$src_location = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;

			$file_name = str_replace( 'ElementalPlugin\\', '', $class_name );
			$file_name = strtolower( $file_name );

			$file_name = str_replace( '\\', DIRECTORY_SEPARATOR, $file_name ) . '.php';

			$path     = ( pathinfo( $file_name ) );
			$location = realpath( $src_location . $path['dirname'] ) . '/class-' . $path['basename'];

			if ( ! file_exists( $location ) ) {

				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                       // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
					trigger_error(
						esc_html( 'Failed to include "' . $src_location . $path['dirname'] . '/class-' . $path['basename'] . '"' ),
						E_USER_ERROR
					);
				}

				return false;
			}

			return (bool) include_once $location;
		}

		return false;
	}

	spl_autoload_register( 'ElementalPlugin\autoloader' );
	require_once __DIR__ . '/library/globals.php';
	add_action( 'plugins_loaded', array( Plugin::class, 'init' ) );
	register_activation_hook( __FILE__, array( Activation::class, 'activate' ) );
	register_uninstall_hook( __FILE__, array( Activation::class, 'uninstall' ) );

	// require_once __DIR__ . '/vendor/autoload.php';
}
