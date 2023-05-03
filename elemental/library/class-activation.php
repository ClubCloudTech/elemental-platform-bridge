<?php
/**
 * Installs and uninstalls the plugin.
 *
 * @package library/class-activation.php
 */

declare(strict_types=1);

namespace ElementalPlugin\Library;

use ElementalPlugin\DAO\TokenDAO;
use ElementalPlugin\DAO\UserPreferenceDAO;
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
		self::install_main_plugin_tables();
	}

	/**
	 * Remove the plugin
	 */
	public static function uninstall() {

	}

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate() {
		Factory::get_instance( WCFM::class )->de_activate();
		Factory::get_instance( Membership::class )->de_activate();
	}

	/**
	 * Activate the plugin, and related modules.
	 */
	private static function install_main_plugin_tables() {
		Factory::get_instance( TokenDAO::class )->install_user_tokens_table();
		Factory::get_instance( UserPreferenceDAO::class )->install_user_preference_table();
	}

}
