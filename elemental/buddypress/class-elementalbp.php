<?php
/**
 * BuddyPress Integration Features.
 *
 * @package elemental/buddypress/class-elementalbp.php
 */

namespace ElementalPlugin\BuddyPress;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\UltimateMembershipPro\Library\ShortCodesUMP;

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

	public function redirect_profile() {
		// Bypass if not in profile page or if not Store Owner Role.
		if ( ! \function_exists( 'bp_is_user' ) || ! bp_is_user() || Factory::get_instance( UserRoles::class )->is_wcfm_vendor() ) {
			return null;
		}
		$user_id = bp_displayed_user_id();

		echo $user_id;
		return null;
		$url = \get_site_url();
		\wp_safe_redirect( $url );
		die();
	}

}


