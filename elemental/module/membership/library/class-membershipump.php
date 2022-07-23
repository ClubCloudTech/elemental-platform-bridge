<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package ElementalPlugin\Module\Membership\Library\class-membershipUMP.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Module\Membership\DAO\MembershipDAO;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\UltimateMembershipPro\DAO\ElementalUMPDAO;

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
		$quota = 0;
		if ( ! class_exists( 'IHC_db' ) ) {
			return $quota;
		}

		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		// Get all levels valid.
		$levels_object = Factory::get_instance( ElementalUMPDAO::class )->get_active_user_membership_levels( $user_id );

		// Check each Level Quota.
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
		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
			return 434;
		}
		// Get Max Quota.
		$quota          = $this->highest_user_membership_quota( $user_id );
		$currently_used = Factory::get_instance( MemberSyncDAO::class )->get_child_count( $user_id );
		if ( $quota > $currently_used ) {
			return $quota - $currently_used;
		} else {
			return 0;
		}
	}
}
