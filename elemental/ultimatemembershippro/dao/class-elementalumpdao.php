<?php
/**
 * Database Access Object for Elemental Accessing Ultimate Membership Pro DB
 *
 * @package ultimatemembershippro/dao/class-elementalumpdao.php
 */

namespace ElementalPlugin\UltimateMembershipPro\DAO;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\WCFMTools;

/**
 * Class ElementalUMP
 * Supports Ultimate Membership Pro Functions.
 */
class ElementalUMPDAO {

	/**
	 * Get Table Name of IHC Membership Table.
	 *
	 * @return string
	 */
	private function get_table_name():string {
		global $wpdb;
		return $wpdb->prefix . 'ihc_user_levels';
	}

	/**
	 * Get Ultimate Membership Pro Membership Levels (unfiltered)
	 *
	 * @param int $user_id - the user id to check.
	 * @return array
	 */
	public function get_active_user_membership_levels( int $user_id ): ?array {
		if ( \class_exists( 'Ihc_Db' ) ) {
			return \IHC_db::get_user_levels( $user_id, true );
		} else {
			return null;
		}
	}

	/**
	 * Is User Level Active
	 * Note this function does not check expiry times.
	 *
	 * @param int $user_id - the user id to check.
	 * @param int $level_id - the level id to check.
	 * @return bool
	 */
	public function is_membership_active( int $user_id, int $level_id ): bool {

		$user_levels = get_user_meta( $user_id, 'ihc_user_levels', true );
		if ( $user_levels ) {
			$levels = explode( ',', $user_levels );
			if ( isset( $levels ) && count( $levels ) && in_array( $level_id, $levels ) && \function_exists( 'ihc_get_start_expire_date_for_user_level' ) && \function_exists( 'indeed_get_unixtimestamp_with_timezone' ) ) {
				$user_time = ihc_get_start_expire_date_for_user_level( $user_id, $level_id );
				if ( strtotime( $user_time['expire_time'] ) > indeed_get_unixtimestamp_with_timezone() ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Get All Active Memberships in UMP
	 *
	 * @param int  $user_id - the user id to check.
	 * @param bool $id_only - return just the membership id and not whole object.
	 * @return array - sorted by premium accounts first.
	 */
	public function get_all_active_ump_levels( int $user_id, bool $id_only = null ): array {
		$user_levels  = $this->get_active_user_membership_levels( $user_id );
		$return_array = array();
		foreach ( $user_levels as $level ) {
			$is_premium = Factory::get_instance( WCFMTools::class )->elemental_am_i_premium( $user_id );
			if ( $id_only ) {
				if ( $is_premium ) {
					\array_unshift( $return_array, $level['level_id'] );
				} else {
					array_push( $return_array, $level['level_id'] );
				}
			} else {
				if ( $is_premium ) {
					\array_unshift( $return_array, $level );
				} else {
					array_push( $return_array, $level );
				}
			}
		}
		return $return_array;
	}

	/**
	 * Returns UMP Role to WCFM Role mapping.
	 *
	 * @param int $ump_level - the UMP level to check.
	 * @return ?int
	 */
	public function translate_ump_level_to_wc( int $ump_level ) : ?int {
		if ( ! \class_exists( 'Ihc_Db' ) ) {
			return null;
		}
		$product_id = \Ihc_Db::get_woo_product_id_for_lid( $ump_level );
		return \intval( $product_id );
	}


}


