<?php
/**
 * Setup Functions for Membership
 *
 * @package membership/library/class-membershipsetup.php
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\DAO\MemberSyncDAO;
use ElementalPlugin\Membership\Membership;
use ElementalPlugin\Xprofile\Library\XprofileTools;

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
		$this->create_registration_bpgroup();

	}


	/**
	 * Create Membership Role for Sponsored Account
	 */
	public function create_membership_role(): void {
		global $wp_roles;
		$edr = $wp_roles->get_role( 'Subscriber' );
		add_role( Membership::MEMBERSHIP_ROLE_NAME, Membership::MEMBERSHIP_ROLE_DESCRIPTION, $edr->capabilities );
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
}

