<?php
/**
 * BuddyPress Integration Features.
 *
 * @package elemental/bbpress/class-elementalbbpress.php
 */

namespace ElementalPlugin\BBPress;

use ElementalPlugin\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Class ElementalBP
 * Supports BuddyPress Custom Functions.
 */
class ElementalBBPress {

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {

	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

	}

	/**
	 * Is BBPress Active.
	 *
	 *  @return bool
	 */
	public function is_bbpress_active(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'bbpress/bbpress.php' ) ) {
			// plugin is active.
			return true;
		} else {
			return false;
		}
	}
}


