<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-loginhandler.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\HttpGet;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class LoginHandler {

	const SHORTCODE_LOGOUT_SWITCH = 'elemental_logout';
	const SHORTCODE_LOGOUT        = 'elemental_logout_url';

	const SHORTCODE_LEGACY_LOGOUT = 'cclogout';
	const SHORTCODE_LEGACY_LOGIN  = 'ccloginswitch';
	const SHORTCODE_LOGIN_SWITCH  = 'elemental_login';

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_LOGOUT_SWITCH, array( $this, 'render_logout_shortcode' ) );
		add_shortcode( self::SHORTCODE_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LOGIN_SWITCH, array( $this, 'elemental_loginswitch' ) );

		// Legacy Shortcodes.
		add_shortcode( self::SHORTCODE_LEGACY_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LEGACY_LOGIN, array( $this, 'elemental_loginswitch' ) );
	}
	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return void
	 */
	public function render_logout_shortcode( $attributes = array() ) {
		$http_get_library = Factory::get_instance( HttpGet::class );
		$logged_out       = $http_get_library->get_string_parameter( 'logged_out' );
		if ( 'true' === $logged_out ) {
			$url = \get_site_url() . '/logout';
			// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
			echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
		}
	}

	/**
	 * Render shortcode to provide WordPress Logout URL (used in menu links)
	 *
	 * @return string
	 */
	public function elemental_logout_url() {
		return wp_logout_url( home_url() );
	}

	/**
	 * Render shortcode to provide login template for Login pages.
	 *
	 * @return string
	 */
	public function elemental_loginswitch() {
		return do_shortcode( '[elementor-template id="24912"]' );
	}
}
