<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package ElementalPlugin\Membership\Library\class-membershipUMP.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class MembershipUMP {

	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param int $user_id User ID to check (current user if blank).
	 *
	 * @return ?string
	 */
	public function highest_user_membership_quota( int $user_id = null ): ?int {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		// Get all levels valid.
		$levels_object = \IHC_db::get_user_levels( $user_id, true );
		$quota         = 0;
		foreach ( $levels_object as $level ) {
			$level_quota = Factory::get_instance( MembershipDAO::class )->get_limit_by_membership( $level['level_id'] );
			if ( $level_quota > $quota ) {
				$quota = $level_quota;
			}
		}
		return $quota;
	}

	/**
	 * Remaining User Child account available.
	 *
	 * @param int $user_id User ID to check (current user if blank).
	 *
	 * @return ?string
	 */
	public function child_account_available_number( int $user_id = null ): ?int {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		// Get Max Quota.
		$quota          = $this->highest_user_membership_quota( $user_id );
		$currently_used = $this->accounts_user_created( $user_id );
		if ( $quota > $currently_used ) {
			return $quota - $currently_used;
		} else {
			return 0;
		}
	}
	/**
	 * Remaining User Child account available.
	 *
	 * @param int $user_id User ID to check (current user if blank).
	 *
	 * @return ?string
	 */
	public function accounts_user_created( int $user_id = null ): ?int {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}
		// Placeholder
		return 10;

	}

}
