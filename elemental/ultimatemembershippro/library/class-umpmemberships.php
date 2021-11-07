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
				$record_data                = Factory::get_instance( MembershipDAO::class )->get_limit_info( intval( $value ) );
				$record_array               = array();
				$record_array['level']      = $value;
				$record_array['wcfm_level'] = Factory::get_instance( ElementalUMPDAO::class )->translate_ump_level_to_wc( $value );
				$record_array['label']      = $ihc_data[ $value ]['label'];
				$record_array['badge_url']  = $ihc_data[ $value ]['badge_image_url'];
				$record_array['price_text'] = $ihc_data[ $value ]['price_text'];
				$record_array['limit']      = $record_data->user_limit;
				$record_array['template']   = $record_data->template;

				\array_push( $return_array, $record_array );
		}
		return $return_array;
	}
}
