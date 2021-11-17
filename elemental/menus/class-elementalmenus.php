<?php
/**
 * Menu Handlers Elemental.
 *
 * @package elemental/menus/class-elementalmenus.php
 */

namespace ElementalPlugin\Menus;

use ElementalPlugin\Factory;
use ElementalPlugin\Menus\Library\Switches;

/**
 * Class WCFM Connect
 */
class ElementalMenus {

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		Factory::get_instance( Switches::class )->init();
	}

	/**
	 * Activate Functions.
	 */
	public function activate() {

	}

	/**
	 * De-Activate Functions.
	 */
	public function de_activate() {

	}

}
