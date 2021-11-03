<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package elemental/ultimatemembershippro/library/class-umpmemberships.php
 */

namespace ElementalPlugin\UltimateMembershipPro\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class UMPMemberships {

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
