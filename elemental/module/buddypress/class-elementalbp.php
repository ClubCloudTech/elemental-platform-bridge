<?php
/**
 * BuddyPress Integration Features.
 *
 * @package elemental/buddypress/class-elementalbp.php
 */

namespace ElementalPlugin\Module\BuddyPress;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;

/**
 * Class ElementalBP
 * Supports BuddyPress Custom Functions.
 */
class ElementalBP {

	const SHORTCODE_PROFILE_URL = 'elemental_profileurl';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		if ( ! $this->is_buddypress_available() ) {
			return null;
		}
		\add_action( 'bp_template_redirect', array( $this, 'redirect_profile' ) );
		\add_shortcode( self::SHORTCODE_PROFILE_URL, array( $this, 'get_buddypress_profile_url_shortcode' ) );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {
	}

	/**
	 * Is Friends Module Active - checks if Module of Friends is enabled in BP as well as all other dependencies.
	 *
	 * @return bool
	 */
	public function is_friends_module_available() {
		if ( $this->is_buddypress_available() && function_exists( 'bp_is_active' ) && \bp_is_active( 'friends' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Is User Module Active - checks if Module of User Rooms is enabled.
	 *
	 * @return bool
	 */
	public function is_group_module_available() {

		if ( $this->is_buddypress_available() && function_exists( 'bp_is_active' ) && \bp_is_active( 'groups' ) ) {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Is Buddypress Available - checks if BuddyPress is enabled.
	 *
	 * @return bool
	 */
	public function is_buddypress_available(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'buddypress/bp-loader.php' );
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
	 * BuddyPress Profile Redirect Shortcode for Login Landing Page.
	 * Redirects a user landing in this page to the Users Buddypress ID.
	 *
	 * @return void
	 */
	public function bp_profile_redirect() {

		$user_id = \get_current_user_id();
		$url     = $this->get_buddypress_profile_url( $user_id );
		// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
		echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
		die();
	}

	/**
	 * Get BuddyPress Profile URL Shortcode.
	 *
	 *  @param array $atts = the attributes.
	 *  @return ?string
	 */
	public function get_buddypress_profile_url_shortcode( $atts = array() ) {
		// User_id in attributes.
		if ( ! isset( $atts['user_id'] ) ) {
				// Try user logged in for ID.
			if ( \is_user_logged_in() ) {
				$user_id = \get_current_user_id();
				// User not logged in - exit.
			} else {
				return null;
			}
			// Atts has ID.
		} else {
			$user_id = intval( $atts['user_id'] );
		}

		return $this->get_buddypress_profile_url( $user_id );
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


