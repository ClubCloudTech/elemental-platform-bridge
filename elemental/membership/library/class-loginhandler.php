<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-loginhandler.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\BuddyPress\ElementalBP;
use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\Ajax;
use \MyVideoRoomPlugin\Library\HttpGet;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class LoginHandler {

	const SHORTCODE_LOGOUT_SWITCH       = 'elemental_logout';
	const SHORTCODE_LOGOUT              = 'elemental_logout_url';
	const SHORTCODE_BP_PROFILE_REDIRECT = 'elemental_profile_redirect';

	const SHORTCODE_LEGACY_LOGOUT = 'cclogout';
	const SHORTCODE_LEGACY_LOGIN  = 'ccloginswitch';
	const SHORTCODE_LOGIN_SWITCH  = 'elemental_login';
	const SHORTCODE_LOGIN_BUTTON  = 'elemental_loginbutton';

	const SETTING_LOGIN_SWITCH_TEMPLATE = 'elemental-login-switch-template';

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_LOGOUT_SWITCH, array( $this, 'render_logout_shortcode' ) );
		add_shortcode( self::SHORTCODE_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LOGIN_SWITCH, array( $this, 'elemental_loginswitch' ) );
		add_shortcode( self::SHORTCODE_LOGIN_BUTTON, array( $this, 'elemental_login_out' ) );
		add_shortcode( self::SHORTCODE_BP_PROFILE_REDIRECT, array( $this, 'bp_profile_redirect' ) );

		// Legacy Shortcodes.
		add_shortcode( self::SHORTCODE_LEGACY_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LEGACY_LOGIN, array( $this, 'elemental_loginswitch' ) );

		// Option for Login Switch Plugin Setting.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_login_template_settings' ), 5, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_login_template_setting' ), 5, 2 );
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
	 * @param string $redirect - the redirect url.
	 * @return string
	 */
	public function elemental_logout_url( string $redirect = null ) {
		if ( ! $redirect ) {
			$redirect = home_url();
		}
		return wp_logout_url( $redirect );
	}

	/**
	 * Login/Out Shortcode Switch.
	 *
	 * @return string
	 */
	public function elemental_login_out(): string {

		if ( \is_user_logged_in() ) {
			$nonce = \wp_create_nonce( 'logout' );
			$url   = get_site_url() . '/login?action=logout&nonce=' . $nonce;
			return '<a href="' . $url . '" class="elemental-thankyou-link">' . esc_html__( 'Sign Out', 'myvideoroom' ) . '</a>';
		} else {
			return '<button class="elemental-header-loginout"> <a class="elemental-thankyou-link" href="' . \get_site_url() . '/login" >' . esc_html__( 'Login', 'myvideoroom' ) . '</a> </button>';
		}
	}

	/**
	 * Render shortcode to provide login template for Login pages.
	 *
	 * @return string
	 */
	public function elemental_loginswitch() {
		$http_get_library = Factory::get_instance( HttpGet::class );
		$action           = $http_get_library->get_string_parameter( 'action' );
		$nonce            = $http_get_library->get_string_parameter( 'nonce' );

		if ( 'logout' === $action && \wp_verify_nonce( $nonce, 'logout' ) ) {

			add_filter( 'wp_redirect', array( $this, 'logout_filter_redirect' ), 99, 1 );
			$url = \get_site_url() . '/logout/';
			wp_logout();
			\wp_safe_redirect( $url );
			die();
		}

		$template_id = intval( get_option( self::SETTING_LOGIN_SWITCH_TEMPLATE ) );
		return do_shortcode( '[elementor-template id="' . $template_id . '"]' );
	}

	/**
	 * BuddyPress Profile Redirect Shortcode.
	 *
	 * @return string
	 */
	public function bp_profile_redirect() {
		if ( ! \is_user_logged_in() ) {
			return null;
		}
		$user_id = \get_current_user_id();
		$url     = Factory::get_instance( ElementalBP::class )->get_buddypress_profile_url( $user_id );
		// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
		echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
	}


	/**
	 * Render shortcode to provide login template for Login pages.
	 *
	 * @return string
	 */
	public function logout_filter_redirect() {
		$url = \get_site_url() . '/logout/';
		return $url;
	}

	/**
	 * Plugin Settings Functions
	 * Adds Menu items and listeners to plugin settings tab.
	 */


	/**
	 * Add WCFM Premium Account List.
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_login_template_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Login Template Setting', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="12"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_LOGIN_SWITCH_TEMPLATE ) . '"
		value="' . get_option( self::SETTING_LOGIN_SWITCH_TEMPLATE ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'The Template ID of the Login Template', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. WCFM Update Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_login_template_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_LOGIN_SWITCH_TEMPLATE );
		\update_option( self::SETTING_LOGIN_SWITCH_TEMPLATE, $field );
		$response['feedback'] = \esc_html__( 'Login Template Saved', 'myvideoroom' );
		return $response;
	}
}
