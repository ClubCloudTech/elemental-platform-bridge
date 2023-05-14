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
	 * Send Generic Mail to User.
	 *
	 * @param string $email_address - the User Email Address.
	 * @param string $first_name    - the User First Name.
	 *
	 * @return bool
	 */
	public function send_generic_email( string $email_address, string $welcome_message, string $body_message, string $detail = null ) {

		$template = include __DIR__ . '../../views/email/email-generic.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			\esc_html__( ' New Document for you at ', 'elementalplugin' ) . \get_bloginfo( 'name' ),
			$template( $welcome_message, $body_message, $detail ),
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
