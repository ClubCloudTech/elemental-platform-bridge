<?php
/**
 * Helper for Email functions
 *
 * @package library/class-emailhelpers.php
 */

namespace ElementalPlugin\Library;

/**
 * Class EmailHelpers.
 */
class EmailHelpers {

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
	 * Update User Email in WP.
	 *
	 * @param int    $user_id - the user id.
	 * @param string $user_email - the user email to change.
	 * @return bool
	 */
	public function update_user_email( int $user_id, string $user_email ):bool {
		$user_already_exists = \get_user_by( 'user_email', $user_email );
		if ( $user_already_exists ) {
			return false;
		}
		$args    = array(
			'ID'         => $user_id,
			'user_email' => $user_email,
		);
		$success = wp_update_user( $args );
		if ( $success ) {
			return true;
		}
		return false;
	}

	/**
	 * Send Generic Mail to User.
	 *
	 * @param string $email_address - the User Email Address.
	 * @param string $subject_line  - Email Subject Line.
	 * @param string $welcome_message  - Top Message.
	 * @param string $body_message  - Main Message Body.
	 * @param string $detail  - Detail to Send.
	 *
	 * @return bool
	 */
	public function send_generic_email( string $email_address, string $subject_line, string $welcome_message, string $body_message, string $detail = null ):bool {
		$setting = get_option( UserHelpers::EMAIL_NOTIFICATION_MENU_CP_SETTING );
		if ( 'true' !== $setting ) {
			return false;
		}
		$current_url_setting = \get_option( UserHelpers::IMAGE_URL_MENU_CP_SETTING );
		if ( $current_url_setting ) {
			$logo_image_url = $current_url_setting;
		} else {
			$logo_image_url = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
		}

		$template = include __DIR__ . '../../views/email/email-generic.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			$subject_line,
			$template( $welcome_message, $body_message, $detail, $logo_image_url ),
			$headers
		);
		return $status;
	}

	/**
	 * Send Document Change Notification Mail to New User.
	 *
	 * @param string $email_address - the User Email Address.
	 * @param string $first_name    - the User First Name.
	 *
	 * @return bool
	 */
	public function notify_user_file_change( string $email_address, string $first_name ) {
		$setting = get_option( UserHelpers::EMAIL_NOTIFICATION_MENU_CP_SETTING );
		if ( 'true' !== $setting ) {
			return false;
		}

		$template = include __DIR__ . '/../views/email/file-change-email-template.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			\esc_html__( ' New Document for you at ', 'elementalplugin' ) . \get_bloginfo( 'name' ),
			$template( $first_name ),
			$headers
		);
		return $status;
	}
}
