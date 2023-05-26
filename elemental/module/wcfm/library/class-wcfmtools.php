<?php
/**
 * Utilities for WCFM
 *
 * @package wcfm/library/class-wcfmtools.php
 */

namespace ElementalPlugin\Module\WCFM\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Library\LoginHandler;

/**
 * Class WCFM Search
 */
class WCFMTools {

	const SHORTCODE_DISPLAY_PRODUCT = 'wcfm_store_products';

	/**
	 * Runtime Shortcodes
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_DISPLAY_PRODUCT, array( $this, 'display_products' ) );

	}

	/**
	 * Get WCFM Membership Levels.
	 *
	 * @param bool $id_only Flag on returning only ID not entire object.
	 * @param int  $post_id -  Post ID of a Single Membership level to retrieve (optional).
	 * @return ?array
	 */
	public function elemental_get_wcfm_memberships( bool $id_only = null, int $post_id = null ): ?array {

		if ( ! $post_id ) {
			$args = array(
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
		} else {
			$args = array(
				'posts_per_page'   => -1,
				'offset'           => 0,
				'category'         => '',
				'category_name'    => '',
				'orderby'          => 'date',
				'order'            => 'DESC',
				'include'          => array( $post_id ),
				'exclude'          => '',
				'meta_key'         => '',
				'meta_value'       => '',
				'post_type'        => 'wcfm_memberships',
				'post_mime_type'   => '',
				'post_parent'      => '',
				'post_status'      => array( 'draft', 'pending', 'publish' ),
				'suppress_filters' => true,
			);
		}

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
	 * Get the Membership Program Information for all Memberships a user has
	 *
	 * @param ?int $user_id - User ID that you want to check. (must be a merchant).
	 * @return ?array with the information of group (including name, URL, etc).
	 */
	public function elemental_get_membership_data( int $user_id ): ?array {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
			if ( $user_id ) {
				return null;
			}
		}
		$memberships  = $this->elemental_get_store_memberships( $user_id );
		$return_array = array();
		foreach ( $memberships as $membership ) {
			array_push( $return_array, $this->elemental_get_wcfm_memberships( null, $membership ) );
		}
		return $return_array;
	}

	/**
	 * Get Staff Member Count of a Store Owner
	 *
	 * @return ?int
	 */
	public function elemental_get_staff_member_count( int $user_id = null ): ?int {
		if ( ! $user_id ) {
			$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		}

		$staff_user_role  = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
		$args             = array(
			'role__in'    => array( $staff_user_role ),
			'orderby'     => 'ID',
			'order'       => 'ASC',
			'offset'      => 0,
			'number'      => -1,
			'count_total' => false,
			'meta_key'    => '_wcfm_vendor',
			'meta_value'  => $user_id,
		);
		$wcfm_staff_array = get_users( $args );
		$staff_count      = count( $wcfm_staff_array );
		if ( $staff_count ) {
			return $staff_count;
		} else {
			return null;
		}
	}

	/**
	 * Get Staff Member ID's of a Store Owner
	 *
	 * @return ?array of Staff ID's
	 */
	public function elemental_get_staff_member_ids(): ?array {
		$current_user_id  = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$staff_user_role  = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
		$args             = array(
			'role__in'    => array( $staff_user_role ),
			'orderby'     => 'ID',
			'order'       => 'ASC',
			'offset'      => 0,
			'number'      => -1,
			'count_total' => false,
			'meta_key'    => '_wcfm_vendor',
			'meta_value'  => $current_user_id,
		);
		$wcfm_staff_array = get_users( $args );
		$output           = array();
		foreach ( $wcfm_staff_array as $item ) {
			array_push( $output, $item->ID );
		}
		if ( $output ) {
			return $output;
		} else {
			return null;
		}
	}

	/**
	 * Get User Members of a Group ID
	 *
	 * @param ?int $group_id ID.
	 * @return ?array
	 */
	public function elemental_show_user_members( int $group_id ): ?array {
		$membership_users = (array) get_post_meta( $group_id, 'membership_users', true );
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
	 * Check if user is a Store Owner or Staff.
	 *
	 * @param ?int $user_id of user to check. (can be left blank for current logged in user ).
	 * @return ?bool
	 */
	public function am_i_merchant( $user_id = null ):bool {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		$user       = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $user_id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $user ) );

		return ( $user_roles->is_wcfm_shop_staff() || $user_roles->is_wcfm_vendor() );
	}

