<?php
/**
 * Setup Functions for Membership
 *
 * @package membership/library/class-membershipsetup.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\BuddyPress\ElementalBP;
use ElementalPlugin\Module\Membership\DAO\MembershipDAO;
use ElementalPlugin\Module\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Module\Membership\Membership;
use ElementalPlugin\Module\BuddyPress\Library\XprofileTools;

/**
 * Class Setup
 */
class MembershipSetup {

	const SETTING_REGISTRATION_GROUP_ID = 'elemental_groupname_registration';
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

		Factory::get_instance( MembershipDAO::class )->install_membership_mapping_table();
		Factory::get_instance( MemberSyncDAO::class )->install_membership_sync_table();
		$this->create_membership_role();
		if ( Factory::get_instance( ElementalBP::class )->is_buddypress_available() ) {
			$this->create_registration_bpgroup();
		}
	}


	/**
	 * Create Membership Role for Sponsored Account
	 */
	public function create_membership_role(): void {
		global $wp_roles;
		$edr = $wp_roles->get_role( 'Subscriber' );
		if ( $edr ) {
			add_role( Membership::MEMBERSHIP_ROLE_NAME, Membership::MEMBERSHIP_ROLE_DESCRIPTION, $edr->capabilities );
		}
	}

	/**
	 * Create Registration BP Group
	 */
	public function create_registration_bpgroup(): void {
		$description = esc_html__( 'This Contains the Mandatory and Important Information that is needed Club Wide for all members, staff, and vendors', 'myvideoroom' );
		$group_name  = 'Registration';
		$field       = Factory::get_instance( XprofileTools::class )->create_xprofile_group( $group_name, $description );
		\update_option( self::SETTING_REGISTRATION_GROUP_ID, $field );
	}

	/**
	 * IS Page Elementor ?
	 * Returns whether page is in elementor admin mode.
	 *
	 * @return bool
	 */
	public function is_page_elementor(): bool {
		$url        = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$admin_page = strpos( $url, 'wp-admin' );
		$elementor  = strpos( $url, 'elementor' );
		if ( $admin_page || $elementor ) {
			return true;
		} else {
			return false;
		}

	}



}

