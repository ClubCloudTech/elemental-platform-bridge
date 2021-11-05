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

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_shortcode( 'elemental_logout', array( $this, 'render_logout_shortcode' ) );
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
}