	/**
	 * Check if user is a Store Owner or Staff.
	 *
	 * @param ?int $user_id of user to check. (can be left blank for current logged in user ).
	 * @return ?bool
	 */
	public function am_i_staff( $user_id = null ):bool {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		$user       = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $user_id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $user ) );

		return ( $user_roles->is_wcfm_shop_staff() );
	}

	/**
	 * Check if user is a Store Owner or Staff.
	 *
	 * @param ?int $user_id of user to check. (can be left blank for current logged in user ).
	 * @return ?bool
	 */
	public function am_i_storeowner( $user_id = null ):bool {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		$user       = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $user_id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $user ) );

		return ( $user_roles->is_wcfm_vendor() );
	}

	/** Get Parent ID of Store from a Child Account.
	 * This function Returns the Parent ID of the Merchant of a store
	 * Used to always return the store parent ID, and filter out Staff/Child Accounts
	 *
	 * @param int $id of user to check (either Staff or Store Owner, or Sponsored ).
	 * @return ?int
	 */
	public function staff_to_parent( int $id ): ?int {
		if ( ! $id ) {
			return null;
		}

		$staff      = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $staff ) );

		if ( $user_roles->is_wcfm_vendor() ) {
			return $id;
		} elseif ( $user_roles->is_sponsored_account() ) {
			$parent_id = Factory::get_instance( MemberSyncDAO::class )->get_parent_by_child( \get_current_user_id() );
		} elseif ( $user_roles->is_wcfm_shop_staff() ) {
			$parent_id = $staff->_wcfm_vendor;
		} else {
			return null;
		}

		$parent     = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $parent_id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $parent ) );

		if ( $parent && $user_roles->is_wcfm_vendor() ) {
			return $parent_id;
		} else {
			return null;
		}
	}

	/**
	 * Get Store Memberships of a user
	 *
	 * @param int $store_id the User ID of the Store parent to Check.
	 * @return ?array
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
			return null;
		}
	}

	/**
	 * Am I a Premium Member.
	 * Checks whether store and all memberships are part of stored premium experience setting.
	 *
	 * @param int $store_id the StoreID to Check.
	 * @return bool
	 */
	public function elemental_am_i_premium( int $store_id ): bool {
		$store_memberships = $this->elemental_get_store_memberships( $store_id );
		if ( ! $store_memberships ) {
			return false;
		}
		$premium        = get_option( WCFMHelpers::SETTING_WCFM_PREMIUM_MEMBERSHIPS );
		$premium_levels = str_getcsv( $premium );

		foreach ( $premium_levels as $level ) {
			$level = intval( $level );
			foreach ( $store_memberships as $membership ) {
				$membership = intval( $membership );
				if ( $membership === $level ) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Get Correct Page Owner
	 *
	 * @return ?int
	 */
	public function get_wcfm_page_owner(): ?int {

		$wcfm_store_url  = wcfm_get_option( 'wcfm_store_url', 'store' );
		$wcfm_store_name = apply_filters( 'wcfmmp_store_query_var', get_query_var( $wcfm_store_url ) );

		if ( empty( $wcfm_store_name ) ) {
			return null;
		}
		$seller_info = get_user_by( 'slug', $wcfm_store_name );
		if ( ! $seller_info ) {
			return null;
		}
		return $seller_info->ID;
	}

	/**
	 * Detects whether Page is a WCFM Store.
	 *
	 * @return bool
	 */
	public function is_wcfm_store(): bool {

		if ( ! \function_exists( 'is_shop' ) || ! \function_exists( 'is_product_category' ) ) {
			return false;
		}

		$is_shop    = is_shop();
		$is_archive = is_product_category();

		if ( $is_shop && ! $is_archive ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Display parent product owners outside of WCFM
	 *
	 * @param string $id - the store ID (leave blank if using this from inside Vendor's Store page).
	 *
	 * @return ?string
	 */
	public function display_products( $id = null ): ?string {
		if ( $id ) {
			$store_id = $id;
		} else {
			$store_id = $this->get_wcfm_page_owner();
		}

		return \do_shortcode( '[products store="' . $store_id . '" paginate="true"]' );
	}

	/**
	 * Is WCFM Marketplace Active - checks if WCFMMP is enabled.
	 *
	 * @return bool
	 */
	public function is_wcfmmp_available(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'wc-multivendor-marketplace/wc-multivendor-marketplace.php' );
	}

	/**
	 * Generate a merchant URL from $ID
	 * This function takes all site parameters and assembles correctly a Store URL taking merchants and staff into consideration and name of Marketplace parameter
	 *
	 * @param int|null $user_id - the user ID.
	 *
	 * @return string
	 */
	public function get_store_url( int $user_id = null ): string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$slug = $this->get_name( $user_id );

		return get_site_url() . '/' . get_option( 'wcfm_store_url' ) . '/' . $slug;
	}



	/**
	 * Returns the Correctly Formatted Username in Menus dealing with Merchants
	 * For Merchants it returns store name or their nice name - for staff it returns parent store name
	 *
	 * @param int|null $id - the ID.
	 *
	 * @return string
	 */
	public function get_name( int $id = null ): string {
		if ( $id ) {
			$user = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $id );
		} else {
			$user = wp_get_current_user();
		}

		$user_roles = Factory::get_instance( UserRoles::class, array( $user ) );

		switch ( true ) {
			case $user_roles->is_wcfm_vendor():
				return $user->user_nicename;

			case $user_roles->is_wcfm_shop_staff():
				$parent_id  = $user->_wcfm_vendor;
				$store_user = \wcfmmp_get_store( $parent_id );
				$store_info = $store_user->get_shop_info();

				return $store_info['store_slug'];

			default:
				// If they aren't a vendor then we simply return User Login.
				return $user->user_nicename;
		}
	}

}
