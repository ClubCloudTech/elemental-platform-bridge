<?php
/**
 * My Video Room Extras Globals.
 * These are legacy global functions that were exported by the plugin
 *
 * @package library/globals.php
 */

declare(strict_types=1);

use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

if ( ! function_exists( 'elemental_get_wcfm_memberships' ) ) {
	/**
	 * Wrapper for Elemental Get WCFM Memberships Function
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function elemental_get_wcfm_memberships() {
		$function_to_call = new WCFMTools();
		return $function_to_call->elemental_get_wcfm_memberships( ...func_get_args() );
	}

	/**
	 * Wrapper for Elemental Get Store Memberships Function.
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function get_ump_memberships() {
		$function_to_call = new UMPMemberships();
		return $function_to_call->get_ump_memberships( ...func_get_args() );
	}

	/**
	 * Wrapper for Elemental Get Store UMP Memberships Function.
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function elemental_get_store_memberships() {
		$function_to_call = new WCFMTools();
		return $function_to_call->elemental_get_store_memberships( ...func_get_args() );
	}

	/**
	 * Wrapper for Elemental Am I Premium Function.
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function elemental_am_i_premium() {
		$function_to_call = new WCFMTools();
		return $function_to_call->elemental_am_i_premium( ...func_get_args() );
	}

	/**
	 * Wrapper for Elemental Get Page Owner Function.
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function elemental_get_wcfm_page_owner() {
		$function_to_call = new WCFMTools();
		return $function_to_call->get_wcfm_page_owner( ...func_get_args() );
	}

	/**
	 * Wrapper for Elemental get_user_roles Function.
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	function get_user_roles() {
		$function_to_call = new UserRoles();
		return $function_to_call->get_user_roles( ...func_get_args() );
	}
}

