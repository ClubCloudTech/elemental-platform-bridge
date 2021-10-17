<?php

namespace ElementalPlugin\Core;

use ElementalPlugin\Factory;
use ElementalPlugin\Core\PageFilters;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\MVR\PageSwitches;
use ElementalPlugin\DAO\SecurityVideoPreference;
use ElementalPlugin\Library\Templates\SecurityTemplates;


/**
 * Class FiltersUtilities
 */
class Security {


	/**
	 * This function is called by all video switches to determine if they can return the video room, or if a setting has blocked their rendering
	 * It is a constructor only with other functions doing the filtering
	 *
	 * @param  takes $host_id - user_id of host to send to upstream filters. $room_type - string of type of room to filter on
	 * @return null|string  - null if nothing blocks, or template page if it does
	 */
	public function render_block( $host_id = null, string $room_type, $module_id = null, $room_name = null ) {

		// Activation/module.
		if ( ! Factory::get_instance( ModuleConfig::class )->module_activation_status( SiteDefaults::MODULE_SECURITY_ID ) ) {
			return null;
		}
		// Getting Master Site override state.

		$site_override = Factory::get_instance( SecurityVideoPreference::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		$room_disabled = Factory::get_instance( SecurityVideoPreference::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'room_disabled' );
		if ( $site_override && $room_disabled ) {
			return Factory::get_instance( SecurityTemplates::class )->room_blocked_by_site();
		}

		/*
		Setup Environment Room Name Transformations for Special Cases.
		* Room names need to be modified for special cases - like multi-user scenarios.
		*/

		// Case Site Video Setting User ID to Site Default User for that room type.
		if ( SiteDefaults::ROOM_NAME_SITE_VIDEO === $room_name ) {
			$host_id = SiteDefaults::USER_ID_SITE_DEFAULTS;
		}

		// Case BuddyPress Groups = need to pass room name, and host IDs as their creator and group name.
		if ( Factory::get_instance( SiteDefaults::class )->is_buddypress_available() ) {
			global $bp;
			if ( SiteDefaults::ROOM_NAME_BUDDYPRESS_GROUPS === $room_name ) {
				$host_id   = $bp->groups->current_group->creator_id;
				$room_name = $bp->groups->current_group->slug;
			}
		}

		// Trapping any Host filter to set host status.
		if ( strpos( $room_type, 'host' ) !== false ) {
			$host_status = true;
		}

		// First - Check Room Active - User Disable/Enable check.
		$disabled_block = Factory::get_instance( PageFilters::class )->block_disabled_room_video_render( $host_id, $room_name, $host_status, $room_type );
		if ( $disabled_block ) {
			return $disabled_block;
		}

		// Second Check Meeting/Room Type Module is Active in Control Panel - Module Check.
		if ( $module_id ) {
			$class_block = Factory::get_instance( PageFilters::class )->block_disabled_module_video_render( $module_id );
			if ( $class_block ) {
				return $class_block;
			}
		}

		// Check Users Signed Out Global Filter - Anonymous Check.
		if ( ! is_user_logged_in() ) {
			$anonymous_status = Factory::get_instance( SecurityVideoPreference::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'anonymous_enabled' );
			if ( $anonymous_status ) {
				return Factory::get_instance( SecurityTemplates::class )->room_blocked_by_site( 'anonymous' );
			}
			$signed_out_block = Factory::get_instance( PageFilters::class )->block_anonymous_room_video_render( $host_id, $room_name, $host_status, $room_type );
			if ( $signed_out_block ) {
				return $signed_out_block;
			}
		}

		// Check Allowed_Roles and Blocked Roles.
		$allowed_roles_block = Factory::get_instance( PageFilters::class )->allowed_roles_room_video_render( $host_id, $room_name, $host_status, $room_type );

		if ( $allowed_roles_block ) {
			return $allowed_roles_block;
		}

		// Check BuddyPress Group Membership - and other related if module enabled.
		if ( Factory::get_instance( SiteDefaults::class )->is_buddypress_available() ) { // Apply to Groups Only.
			if ( \bp_is_groups_component() ) {
				// Check Group Filter.
				$bp_group_block = Factory::get_instance( PageFilters::class )->block_bp_non_group_member_video_render( $host_id, $room_name, $host_status, $room_type );

				if ( $bp_group_block ) {
					return $bp_group_block;
				}
			}
			// Check Friend Filter.
			if ( ( strpos( $room_type, 'guest' ) !== false ) ) {
				$bp_friend_block = Factory::get_instance( PageFilters::class )->block_bp_friend_video_render( $host_id, $room_name, $host_status, $room_type );
			}
			if ( $bp_friend_block ) {
				return $bp_friend_block;
			}
		}

		if ( 'pbrhost' === $room_type ) {

			// MVR - get membership levels for filtering subscribers and rejecting non subscribed users who shouldn't get video.
			// @TODO FB Will be upgraded to plugin section when support for UMP and WCFM role types is added as plugin explicitly.

			// MVR Case - valid to block personal rooms from non premium users, and non storeowners.
			if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
				$umpblock  = Factory::get_instance( PageSwitches::class )->ump_membership_upgrade_block();
				$wcfmblock = Factory::get_instance( PageSwitches::class )->wcfm_membership_upgrade_block();

				if ( $umpblock ) {
					return $umpblock;
				}
				if ( $wcfmblock ) {
					return $wcfmblock;
				}
			}
			// Filters 2...n.
		}

	}
} // end class
