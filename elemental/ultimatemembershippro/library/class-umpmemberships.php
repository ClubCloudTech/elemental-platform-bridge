<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package elemental/ultimatemembershippro/library/class-umpmemberships.php
 */

namespace ElementalPlugin\UltimateMembershipPro\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\UltimateMembershipPro\DAO\ElementalUMPDAO;

/**
 * Class UMPMemberships - Classes for UMP
 */
class UMPMemberships {

	/**
	 * Get the list of Subscription Levels
	 *
	 * @param int $membership_id - the ump membership ID.
	 * @return array
	 */
	public function get_ump_memberships( int $membership_id = null ) :array {

		$ihc_data          = get_option( 'ihc_levels' );
		$membership_levels = array_keys( $ihc_data );
		$return_array      = array();
		foreach ( $membership_levels as $level => $value ) {
			if ( ! $membership_id || Factory::get_instance( ElementalUMPDAO::class )->translate_ump_level_to_wc( $value ) === $membership_id ) {
				$record_data                      = Factory::get_instance( MembershipDAO::class )->get_limit_info( intval( $value ) );
				$record_array                     = array();
				$record_array['level']            = $value;
				$record_array['wcfm_level']       = Factory::get_instance( ElementalUMPDAO::class )->translate_ump_level_to_wc( $value );
				$record_array['label']            = $ihc_data[ $value ]['label'];
				$record_array['badge_url']        = $ihc_data[ $value ]['badge_image_url'];
				$record_array['price_text']       = $ihc_data[ $value ]['price_text'];
				$record_array['limit']            = $record_data->user_limit;
				$record_array['template']         = $record_data->template;
				$record_array['landing_template'] = $record_data->landing_template;
				\array_push( $return_array, $record_array );
			}
		}
		return $return_array;
	}

	/**
	 * Get Login Template by UMP Membership
	 *
	 * @param int $membership_id - the ump membership ID.
	 * @return ?int
	 */
	public function get_landing_template_by_ump_membership( int $membership_id ) :?int {
		$object = Factory::get_instance( MembershipDAO::class )->get_limit_info( $membership_id );
		return $object->landing_template;
	}

	/**
	 * Get Login Template for a User
	 *
	 * @param int $user_id - the user ID the template is for.
	 * @return ?int
	 */
	public function get_landing_template_for_a_user( int $user_id = null ) :?int {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$my_membership = Factory::get_instance( ElementalUMPDAO::class )->get_active_user_membership_levels( $user_id );
		return $this->get_landing_template_by_ump_membership( intval( array_key_first( $my_membership ) ) );
	}



}
