<?php
/**
 * Addon functionality for Filtering Users from Accessing Rooms
 *
 * Called from
 */

namespace ElementalPlugin\Core;

use ElementalPlugin\Library\UserRoles;

use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Factory;
use ElementalPlugin\BuddyPress\BuddyPress;
use ElementalPlugin\DAO\SecurityVideoPreference as SecurityVideoPreferenceDAO;
use ElementalPlugin\Library\Templates\SecurityTemplates;
use ElementalPlugin\Shortcode\SecurityVideoPreference;
use ElementalPlugin\Library\WordPressUser;



/**
 * Class Template
 */
class PageFilters extends Shortcode {




	/**
	 * Filters
	 */
	/**
	 * This function Checks a Module is Active to allow it to render Video
	 * Used only in admin pages of plugin
	 *
	 * @return String  Template if Blocked - null if allowed
	 */


	public function block_disabled_module_video_render( int $module_id ) {
		// Check BuddyPress as both itself and Personal Video Scenarios
		if ( $module_id === SiteDefaults::MODULE_BUDDYPRESS_ID ) {
			$is_module_enabled = Factory::get_instance( ModuleConfig::class )->read_enabled_status( $module_id );
			if ( ! $is_module_enabled ) {
				return Factory::get_instance( SectionTemplates::class )->room_blocked_by_module();
			}
			// Now Check as BuddyPress Completed - Check as Personal Boardroom.
			$module_id = SiteDefaults::MODULE_PERSONAL_MEETING_ID;
		}
		$is_module_enabled = Factory::get_instance( ModuleConfig::class )->read_enabled_status( $module_id );
		if ( ! $is_module_enabled ) {
			return Factory::get_instance( SectionTemplates::class )->room_blocked_by_module();
		}
	}

	/**
	 * This function Checks a Module is Active to allow it to render Video
	 * Used only in admin pages of plugin
	 *
	 * @return String  Template if Blocked - null if allowed
	 */


