<?php
/**
 * BuddyPress Integration Features.
 *
 * @package elemental/buddypress/class-elementalbp.php
 */

namespace ElementalPlugin\BuddyPress;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\Library\WCFMTools;

/**
 * Class ElementalBP
 * Supports BuddyPress Custom Functions.
 */
class ElementalBP {


	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		\add_action( 'bp_template_redirect', array( $this, 'redirect_profile' ) );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

	}

	/**
	 * Redirect Store Owner BP Profile Views to Store Pages.
	 *
	 * @return ?void
	 */
	public function redirect_profile() {
		// Bypass if not in profile page or if not looking at Store Owner Role.
		$user_id    = bp_displayed_user_id();
		$storeowner = Factory::get_instance( WCFMTools::class )->am_i_storeowner( $user_id );
		if ( ! \function_exists( 'bp_is_user' ) || ! bp_is_user() || ! $storeowner ) {
			return null;
		}

		$current_user_id = \get_current_user_id();

		if ( $user_id === $current_user_id ) {
			$url = get_site_url() . '/control';
		} else {
			$url = Factory::get_instance( WCFMTools::class )->get_store_url( $user_id );
		}
		\wp_safe_redirect( $url );

		die();
	}

	/**
	 * Get BuddyPress Profile URL.
	 *
	 *  @param int $user_id = the user_id to look up.
	 *  @return ?string
	 */
	public function get_buddypress_profile_url( int $user_id ) {
		if ( \function_exists( 'bp_core_get_user_domain' ) ) {
			return \bp_core_get_user_domain( $user_id );
		}
		return null;
	}


}


