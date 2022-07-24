<?php
/**
 * Get versioning information
 *
 * @package library/class-version.php
 */

declare( strict_types=1 );

namespace ElementalPlugin\Library;

/**
 * Version
 */
class Version {

	/**
	 * Get the current version of the installed plugin
	 * Used for cache-busting.
	 *
	 * @return string
	 */
	public function get_plugin_version(): string {
		$plugin_data = \get_plugin_data( __DIR__ . '/../index.php' );

		$plugin_version = $plugin_data['Version'];
		// TODO Remove before Production.
		return $plugin_version . time();
	}
}
