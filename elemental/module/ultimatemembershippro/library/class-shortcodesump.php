<?php
/**
 * Membership Ultimate Membership Pro Handlers
 *
 * @package elemental/ultimatemembershippro/library/class-shortcodesump.php
 */

namespace ElementalPlugin\Module\UltimateMembershipPro\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Library\HttpGet;
use ElementalPlugin\Module\Membership\Membership;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class ShortCodesUMP {

	/**
	 * Render shortcode to Display Membership Name.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?mixed
	 */
	public function render_level_name( $attributes = array() ): mixed {
		$membership_id = $attributes['id'];
		$type          = $attributes['type'];
		// Get Membership ID from URL if not passed into SC.
		if ( ! $membership_id ) {
			$http_get_library = Factory::get_instance( HttpGet::class );
			$membership_id    = $http_get_library->get_string_parameter( 'membership' );
			if ( Membership::MEMBERSHIP_INDIVIDUAL_ID === $membership_id ) {
				return \esc_html__( 'Individual Account', 'elemental' );
			} else {
				$membership_id = intval( $http_get_library->get_string_parameter( 'membership' ) );
			}
		}

		// Check UMP for Level First.
		if ( Factory::get_instance( UMPMemberships::class )->is_a_ump_subscription( $membership_id ) ) {
			$ump_data          = Factory::get_instance( UMPMemberships::class )->get_ump_memberships( $membership_id );
			$level_label       = $ump_data[0]['label'];
			$level_description = $ump_data[0]['description'];
		} else {
			$level_info_array = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( null, $membership_id );
			if ( ! $level_info_array ) {
				// If not UMP send to WooComm Subscription Product.
				return $this->render_product_sub_name( $membership_id );
			} else {
				$level_info        = $level_info_array[0];
				$level_label       = $level_info->post_title;
				$level_description = $level_info->post_excerpt;
			}
		}

		switch ( $type ) {

			case 'label':
				return $level_label;
			case 'description':
				return $level_description;
			case 'all':
				return $ump_data[0];
			default:
				return $level_label;
		}
	}

	/**
	 * Render Product Subscription Name from WC.
	 *
	 * @param int    $product_id -WooCommerce Product ID.
	 * @param string $type - Type of Record to Return.
	 *
	 * @return ?string
	 */
	public function render_product_sub_name( int $product_id, string $type = null ): ?string {

		if ( \function_exists( 'wc_get_product' ) ) {
			$product = \wc_get_product( $product_id );
			if ( ! $product ) {
				return null;
			}
		} else {
			return null;
		}

		switch ( $type ) {

			case 'label':
				return $product->get_name();
			case 'description':
				return $product->get_description();
			default:
				return $product->get_name();
		}
	}
}
