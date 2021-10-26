<?php
/**
 * MVR VERSION
 * Shortcodes for pages
 *
 * @package ElementalPlugin\Core
 */

namespace ElementalPlugin\MVR;

use ElementalPlugin\Core\MenuHelpers;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\UltimateMembershipPro\MembershipLevel;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\WCFM\WCFMHelpers;

// required for cleaning correct URL redirects in Firefox.
ob_clean();
ob_start();

/**
 * Class PageSwitches
 */
class PageSwitches extends Shortcode {




	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'mvrswitch', array( $this, 'mvr_switch_shortcode' ) );

	}

	/**
	 * A shortcode to switch The My Video Room Tab to correct usage
	 * the /meet room works from this logic
	 *
	 * @return string
	 */
	public function mvr_switch_shortcode(): string {

		$user_id  = \get_current_user_id();
		$owner_id = \bp_displayed_user_id();

		$user = \ElementalPlugin\Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $owner_id );

		$user_roles = \ElementalPlugin\Factory::get_instance( UserRoles::class, array( $user ) );

		// handle signed out users and return signed out templates.
		if ( ! \is_user_logged_in() ) {

			if ( \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->is_premium_check( $owner_id ) && ! $user_roles->is_wcfm_shop_staff() ) {
				$url = \ElementalPlugin\Factory::get_instance( MenuHelpers::class )->get_store_url( (int) $owner_id ) . '/' . \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->defaults( 'video_storefront_slug' );
				wp_redirect( $url );
				exit();

			} elseif ( \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->is_premium_check( $owner_id ) && $user_roles->is_wcfm_shop_staff() ) {

				return do_shortcode( '[elementor-template id="34095"]' );
			} else {
				return do_shortcode( ' [elementor-template id="29585"]' );
			}
		} elseif ( ! $user_roles->is_wcfm_vendor() && ! $user_roles->is_wcfm_shop_staff() ) {
			// Redirecting to Premium Store for normal users.
			if ( \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->is_premium_check( $owner_id ) ) {
				$url = \ElementalPlugin\Factory::get_instance( MenuHelpers::class )->get_store_url( (int) $owner_id ) . '/' . \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->defaults( 'video_storefront_slug' );

				wp_redirect( $url );
				exit();
			} else {
				// Default Upgrade Template - as it must be normal user in own page.
				return do_shortcode( ' [elementor-template id="29585"]' );

			}
		}

		// Now Get Merchant and Staff.
		if ( $user_roles->is_wcfm_vendor() || $user_roles->is_wcfm_shop_staff() ) {

			// First Guest Owners who dont own this store (or Staff).
			if ( $owner_id !== \ElementalPlugin\Factory::get_instance( WCFMHelpers::class )->staff_to_parent( $user_id ) ) {
				return do_shortcode( '[elementor-template id="34858"]' );
			} elseif ( $user_id === $owner_id ) {

				// Own Stores/Profiles - Redirect to Admin Centres.
				$membership_level = get_user_meta( $user->id, 'ihc_user_levels' );
				$memlev           = explode( ',', $membership_level[0] );
				$array_count      = count( $memlev );
				// Role Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
				for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
					switch ( $memlev[ $x ] ) {
						case MembershipLevel::BUSINESS:
						case MembershipLevel::PREMIUM:
						case MembershipLevel::BASIC:
							return do_shortcode( ' [elementor-template id="34095"]' );

						case MembershipLevel::VENDOR_STAFF:
							// Basic Staff Host template.
							if ( \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->is_premium_check( $owner_id ) ) {
								return do_shortcode( '[elementor-template id="34858"]' );
							} else {
								// Upgrade Page as Account Inactive.
								do_shortcode( ' [elementor-template id="34880"]' );
							}

							break;
					}
				}

				// Default Upgrade Template - as it must be normal user in own page.
				return do_shortcode( ' [elementor-template id="29585"]' );
			}

			// Deal with Inactive Staff.
			if ( $user_roles->is_wcfm_shop_staff()
				&& ! \ElementalPlugin\Factory::get_instance( SiteDefaults::class )->is_premium_check( \ElementalPlugin\Factory::get_instance( WCFMHelpers::class )->staff_to_parent( $owner_id ) )
			) {
				// Upgrade Page as Account Inactive.
				return do_shortcode( ' [elementor-template id="34880"]' );
			} else {
				return do_shortcode( '[elementor-template id="34858"]' );
			}
		}

		return '';
	}

	public function meet_helper( int $user_id ) {

		if ( ! \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Core\SiteDefaults::class )->is_mvr() ) {
			return null;
		}

		$membership_level = get_user_meta( $user_id, 'ihc_user_levels' );
		$memlev           = explode( ',', $membership_level[0] );
		$array_count      = count( $memlev );

		// Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
		for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
			switch ( $memlev[ $x ] ) {
				case MembershipLevel::BUSINESS:
					return $this->get_instance( SectionTemplates::class )->meet_admin_page_template();
				case MembershipLevel::PREMIUM:
					return $this->get_instance( SectionTemplates::class )->meet_admin_page_template();
				case MembershipLevel::BASIC:
					return $this->get_instance( SectionTemplates::class )->meet_admin_page_template();
				case MembershipLevel::VENDOR_STAFF:
					return $this->get_instance( SectionTemplates::class )->meet_admin_page_template();
			}
		}
		return null;
	}

	/**
	 * This function checks if a user is a store owner, or UMP specific subscription level - if so - it returns null -
	 * if User is blocked from using Video by subscription level - page returns a configurable upgrade sales page.
	 * Used for MVR - but function will become part of membership/UMP plugin
	 *
	 * @return string,null
	 */

	public function ump_membership_upgrade_block() {

		$membership_level = get_user_meta( $user_id, 'ihc_user_levels' );
		$memlev           = explode( ',', $membership_level[0] );
		$array_count      = count( $memlev );
		// Role Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
		for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
			switch ( $memlev[ $x ] ) {
				case MembershipLevel::BUSINESS:// Coach gold
				case MembershipLevel::PREMIUM:// Coach silver
				case MembershipLevel::BASIC:// Coach bronze
				case MembershipLevel::VENDOR_STAFF:
					$membership_block = false;
					break;
			}
		}    //sets default case in case no selection by merchant
		if ( $membership_block ) {
			return \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Library\SectionTemplates::class )->mvr_ump_wcfm_upgrade_template();
		} else {
			return null;
		}

	}

	public function wcfm_membership_upgrade_block() {

		$user_roles = \ElementalPlugin\Factory::get_instance( UserRoles::class );
		if ( $user_roles->is_wcfm_vendor()
			|| $user_roles->is_wcfm_shop_staff()
			|| $user_roles->is_wordpress_administrator()
		) {
			$membership_block = false;
		} else {
			$membership_block = true;
		}

		if ( $membership_block ) {
			return \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Library\SectionTemplates::class )->mvr_ump_wcfm_upgrade_template();
		} else {
			return null;
		}

	}

}//end class


