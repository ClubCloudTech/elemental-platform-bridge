<?php
/**
 * Installs and uninstalls the plugin - fred edit
 *
 * @package ElementalPlugin\Admin
 */

declare(strict_types=1);

namespace ElementalPlugin;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Setup\Setup;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;

/**
 * Class Activation
 */
class Activation {



	/**
	 * Activate the plugin.
	 */
	public static function activate() {
		Factory::get_instance( MembershipDAO::class )->install_membership_mapping_table();
		Factory::get_instance( MemberSyncDAO::class )->install_membership_sync_table();
	}



	/**
	 * Remove the plugin
	 */
	public static function uninstall() {

	}

}
