<?php
/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 *
 * @package wcfm/library/class-wcfmshortcodes.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\WCFMTools;

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 */
class WCFMShortcodes {

	const SHORTCODE_SHOW_STAFF = 'elemental_show_staff';

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_SHOW_STAFF, array( $this, 'show_wcfm_staff' ) );
	}

	/**
	 * Show WCFM Staff.
	 *
	 * @return string
	 */
	public function show_wcfm_staff(): string {
		global $WCFM;

		$vendor_id = Factory::get_instance( WCFMTools::class )->get_wcfm_page_owner();
			global $WCFM;
			$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
			$args            = array(
				'role__in'    => array( $staff_user_role ),
				'orderby'     => 'ID',
				'order'       => 'ASC',
				'offset'      => 0,
				'number'      => -1,
				'meta_key'    => '_wcfm_vendor',
				'meta_value'  => $vendor_id,
				'count_total' => false,
			);

			$wcfm_staff      = get_users( $args );
			$result_count    = count( $wcfm_staff );
			$shop_staff_html = '';

			if ( $result_count >= 1 ) {
				$is_first = true;
				foreach ( $wcfm_staff as $wcfm_staff_member ) {
					if ( ! $is_first ) {
						$shop_staff_html .= ', ';
					}
					$shop_staff_html .= $wcfm_staff_member->ID;
					$is_first         = false;
				}
				return do_shortcode( '[youzify_members include="' . $shop_staff_html . '" ]' );
			}
			return '<h1>' . esc_html__( 'This Organisation has no Member Accounts', 'myvideoroom' ) . '</h1>';
	}
}
