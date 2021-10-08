<?php
/**
 * Installs and uninstalls the plugin - fred edit
 *
 * @package ElementalPlugin\Admin
 */

declare(strict_types=1);

namespace ElementalPlugin;

use ElementalPlugin\Membership\Membership;

/**
 * Class Activation
 */
class Activation {



	/**
	 * Activate the plugin, and related modules.
	 */
	public static function activate() {
		Factory::get_instance( Membership::class )->activate();
	}



	/**
	 * Remove the plugin
	 */
	public static function uninstall() {

	}

}
