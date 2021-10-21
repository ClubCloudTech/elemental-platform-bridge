<?php
/**
 * Utilities for WCFM
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WCFM;

/**
 * Class WCFM Search
 */
class WCFMTools {

	/**
	 * Get WCFM Membership Levels.
	 *
	 * @param bool $id_only Flag on returning only ID not entire object.
	 * @return ?array
	 */
	public function elemental_get_wcfm_memberships( bool $id_only = null ): ?array {

		$args              = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'wcfm_memberships',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => array( 'draft', 'pending', 'publish' ),
			'suppress_filters' => true,
		);
		$wcfm_groups_array = get_posts( $args );

		if ( $id_only && $wcfm_groups_array ) {
			$return_array = array();
			foreach ( $wcfm_groups_array as $level ) {
				\array_push( $return_array, intval( $level->ID ) );
			}
			return $return_array;
		}
		if ( $wcfm_groups_array ) {
			return $wcfm_groups_array;
		} else {
			return null;
		}
	}

	/**
	 * Get User Members of a Group ID
	 *
	 * @param ?int $membership_id ID Of members.
	 * @return ?array
	 */
	public function elemental_show_user_members( int $membership_id ): ?array {
		$membership_users = (array) get_post_meta( $membership_id, 'membership_users', true );
		if ( $membership_users ) {
			return $membership_users;
		} else {
			return null;
		}
	}

	/**
	 * Check if ID is a member of a WCFM group.
	 *
	 * @param ?int $membership_id ID Of members.
	 * @param ?int $group_id ID Of group to check.
	 * @return ?bool
	 */
	public function elemental_is_user_a_member( int $membership_id, int $group_id ): bool {
		$group_members = $this->elemental_show_user_members( $group_id );
		if ( ! $group_members ) {
			return false;
		}

		foreach ( $group_members as $member ) {
			if ( $member === $membership_id ) {
				return true;
			}
		}
		return false;

	}

	/**
	 * Get Store Memberships.
	 *
	 * @param int $store_id the StoreID to Check.
	 * @return ?bool
	 */
	public function elemental_get_store_memberships( int $store_id ): ?array {
		$memberships       = $this->elemental_get_wcfm_memberships( true );
		$membership_output = array();
		foreach ( $memberships as $membership ) {
			$true = $this->elemental_is_user_a_member( $store_id, $membership );
			if ( $true ) {
				array_push( $membership_output, $membership );
			}
		}
		if ( $membership_output ) {
			return $membership_output;
		} else {
			return false;
		}
	}


}
