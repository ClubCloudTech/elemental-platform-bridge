<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package ElementalPlugin\Membership\Library\class-membershipUMP.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;

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
		$levels_object = \IHC_db::get_user_levels( $user_id, true );

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

	/**
	 * Get the list of Subscription Levels
	 *
	 * @return array
	 */
	public function get_ump_memberships() :array {
		$ihc_data          = get_option( 'ihc_levels' );
		$membership_levels = array_keys( $ihc_data );
		$return_array      = array();

		foreach ( $membership_levels as $level => $value ) {
			$record_array               = array();
			$record_array['level']      = $value;
			$record_array['label']      = $ihc_data[ $value ]['label'];
			$record_array['badge_url']  = $ihc_data[ $value ]['badge_image_url'];
			$record_array['price_text'] = $ihc_data[ $value ]['price_text'];
			$record_array['limit']      = Factory::get_instance( MembershipDAO::class )->get_limit_by_membership( intval( $value ) );

			\array_push( $return_array, $record_array );
		}
		return $return_array;
	}
}
