<?php
/**
 * Wrapper for WordPress User functions
 *
 * @package library/class-userhelpers.php
 */

namespace ElementalPlugin\Library;

/**
 * Class UserHelpers
 */
class UserHelpers {

	const PROFILE_MENU_CP_SETTING    = 'elemental-profile-menu-setting';
	const DOCVAULT_MENU_CP_SETTING   = 'elemental-docvault-menu-setting';
	const RESTRICTED_MENU_CP_SETTING = 'elemental-restricted-menu-setting';
	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		// Option for User Logo Landing Page Option PostID.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_profile_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_profile_menu_cp_setting' ), 9, 2 );
		// Option for Docvault Landing Page Option PostID.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_docvault_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_docvault_menu_cp_setting' ), 9, 2 );
		// Option for Restricted Landing Page Option PostID.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_restricted_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_restricted_menu_cp_setting' ), 9, 2 );
	}
	/**
	 * Verify User Exists by Email
	 *
	 * @param string $email - the email to verify.
	 * @return bool
	 */
	public function verify_user_by_email_ajax( string $email ): bool {

		$email_exists = get_user_by( 'email', $email );

		if ( $email_exists ) {
			$available = false;
		} else {
			$available = true;
		}
		return apply_filters( 'elemental_email_available_check', $available );
	}

	/**
	 * Add Profile Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_profile_menu_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Profile Shortcode User Landing Page URL', 'elemental' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::PROFILE_MENU_CP_SETTING ) . '"
		value="' . get_option( self::PROFILE_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the page you want to redirect user profile menu clicks to <br>(used in user menu profile shortcode)', 'elemental' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}
	/**
	 * Process Update Result. Profile Menu CP Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function process_profile_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::PROFILE_MENU_CP_SETTING );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::PROFILE_MENU_CP_SETTING );
		if ( $field !== $current_value ) {
			\update_option( self::PROFILE_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Setting Saved', 'elementalplugin' );
		}
		return $response;
	}

		/**
	 * Add Profile Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_restricted_menu_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Profile Shortcode User Landing Page URL', 'elemental' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::PROFILE_MENU_CP_SETTING ) . '"
		value="' . get_option( self::PROFILE_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the page you want to redirect user profile menu clicks to <br>(used in user menu profile shortcode)', 'elemental' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}
	/**
	 * Process Update Result. Profile Menu CP Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function process_restricted_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::RESTRICTED_MENU_CP_SETTING );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::RESTRICTED_MENU_CP_SETTING );
		if ( $field !== $current_value ) {
			\update_option( self::RESTRICTED_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Setting Saved', 'elementalplugin' );
		}
		return $response;
	}

		/**
	 * Add Access Restricted Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_docvault_menu_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Access Restricted Setting', 'elemental' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::RESTRICTED_MENU_CP_SETTING ) . '"
		value="' . get_option( self::RESTRICTED_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The Access Restricted URL', 'elemental' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}
	/**
	 * Process Update Result. Profile Menu CP Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function process_docvault_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::DOCVAULT_MENU_CP_SETTING );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::DOCVAULT_MENU_CP_SETTING );
		if ( $field !== $current_value ) {
			\update_option( self::DOCVAULT_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Setting Saved', 'elementalplugin' );
		}
		return $response;
	}
}
