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
	 * Get Session ID.
	 *
	 * @param ?int $user_id The user id. (optional).
	 *
	 * @return string the session ID of the user.
	 */
	public function get_user_session( int $user_id = null ): string {

		if ( $user_id ) {
			return wp_hash( $user_id );

		} elseif ( is_user_logged_in() ) {

			return wp_hash( get_current_user_id() );
		} else {

			// Get php session hash.
			if ( ! session_id() ) {
				session_start();
			}
			return session_id();
		}
	}
}