	public function block_disabled_room_video_render( int $user_id, string $room_name, $host_status, $room_type = null ) {
		// Check BuddyPress as both itself and Personal Video Scenarios.
		$is_room_disabled = Factory::get_instance( SecurityVideoPreferenceDAO::class )
		->read_security_settings( $user_id, $room_name, 'room_disabled' );

		// Is Disable setting active ?
		if ( $is_room_disabled ) {
			if ( $host_status ) {
				// If user is a host return their control panel
				$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
					$user_id,
					$room_name
				);

				return $permissions_page;
			}
			// For guests return the blocked template.
			else {
				$blocked_display = Factory::get_instance( SecurityTemplates::class )->room_blocked_by_user( $user_id, $room_type );
				return $blocked_display;
			}
		}
	}

	/**
	 * This function Checks The Disable Anonymous Setting is/not on - and enforces result
	 * Used by all rooms
	 *
	 * @return String  Template if Blocked - null if allowed
	 */



	public function block_anonymous_room_video_render( int $user_id, string $room_name, $host_status, $room_type = null ) {
		// Check to see if User has blocked Anonymous Access in the database and render block template if so.

		$is_room_disabled = Factory::get_instance( SecurityVideoPreferenceDAO::class )
		->read_security_settings( $user_id, $room_name, 'anonymous_enabled' );
		// If the restrict room setting is enabled fire the block.
		if ( $is_room_disabled ) {
			$blocked_display = Factory::get_instance( SecurityTemplates::class )->anonymous_blocked_by_user( $user_id, $room_type );
		}
		return $blocked_display;
	}

	/**
	 * * This function Checks The Role Based Configuration Settings - and enforces result
	 * Used by all rooms
	 *
	 * @return String  Template if Blocked - null if allowed
	 */

	public function allowed_roles_room_video_render( int $owner_id, string $room_name, $host_status, $room_type = null ) {
		// Check Module State.
		$site_override     = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		$site_role_control = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'allow_role_control_enabled' );
		// Override Control Check.
		if ( $site_override ) {
			$owner_id  = SiteDefaults::USER_ID_SITE_DEFAULTS;
			$room_name = SiteDefaults::ROOM_NAME_SITE_DEFAULT;
		}
		$room_control_enabled_state = Factory::get_instance( SecurityVideoPreferenceDAO::class )
		->read_security_settings( $owner_id, $room_name, 'allow_role_control_enabled' );
		// Exit Filter if calling from Host Functions ( that pass host in as variable ) or if Module is Disabled.
		if ( ! $room_control_enabled_state || ( $host_status ) ) {
			return null;
		}

		// Reject Anonymous Users ( as have no role ).
		if ( ! is_user_logged_in() ) {
			return Factory::get_instance( SecurityTemplates::class )->blocked_by_role_template( $owner_id, $room_type );
		}

		// Decide whether to allow or block.
		$allow_to_block_switch = Factory::get_instance( SecurityVideoPreferenceDAO::class )
		->read_security_settings( $owner_id, $room_name, 'block_role_control_enabled' );

		// Get List of Allowed/Blocked Roles from DB.

		$allowed_db_roles_configuration = Factory::get_instance( SecurityVideoPreferenceDAO::class )
		->read_db_wordpress_roles( $owner_id, $room_name, 'allowed_roles' );

		if ( ( ! $allowed_db_roles_configuration ) && ( ! $allow_to_block_switch ) ) {
			return Factory::get_instance( SecurityTemplates::class )->blocked_by_role_template( $owner_id, $room_type );
		}

		// Retrieve Users Roles.

		global $wp_roles;
		$user_roles = Factory::get_instance( UserRoles::class )->get_user_roles();

		// Retrieve Allowed/Blocked Roles.

		// User Roles May be multiple ( so check each role ).
		$role_match = false;

		foreach ( $user_roles as $user_role ) {

			$role_name = translate_user_role( $wp_roles->roles[ $user_role ]['name'] );
			foreach ( $allowed_db_roles_configuration as $db_role ) {
				// transform user role to Display format.

				if ( $db_role === $role_name ) {
					$role_match = true;
				}
			}
		}// End per role Check.

		// Fire Block if Flag to block is on.
		if ( $role_match === true && $allow_to_block_switch ) {
			$blocked_display = Factory::get_instance( SecurityTemplates::class )->blocked_by_role_template( $owner_id, $room_type );
			return $blocked_display;
		} elseif ( false === $role_match && ! $allow_to_block_switch ) {
			$blocked_display = Factory::get_instance( SecurityTemplates::class )->blocked_by_role_template( $owner_id, $room_type );
			return $blocked_display;
		}

		return $blocked_display;
	}


	/**
	 * This function Checks The Group Membership setting ( allow only group members ) of BuddyPress Groups - and enforces result
	 * Used by Only BuddyPress Groups
	 *
	 * @return String  Template if Blocked - null if allowed
	 */



	public function block_bp_non_group_member_video_render( int $user_id, string $room_name, $host_status, $room_type = null ) {
		// Exit for Non Groups.
		if ( ! bp_is_groups_component() ) {
			return null;
		}
		// Check Settings.
		global $bp;
		$creator_id    = $bp->groups->current_group->creator_id;
		$site_override = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		if ( $site_override ) {
			$creator_id = SiteDefaults::USER_ID_SITE_DEFAULTS;
			$room_name  = SiteDefaults::ROOM_NAME_SITE_DEFAULT;
		}
		$room_access_setting = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $creator_id, $room_name, 'restrict_group_to_members_enabled' );

		// Exit early if no setting for filter.
		if ( ! $room_access_setting ) {
			return null;
		}
		// Get Global Information on Group.

		$group_id = $bp->groups->current_group->id;
		$user_id  = get_current_user_id();
		// echo '<br> 216 of Page Filters getid'. $my_id . '<br>userid'. $user_id. '<br>user id-group'. $group_id .'<br>';
		// echo '<br>'.$room_name;
		$is_user_member    = Factory::get_instance( BuddyPress::class )->bp_is_user_member( $group_id, $user_id );
		$is_user_moderator = Factory::get_instance( BuddyPress::class )->bp_is_user_moderator( $group_id, $user_id );
		$is_user_admin     = Factory::get_instance( BuddyPress::class )->bp_is_user_admin( $group_id, $user_id );
		// echo     'block fire->member'.$is_user_member . '<br>moder'. $is_user_moderator .'<br>admin'. $is_user_admin .'<br>room access' . $room_access_setting. '<br> room name->'. $room_name;.
		// echo var_dump ( $room_access_setting );
		switch ( $room_access_setting ) {
			case 'Administrators':
				if ( $is_user_admin ) {
					return null;
				}
				// Check for all Roles starting with Admin - and Fall through.
			case 'Moderators':
				if ( $is_user_admin || $is_user_moderator ) {
					return null;
				}
				// Check for all Roles - and Fall through.
			case 'Members':
				if ( $is_user_admin || $is_user_moderator || $is_user_member ) {
					return null;
				}

				// Else Fire the Block.

		}
		return Factory::get_instance( SecurityTemplates::class )->blocked_by_group_membership( $creator_id, $room_type );

	}

	/**
	 * This function Checks The Group Membership setting ( allow only group members ) of BuddyPress Groups - and enforces result
	 * Used by Only BuddyPress Groups
	 *
	 * @return String  Template if Blocked - null if allowed
	 */



	public function block_bp_friend_video_render( int $user_id, string $room_name, $host_status, $room_type = null ) {

		// Check Settings.
		global $bp;
		$user_id_original = $user_id;
		$site_override    = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		$site_override    = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		if ( $site_override ) {
			$owner_id  = SiteDefaults::USER_ID_SITE_DEFAULTS;
			$room_name = SiteDefaults::ROOM_NAME_SITE_DEFAULT;
		} else {
			$owner_id = $user_id;
		}
		$room_access_setting = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $owner_id, $room_name, 'bp_friends_setting' );

		// Exit early if no setting for filter.
		if ( ! $room_access_setting ) {
			return null;
		}

		// Get Global Information on User Relationships to start.
		$visitor_id     = get_current_user_id();
		$friends_status = friends_check_friendship_status( $user_id, $visitor_id );
		// echo '<br><br>Friends-Status<br><br>Hostid->'.$user_id.' <br>GuestID->'. $visitor_id . '<br> Friends Status ->'. $friends_status . '<br>Room-Access->'.$room_access_setting;
		switch ( $room_access_setting ) {
			case '':
				return null;
				// Drop Setting Off Cases.
			case 'Do-Not-Disturb':
				if ( $friends_status ) {
					return null;
				} else {
					return Factory::get_instance( SecurityTemplates::class )->room_blocked_by_user( $user_id_original, $room_type );
				}
				// Allow Friends to Exit.
			case 'Stealth-Remove-Video':
				return null;

				// Else Fire the Block.
		}
		return Factory::get_instance( SecurityTemplates::class )->room_blocked_by_user( $user_id_original, $room_type );

		// echo  'block fire->member'.$is_user_member . '<br>moder'. $is_user_moderator .'<br>admin'. $is_user_admin .'<br>room access' . $room_access_setting. '<br> room name->'. $room_name;
		// echo var_dump ( $room_access_setting );.
	}



	/**
	 * A Function to Correctly Render Pictures for Store Owners, Staff, and Users Being Viewed by Visitors
	 *
	 * @param string $inbound_image
	 *
	 * @return string
	 */


	public function get_picture_template( int $user_id = null, $room_type = null ) {
		$owner_id   = $this->get_instance( SiteDefaults::class )->page_owner();
		$user       = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $owner_id );
		$user_roles = $this->get_instance( UserRoles::class, array( $user ) );

		if ( Factory::get_instance( SiteDefaults::class )->is_wcfm_active() ) {

			if ( $user_roles->is_wcfm_vendor() || $user_roles->is_wcfm_shop_staff() ) {

				switch ( true ) {
					case $user_roles->is_wcfm_vendor():
						$store_user     = \wcfmmp_get_store( $user_id );
						$store_gravatar = $store_user->get_avatar();

						return $store_gravatar;
				}
			}
		}

		if ( 'bpgrouppicture' === $room_type ) {
			$url = bp_core_fetch_avatar(
				array(
					'item_id' => $user_id, // id of user for desired avatar
					'type'    => 'full',
					'html'    => true,     // FALSE = return url, TRUE ( default ) = return img html
				)
			);
			return $url;
		}

		// Set up basics.

		if ( Factory::get_instance( SiteDefaults::class )->is_buddypress_active() && bp_displayed_user_id() ) {
			$url = \bp_core_fetch_avatar(
				array(
					'item_id' => \bp_displayed_user_id(),
					'type'    => 'full',
					'html'    => false,
				)
			);
			return $url;
		}

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		return $image[0];
	}
}//end class
