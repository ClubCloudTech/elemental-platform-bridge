<?php
/**
 * Installs and uninstalls the plugin - fred edit konkey dong
 *
 * @package ElementalPlugin\Admin
 */

declare(strict_types=1);

namespace ElementalPlugin;

use ElementalPlugin\Membership\Membership;
use ElementalPlugin\WCFM\WCFM;

/**
 * Class Activation
 */
class Activation {




	/**
	 * Activate the plugin, and related modules.
	 */
	public static function activate() {
		Factory::get_instance( Membership::class )->activate();
		Factory::get_instance( WCFM::class )->activate();
	}



	/**
	 * Remove the plugin
	 */
	public static function uninstall() {
		Factory::get_instance( WCFM::class )->de_activate();
	}

}
