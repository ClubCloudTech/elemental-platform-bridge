<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-loginhandler.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\HttpGet;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class LoginHandler {

	const SHORTCODE_LOGOUT_SWITCH = 'elemental_logout';
	const SHORTCODE_LOGOUT        = 'elemental_logout_url';

	const SHORTCODE_LEGACY_LOGOUT = 'cclogout';
	const SHORTCODE_LEGACY_LOGIN  = 'ccloginswitch';
	const SHORTCODE_LOGIN_SWITCH  = 'elemental_login';

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
		$template_id = intval( get_option( self::SETTING_LOGIN_SWITCH_TEMPLATE ) );
		return do_shortcode( '[elementor-template id="' . $template_id . '"]' );
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
