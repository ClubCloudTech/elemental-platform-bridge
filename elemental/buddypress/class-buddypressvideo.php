<?php
/**
 * Addon functionality for BuddyPress -Video Room Handlers for BuddyPress
 *
 * @package ElementalPlugin\BuddyPressVideo
 */

namespace ElementalPlugin\BuddyPress;

use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Core\VideoHelpers;
use ElementalPlugin\Core\Security;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Shortcode\MyVideoRoomApp;
use ElementalPlugin\Shortcode\SecurityVideoPreference;
use ElementalPlugin\Shortcode\UserVideoPreference;

/**
 * Class BuddyPress
 */
class BuddyPressVideo extends Shortcode {




	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'bpboardroomguest', array( $this, 'bp_boardroom_video_guest' ) );
		$this->add_shortcode( 'bpboardroomswitch', array( $this, 'bp_boardroom_video_host' ) );
		$this->add_shortcode( 'groupmeetswitch', array( $this, 'groupmeet_switch' ) );
	}

	/**
	 * A Shortcode for the Boardroom View to be rendered on BuddyPress profile pages
	 * This is used for the Guest entry
	 * No arguments needed
	 *
	 * @param array $params
	 *
	 * @return string|null
	 */


	public function bp_boardroom_video_guest() {
		// Escape dependencies.
		if ( ! $this->get_instance( SiteDefaults::class )->is_buddypress_active() ) {
			return 'BuddyPress not active';
		}
		// Establish who is host.

		$user_id    = $user_id = \bp_displayed_user_id();
		$my_user_id = get_current_user_id();

		// Check if user is looking at own profile page - return host mode if they do, guest if they dont.
		if ( $user_id === $my_user_id ) {
			// return $this->bp_boardroom_video_host();
		}

		// Security Engine - blocks room rendering if another setting has blocked it ( eg upgrades, site lockdown, or other feature ).

		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'bppbrguest', SiteDefaults::MODULE_BUDDYPRESS_ID, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Layout and Reception Settings.
		$reception_setting     = $this->get_instance( VideoHelpers::class )->get_enable_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$reception_template    = $this->get_instance( VideoHelpers::class )->get_reception_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_template        = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_reception_state = $this->get_instance( VideoHelpers::class )->get_video_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_reception_url   = $this->get_instance( VideoHelpers::class )->get_video_reception_url( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );

		// Build Shortcode.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'userbr', $user_id ),
			$video_template,
		);

		// Check Floorplan Status.
		$show_floorplan = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}

		if ( $reception_setting && $reception_template ) {
			$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

			if ( $video_reception_state ) {

				$myvideoroom_app->set_reception_video_url( $video_reception_url );
			}
		}
		// Construct Shortcode Template - and execute.
		$header    = $this->get_instance( SectionTemplates::class )->meet_guest_header();
		$shortcode = $myvideoroom_app->output_shortcode();
		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode );

	}

	/**
	 * A Shortcode for the Boardroom View to be rendered on BuddyPress profile pages
	 * This is used for the Host entry and contains switching logic that will direct automatically to guest if not in own profile
	 * This function means no switching function is needed ( guests also re-direct to host if in own profile )
	 * No arguments needed
	 *
	 * @param array $params
	 *
	 * @return string|null
	 */


	public function bp_boardroom_video_host() {
		// Escape dependencies.
		if ( ! $this->get_instance( SiteDefaults::class )->is_buddypress_active() ) {
			return 'BuddyPress not active';
		}
		// Establish who is host.

		$user_id    = \bp_displayed_user_id();
		$my_user_id = get_current_user_id();

		// Check if user is looking at own profile page - continue if they do, redirect to guest if they dont.
		if ( $user_id !== $my_user_id ) {
			return $this->bp_boardroom_video_guest();
		}

		// Security Engine - blocks room rendering if another setting has blocked it ( eg upgrades, site lockdown, or other feature ).

		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'bppbrhost', SiteDefaults::MODULE_BUDDYPRESS_ID, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Parameters.
		$video_template = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		// Build the Room.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'userbr', $user_id ),
			$video_template,
		)->enable_admin();

		// Check Floorplan Status.
		$show_floorplan = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}

		// Construct Shortcode Template - and execute.
		$header           = Factory::get_instance( SectionTemplates::class )->meet_host_header();
		$shortcode        = $myvideoroom_app->output_shortcode();
		$admin_page       = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			$user_id,
			SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM,
			array( 'basic', 'premium' )
		);
		$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
			$user_id,
			SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM
		);
		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode, $admin_page, $permissions_page );
	}

	/**
	 * A shortcode to switch Group Meeting Templates to Admins or Users
	 * The groups video page subnav menu calls this function which in term calls the hosting, or attendee pages depending on role
	 *
	 * @return string
	 */
	public function groupmeet_switch() {
		global $bp;

		if ( $this->get_instance( BuddyPress::class )->bp_can_host_group( get_current_user_id() ) ) {
			return $this->get_instance( self::class )->bp_group_video_host();
		} else {
			return $this->get_instance( self::class )->bp_group_video_guest();
		}
	}


	/**
	 * Bp_group_video_host.
	 * Provides Group Host Function for Buddypress
	 *
	 * @return string The shortcode output
	 */
	public function bp_group_video_host() {
		global $bp;
		// Escape dependencies.
		if ( ! $this->get_instance( SiteDefaults::class )->is_buddypress_active() ) {
			return 'BuddyPress not active';
		}
		// Establish who is host -set group creator as base Group ID for Room - Security Check already done in Switch that calls this function.
		$user_id    = $bp->groups->current_group->creator_id;
		$my_user_id = get_current_user_id();
		$room_name  = $bp->groups->current_group->slug;

		// Checking Permissions of for Host Status of Group.
		if ( ! $this->get_instance( BuddyPress::class )->bp_can_host_group( $my_user_id ) ) {
			$this->get_instance( self::class )->bp_group_video_guest();
		}

		// Security Engine - blocks room rendering if another setting has blocked it ( eg upgrades, site lockdown, or other feature ).
		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'bpgrouphost', SiteDefaults::MODULE_BUDDYPRESS_ID, SiteDefaults::ROOM_NAME_BUDDYPRESS_GROUPS );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Layout and Reception Settings.
		$video_template = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, $room_name );
		// Build the Room.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'group', $user_id ),
			$video_template
		)->enable_admin();

		// Floorplan Status from DB (applies to hosts as well as guests).
		$show_floorplan = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, $room_name );
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}

		// Construct Shortcode Template - and execute.
		$header           = Factory::get_instance( SectionTemplates::class )->bp_group_host_template();
		$shortcode        = $myvideoroom_app->output_shortcode();
		$admin_page       = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			$user_id,
			$room_name,
			array( 'basic', 'premium' )
		);
		$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
			$user_id,
			$room_name
		);

		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode, $admin_page, $permissions_page );
	}


	/**
	 * BP Groups - Guest render.
	 *
	 * @return string Returns the Shortcode call.
	 */
	public function bp_group_video_guest() {
		global $bp;
		// Escape dependencies.
		if ( ! $this->get_instance( SiteDefaults::class )->is_buddypress_active() ) {
			return null;
		}

		// Establish who is host -set group creator as base Group ID for Room - Security Check already done in Switch that calls this function.
		$user_id    = $bp->groups->current_group->creator_id;
		$my_user_id = get_current_user_id();

		// Checking Permissions of for Host Status of Group.
		if ( $this->get_instance( BuddyPress::class )->bp_can_host_group( $my_user_id ) ) {
			$this->get_instance( self::class )->bp_group_video_host();
		}
		// Security Engine - blocks room rendering if another setting has blocked it ( eg upgrades, site lockdown, or other feature ).

		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'bpgroupguest', SiteDefaults::MODULE_BUDDYPRESS_ID, SiteDefaults::ROOM_NAME_BUDDYPRESS_GROUPS );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Layout and Reception Settings.
		$reception_setting     = $this->get_instance( VideoHelpers::class )->get_enable_reception_state( $user_id, $bp->groups->current_group->slug );
		$reception_template    = $this->get_instance( VideoHelpers::class )->get_reception_template( $user_id, $bp->groups->current_group->slug );
		$video_template        = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, $bp->groups->current_group->slug );
		$video_reception_state = $this->get_instance( VideoHelpers::class )->get_video_reception_state( $user_id, $bp->groups->current_group->slug );
		$video_reception_url   = $this->get_instance( VideoHelpers::class )->get_video_reception_url( $user_id, $bp->groups->current_group->slug );

		// Build the Room.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'group', $user_id ),
			$video_template
		);
		// Check Floorplan Status.
		$show_floorplan = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}

		// Reception Settings.
		if ( $reception_setting ) {
			$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

			if ( $video_reception_state ) {

				$myvideoroom_app->set_reception_video_url( $video_reception_url );
			}
		}
		// Construct Shortcode Template - and execute.
		$header    = Factory::get_instance( SectionTemplates::class )->bp_group_guest_template();
		$shortcode = $myvideoroom_app->output_shortcode();

		echo $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode );
		return null;
	}
}
