<?php
/**
 * Wrapper for WordPress User functions
 *
 * @package library/class-userhelpers.php
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\Module\UltimateMembershipPro\ElementalUMP;
use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\EmailHelpers;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;

/**
 * Class UserHelpers
 */
class UserHelpers {

	const PROFILE_MENU_CP_SETTING            = 'elemental-profile-menu-setting';
	const DOCVAULT_MENU_CP_SETTING           = 'elemental-docvault-menu-setting';
	const RESTRICTED_MENU_CP_SETTING         = 'elemental-restricted-menu-setting';
	const EMAIL_NOTIFICATION_MENU_CP_SETTING = 'elemental-email-file-notification-menu-setting';
	const CHANGE_PASSWORD_MENU_CP_SETTING    = 'elemental-change-password-menu-setting';
	const LOGIN_ADDRESS_MENU_CP_SETTING      = 'elemental-login-address-menu-setting';
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

		// Option for Sending Email Notifications.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_notification_email_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_notification_email_menu_cp_setting' ), 9, 2 );

		// Option for Sending Email Notifications.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_change_password_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_change_password_menu_cp_setting' ), 9, 2 );

		// Option for Sending Email Notifications.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'process_login_url_menu_cp_setting' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'update_login_url_menu_cp_setting' ), 9, 2 );

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
		<span>' . esc_html__( 'Profile Shortcode User Landing Page URL', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::PROFILE_MENU_CP_SETTING ) . '"
		value="' . get_option( self::PROFILE_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the page you want to redirect user profile menu clicks to <br>(used in user menu profile shortcode)', 'elementalplugin' ) . '"></i>
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
		<span>' . esc_html__( 'Restricted Page URL', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::RESTRICTED_MENU_CP_SETTING ) . '"
		value="' . get_option( self::RESTRICTED_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the page that is Restricted in case of redirect', 'elementalplugin' ) . '"></i>
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
			$response['feedback'] = \esc_html__( 'Restricted Page Setting Saved', 'elementalplugin' );
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
		<span>' . esc_html__( 'Document Vault URL', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::DOCVAULT_MENU_CP_SETTING ) . '"
		value="' . get_option( self::DOCVAULT_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the Document Vault', 'elementalplugin' ) . '"></i>
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
	public function process_change_password_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::CHANGE_PASSWORD_MENU_CP_SETTING );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::CHANGE_PASSWORD_MENU_CP_SETTING );
		if ( $field !== $current_value ) {
			\update_option( self::CHANGE_PASSWORD_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Password Change URL Setting Saved', 'elementalplugin' );
		}
		return $response;
	}
	/**
	 * Add Access Restricted Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_change_password_menu_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Change Password URL', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::CHANGE_PASSWORD_MENU_CP_SETTING ) . '"
		value="' . get_option( self::CHANGE_PASSWORD_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the Change Password Page', 'elementalplugin' ) . '"></i>
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
	public function process_login_url_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::LOGIN_ADDRESS_MENU_CP_SETTING );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::LOGIN_ADDRESS_MENU_CP_SETTING );
		if ( $field !== $current_value ) {
			\update_option( self::LOGIN_ADDRESS_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Login URL Setting Saved', 'elementalplugin' );
		}
		return $response;
	}
	/**
	 * Add Access Restricted Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_login_url_menu_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Login Page URL', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="string" size="64"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::LOGIN_ADDRESS_MENU_CP_SETTING ) . '"
		value="' . get_option( self::LOGIN_ADDRESS_MENU_CP_SETTING ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' The URL of the Login Page', 'elementalplugin' ) . '"></i>
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
			$response['feedback'] = \esc_html__( 'Document Vault Setting Saved', 'elementalplugin' );
		}
		return $response;
	}
	/**
	 * Add Access Restricted Menu Icon Control Panel
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function update_notification_email_menu_cp_setting( array $input ): array {
		if ( 'true' === get_option( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) ) {
			$retrieved_value = 'checked';
		} else {
			$retrieved_value = '';
		}
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Send Email File and Account Notifications', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="checkbox" 
		class="elemental-maintenance-checkbox-setting"
		id="' . esc_attr( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) . '"
		name="' . esc_attr( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) . '"
		value="' . esc_attr( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) . '"
		. ' . $retrieved_value . '>
			<label for="' . esc_attr( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) . '">' . \esc_html__( ' Send Email on account and file events', 'elementalplugin' ) . '</label>
		<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' Send Email Notifications - yes or no', 'elementalplugin' ) . '"></i><br>
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
	public function process_notification_email_menu_cp_setting( array $response ): array {
		$current_value = \get_option( self::EMAIL_NOTIFICATION_MENU_CP_SETTING );
		$field         = strval( Factory::get_instance( Ajax::class )->get_string_parameter( self::EMAIL_NOTIFICATION_MENU_CP_SETTING ) );

		if ( $field !== $current_value ) {
			\update_option( self::EMAIL_NOTIFICATION_MENU_CP_SETTING, $field );
			$response['feedback'] = \esc_html__( 'Setting Saved', 'elementalplugin' );
		}
		return $response;
	}
	/**
	 * Update User Email in WP.
	 *
	 * @param int    $user_id - the user id.
	 * @param string $display_name - the user Display Name to change.
	 * @return bool
	 */
	public function update_display_name( int $user_id, string $display_name ):bool {
		$args    = array(
			'ID'           => $user_id,
			'display_name' => $display_name,
		);
		$success = wp_update_user( $args );
		if ( $success ) {
			return true;
		}
		return false;
	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 *
	 * @param int $user_id - the optional User ID to process.
	 * @return string
	 */
	public function render_user_manage_page( int $user_id = null ): string {
		$this->enqueue_scripts_user_management();
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		} else {
			$user_passed_in = true;
		}

		$encrypted_user_id = Factory::get_instance( Encryption::class )->encrypt_string( \strval( $user_id ) );

		if ( true === $user_passed_in ) {
			$user_object = get_user_by( 'id', $user_id );
		} elseif ( \is_user_logged_in() ) {
			$user_object = wp_get_current_user();
		} else {
			return 'No User Logged in';
		}
		$user_picture = Factory::get_instance( ElementalUMP::class )->get_ump_avatar( $user_id );
		return ( include __DIR__ . '/../views/user/table-user-views.php' )( $encrypted_user_id, $user_object, $user_picture );

	}
	/**
	 * Runs required Scripts to start.
	 *
	 * @return void
	 */
	private function enqueue_scripts_user_management(): void {
		wp_enqueue_style( 'elemental-admin-css' );
		wp_enqueue_script( 'elemental-webcam-stream-js' );
		wp_enqueue_script( 'elemental-protect-username' );
		wp_enqueue_style( 'elemental-template' );
		wp_enqueue_style( 'dashicons' );

	}
	/**
	 * Update User Password in WP.
	 *
	 * @param int    $user_id - the user id.
	 * @param string $password - the Password to update.
	 * @return void
	 */
	public function update_password( int $user_id, string $password ):void {
		wp_set_password( $password, $user_id );
	}

	/**
	 * Reset User Password and communicate.
	 *
	 * @param int    $user_id - the user id.
	 * @param string $password - the new password.
	 * @return void
	 */
	public function reset_password( int $user_id, string $password = null ):void {
		if ( ! $password ) {
			$password = wp_generate_password( 8, true );
		}
		wp_set_password( $password, $user_id );

		// Notify User Password.
		$user            = \get_user_by( 'id', $user_id );
		$email_address   = $user->user_email;
		$welcome_message = esc_html__( 'Password Reset Notification', 'elementalplugin' );
		$subject_line    = \esc_html__( 'Password Reset for your account at ', 'elementalplugin' ) . \get_bloginfo( 'name' );
		$body_message    = esc_html__( 'Your password on ', 'elementalplugin' ) . get_bloginfo( 'name' ) . esc_html__( ' has been changed. The details of the new password are below. ', 'elementalplugin' );
		$detail          = '<br>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body" style="border-collapse: collapse; border-spacing: 0; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; background-color: #f1f1f1; color: #444; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%;"><tr style="padding: 0; vertical-align: top; text-align: left;">
			<thead colspan="2"><h3>' . esc_html__( 'New Password Details', 'elementalplugin' ) . '</h3>
			</thead>
			<tr style="padding: 0; vertical-align: top; text-align: left;"><td><strong>' . esc_html__( 'Username', 'elementalplugin' ) . '</strong></td>
			<td>' . \esc_textarea( $user->user_login ) . '</td></tr>
			<tr style="padding: 0; vertical-align: top; text-align: left;"><td><strong>' . esc_html__( 'Password', 'elementalplugin' ) . '</strong></td>
			<td>' . \esc_textarea( $password ) . '</td></tr>
			
		</table>
		';
		Factory::get_instance( EmailHelpers::class )->send_generic_email( $email_address, $subject_line, $welcome_message, $body_message, $detail );
	}

	/**
	 * Reset User Password and communicate.
	 *
	 * @param int $user_id - the user id.
	 * @return void
	 */
	public function re_invite_user( int $user_id ):void {
		// Find Inviter.
		$parent_id     = Factory::get_instance( MemberSyncDAO::class )->get_parent_by_child( $user_id );
		$parent_object = \get_user_by( 'ID', $parent_id );
		$site_url      = \get_site_url();

		// Notify User.
		$user            = \get_user_by( 'id', $user_id );
		$email_address   = $user->user_email;
		$welcome_message = esc_html__( 'Reminder to Access Your Acccount', 'elementalplugin' );
		$subject_line    = \esc_html__( 'Reminder for you to access your ', 'elementalplugin' ) . \get_bloginfo( 'name' ) . \esc_html__( ' account', 'elemental' );
		$body_message    = esc_html__( 'You have been invited to access the ', 'elementalplugin' ) .
		get_bloginfo( 'name' ) . esc_html__( ' platform by ', 'elementalplugin' ) . \esc_textarea( $parent_object->display_name ) .
		esc_html__( '. You can access the platform at: ', 'elementalplugin' ) . '<br>' . $site_url;
		$detail          = '<br>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="body" style="border-collapse: collapse; border-spacing: 0; vertical-align: top;  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; height: 100% !important; width: 100% !important; min-width: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; -webkit-font-smoothing: antialiased !important; -moz-osx-font-smoothing: grayscale !important; background-color: #f1f1f1; color: #444; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; font-weight: normal; padding: 0; margin: 0; Margin: 0; text-align: left; font-size: 14px; line-height: 140%;"><tr style="padding: 0; vertical-align: top; text-align: left;">
			<thead colspan="2"><h3>' . esc_html__( 'Your Details', 'elementalplugin' ) . '</h3>
			</thead>
			<tr style="padding: 0; vertical-align: top; text-align: left;"><td><strong>' . esc_html__( 'Username', 'elementalplugin' ) . '</strong></td>
			<td>' . \esc_textarea( $user->user_login ) . '</td></tr>
			<tr style="padding: 0; vertical-align: top; text-align: left;"><td><strong>' . esc_html__( 'Password', 'elementalplugin' ) . '</strong></td>
			<td>' . esc_html__( 'Access: ', 'elementalplugin' ) . '<a href="' . $site_url . get_option( self::LOGIN_ADDRESS_MENU_CP_SETTING ) . '">' . esc_html__( 'Here', 'elementalplugin' ) . '</a><br>' .
			esc_html__( 'Change Password: ', 'elementalplugin' ) . '<a href="' . $site_url . get_option( self::CHANGE_PASSWORD_MENU_CP_SETTING ) . '">' . esc_html__( 'Here', 'elementalplugin' ) . '</a></td></tr>
			
		</table>
		';

		Factory::get_instance( EmailHelpers::class )->send_generic_email( $email_address, $subject_line, $welcome_message, $body_message, $detail );
	}
}
