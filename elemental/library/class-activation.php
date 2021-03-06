<?php
/**
 * Installs and uninstalls the plugin.
 *
 * @package library/class-activation.php
 */

declare(strict_types=1);

namespace ElementalPlugin\Library;

use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\Sandbox\Sandbox;
use ElementalPlugin\Module\WCFM\WCFM;

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
		Factory::get_instance( Sandbox::class )->activate();
	}

	/**
	 * Remove the plugin
	 */
	public static function uninstall() {
		Factory::get_instance( WCFM::class )->de_activate();
	}

}
