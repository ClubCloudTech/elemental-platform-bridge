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
}
