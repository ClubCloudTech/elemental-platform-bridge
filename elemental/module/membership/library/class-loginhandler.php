<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-loginhandler.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\Membership\Onboard;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\HttpGet;
use ElementalPlugin\Module\BuddyPress\ElementalBP;
use ElementalPlugin\Module\Menus\ElementalMenus;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;


/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class LoginHandler {

	const SHORTCODE_LOGOUT_SWITCH  = 'elemental_logout';
	const SHORTCODE_LOGOUT         = 'elemental_logout_url';
	const SHORTCODE_LEGACY_LOGOUT  = 'cclogout';
	const SHORTCODE_LEGACY_LOGIN   = 'ccloginswitch';
	const SHORTCODE_LOGIN_SWITCH   = 'elemental_login';
	const SHORTCODE_LOGIN_VIEW     = 'elemental_login_view';
	const SHORTCODE_LOGIN_BUTTON   = 'elemental_loginbutton';
	const SHORTCODE_LOGIN_REDIRECT = 'elemental_profile_redirect';

	const SETTING_LOGIN_SWITCH_TEMPLATE           = 'elemental-login-switch-template';
	const SETTING_CHECKOUT_HEADER_SWITCH_TEMPLATE = 'elemental-checkout-header-template';

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_LOGOUT_SWITCH, array( $this, 'render_logout_shortcode' ) );
		add_shortcode( self::SHORTCODE_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LOGIN_SWITCH, array( $this, 'elemental_loginswitch' ) );
		add_shortcode( self::SHORTCODE_LOGIN_VIEW, array( $this, 'elemental_login_view' ) );
		add_shortcode( self::SHORTCODE_LOGIN_BUTTON, array( $this, 'elemental_login_out' ) );
		\add_shortcode( self::SHORTCODE_LOGIN_REDIRECT, array( $this, 'loginland_redirect' ) );

		// Legacy Shortcodes.
		add_shortcode( self::SHORTCODE_LEGACY_LOGOUT, array( $this, 'elemental_logout' ) );
		add_shortcode( self::SHORTCODE_LEGACY_LOGIN, array( $this, 'elemental_loginswitch' ) );

		// Option for Login Switch Plugin Setting.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_login_template_settings' ), 5, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_login_template_setting' ), 5, 2 );

		// Option for Checkout Header Template.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_checkout_header_template_settings' ), 5, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_checkout_header_template_setting' ), 5, 2 );
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
			die();
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
	 * Login/Out Button Generation Shortcode.
	 * Renders Login/Out or admin mode buttons and redirects.
	 * Starts by understanding if there are sufficient staff accounts, if not - forces their creation
	 *
	 * @param string $type = the type of shortcode to return, login = login only, role = role switch only, all or null is both.
	 *
	 * @return string
	 */
	public function elemental_login_out( string $type ): string {
		$output = '';
		// Get Identities.
		$is_vendor = Factory::get_instance( UserRoles::class )->is_wcfm_vendor();
		$is_staff  = Factory::get_instance( UserRoles::class )->is_wcfm_shop_staff();

		$is_setup = Factory::get_instance( Onboard::class )->decode_setup_cookie();
		// Case recently created first time vendor with setup cookie- adding child admin account.
		if ( $is_vendor && $is_setup ) {

			$staff_id = Factory::get_instance( WCFMTools::class )->elemental_get_staff_member_ids();

			if ( count( $staff_id ) === 1 ) {
				$this->create_user_child_cookie( $staff_id[0] );
				$url = \get_site_url() . '/control/';
				Factory::get_instance( Onboard::class )->delete_setup_cookie();
				// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
				echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
				die();
			}
		}

		// Case Time Vendor - need to check if staff/admin accounts exist and if not go to admin account add.
		if ( $is_vendor ) {
			// Decide Correct Redirect Path based on Staff Account Count.
			$staff_count = Factory::get_instance( WCFMTools::class )->elemental_get_staff_member_count();
			if ( $staff_count >= 1 ) {
				$child_id = $this->decode_user_child_cookie();
				if ( $child_id ) {
					$noncechild = \wp_create_nonce( 'child' );
					$url        = get_site_url() . '/login?action=child&noncechild=' . $noncechild;
					$output    .= '<a href="' . $url . '" class="elemental-host-link">' . esc_html__( 'Exit Admin Mode', 'myvideoroom' ) . '</a>';
				}
			} else {

				// There are Insufficient Admin Accounts - so redirect to Admin Account Addin page.
				$url = \get_site_url() . '/control/manage-accounts/firstadmin/';
				// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
				echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
				die();
			}
		}
		// Case Normal Staff Member - need to encode user id in cookie so Parent account knows which user to return to.
		if ( $is_staff ) {
			$atts        = array(
				'type' => 'text',
			);
			$org_name    = Factory::get_instance( ElementalMenus::class )->render_header_logo_shortcode( $atts );
			$nonceparent = \wp_create_nonce( 'parent' );
			$url         = get_site_url() . '/login?action=parent&nonceparent=' . $nonceparent;
			$output     .= '<a href="' . $url . '" class="elemental-host-link">' . esc_html__( 'Switch to ', 'myvideoroom' ) . $org_name . ' Admin</a>';
		}
		if ( 'role' === $type ) {
			return $output;
		} elseif ( 'login' === $type ) {
			$output = null;
		}

		if ( \is_user_logged_in() ) {
			$nonce   = \wp_create_nonce( 'logout' );
			$url     = get_site_url() . '/login?action=logout&nonce=' . $nonce;
			$output .= '<a href="' . $url . '" class="elemental-host-link">' . esc_html__( 'Sign Out', 'myvideoroom' ) . '</a>';
		} else {
			// Redirect- non-logged in users looking at profiles.
			if ( \function_exists( 'bp_displayed_user_id' ) && bp_displayed_user_id() ) {
				$url = \get_site_url() . '/access-restricted/';
				// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
				echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
				die();
			}

			$output .= ' <a class="elemental-host-link" href="' . \get_site_url() . '/login" >' . esc_html__( 'Login', 'myvideoroom' ) . '</a><a class="elemental-host-link elemental-buttonlink-border" href="' . \get_site_url() . '/join" >' . esc_html__( 'Join', 'myvideoroom' ) . '</a>';
		}
		return $output;
	}

	/**
	 * Login Hook
	 * Renders hook handler to manage logins.
	 *
	 * @return string
	 */
	public function elemental_login_switch_hook() {

		$http_get_library = Factory::get_instance( HttpGet::class );
		$action           = $http_get_library->get_string_parameter( 'action' );
		$nonce            = $http_get_library->get_string_parameter( 'nonce' );
		if ( ! $action && ! $nonce ) {
			return;
		}

		$is_staff = Factory::get_instance( UserRoles::class )->is_wcfm_shop_staff();

		if ( 'logout' === $action && \wp_verify_nonce( $nonce, 'logout' ) ) {
			add_filter( 'wp_redirect', array( $this, 'logout_filter_redirect' ), 99, 1 );
			$url = \get_site_url() . '/logout/';
			wp_logout();
			$this->delete_child_cookie();
			\wp_safe_redirect( $url );
			die();
		}
		$noncechild = $http_get_library->get_string_parameter( 'noncechild' );
		if ( 'child' === $action && \wp_verify_nonce( $noncechild, 'child' ) ) {
			Factory::get_instance( WCFMTools::class )->login_to_child_account();
			$template = Factory::get_instance( UMPMemberships::class )->get_landing_template_for_a_user();
			$url      = get_permalink( $template );
			\wp_safe_redirect( $url );
			die();
		}
		// Login to Parent Account if coming from Child with Correct Security.
		$nonceparent = $http_get_library->get_string_parameter( 'nonceparent' );
		if ( 'parent' === $action && \wp_verify_nonce( $nonceparent, 'parent' ) ) {
			Factory::get_instance( WCFMTools::class )->login_to_parent_account();
			$template = Factory::get_instance( UMPMemberships::class )->get_landing_template_for_a_user();
			$url      = get_permalink( $template );
			if ( ! $template ) {
				esc_html_e( 'No Site Template for this subscription, Or subscription invalid', 'my-video-room' );
			} else {
				\wp_safe_redirect( $url );
			}
			die();
		}

		$is_vendor = Factory::get_instance( UserRoles::class )->is_wcfm_vendor();
		if ( $is_vendor ) {
			// Decide Correct Redirect Path based on Staff Account Count.
			$staff_count = Factory::get_instance( WCFMTools::class )->elemental_get_staff_member_count();
			if ( $staff_count >= 1 ) {
				$url = \get_site_url() . '/control/';
			} else {
				$url = \get_site_url() . '/control/manage-accounts/firstadmin/';
			}
			\wp_safe_redirect( $url );
			die();

		}
	}


	/**
	 * Add Staff Acount Cookie
	 * Renders hook handler to manage logins.
	 *
	 * @param string $user_login - not used.
	 * @param object $user_object - The passed in object from the hook.
	 *
	 * @return void
	 */
	public function elemental_add_staff_account_cookie_hook( string $user_login, object $user_object ):void {

		$is_staff = Factory::get_instance( UserRoles::class )->is_wcfm_shop_staff( $user_object->ID );

		if ( $is_staff ) {
			$this->create_user_child_cookie( $user_object->ID );
		}

	}

	/**
	 * Login Switch
	 * Renders the shortcode to correctly login and out users, and handle admin/child context switches for vendors.
	 *
	 * @return string
	 */
	public function elemental_loginview(): string {

		
		return do_shortcode( '[elementor-template id="' . $template_id . '"]' );
	}

	/**
	 * Login Switch
	 * Renders the shortcode to correctly login and out users, and handle admin/child context switches for vendors.
	 *
	 * @return string
	 */
	public function elemental_loginswitch(): string {

		$template_id = intval( get_option( self::SETTING_LOGIN_SWITCH_TEMPLATE ) );
		return do_shortcode( '[elementor-template id="' . $template_id . '"]' );
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

	/**
	 * Add WCFM Premium Account List.
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_checkout_header_template_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Checkout Header Template Setting', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="12"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_CHECKOUT_HEADER_SWITCH_TEMPLATE ) . '"
		value="' . get_option( self::SETTING_CHECKOUT_HEADER_SWITCH_TEMPLATE ) . '">
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
	public function update_checkout_header_template_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_CHECKOUT_HEADER_SWITCH_TEMPLATE );
		\update_option( self::SETTING_CHECKOUT_HEADER_SWITCH_TEMPLATE, $field );
		$response['feedback'] = \esc_html__( 'Checkout Header Template Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Create Cookie to Return to from Admin Mode. WCFM Update Setting.
	 *
	 * @param int $user_id - inbound userID to encode into cookie.
	 * @return void
	 */
	public function create_user_child_cookie( int $user_id = null ): void {

		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}
		$hash        = Factory::get_instance( Encryption::class )->get_meeting_hash_from_user_id( $user_id );
		$time_offset = 60 * 60 * 36;
		unset( $_COOKIE['staffsessionid'] );
		setcookie( 'staffsessionid', $hash, time() + $time_offset, '/' );
	}

	/**
	 * Create Cookie to Return to from Admin Mode. WCFM Update Setting.
	 *
	 * @return void
	 */
	public function delete_child_cookie(): void {
		unset( $_COOKIE['staffsessionid'] );
		setcookie( 'staffsessionid', null, time() - 1000, '/' );
	}

	/**
	 * Create Cookie to Return to from Admin Mode. WCFM Update Setting.
	 *
	 * @return ?int
	 */
	public function decode_user_child_cookie(): ?int {
		if ( ! \is_user_logged_in() || ! isset( $_COOKIE['staffsessionid'] ) ) {
			return null;
		}
		$hash    = sanitize_key( $_COOKIE['staffsessionid'] );
		$user_id = Factory::get_instance( Encryption::class )->get_user_id_from_meeting_hash( $hash );
		if ( $user_id ) {
			return $user_id;
		} else {
			return null;
		}
	}

	/**
	 * BuddyPress Profile Redirect Shortcode for Login Landing Page.
	 * Redirects a user landing in this page to the Users Buddypress ID.
	 *
	 * @return string
	 */
	public function loginland_redirect() {

		$template = Factory::get_instance( UMPMemberships::class )->get_landing_template_for_a_user();
		if ( $template ) {
			$url = get_permalink( $template );
			echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
			die();
		}

		if ( ! \is_user_logged_in() ) {
			return null;
		}
		if ( Factory::get_instance( ElementalBP::class )->is_buddypress_available() ) {
			return Factory::get_instance( ElementalBP::class )->bp_profile_redirect();
		}
	}
}
