<?php
/**
 * Shortcodes for video controllers
 *
 * @package MyVideoRoomExtrasPlugin\Core
 */

namespace MyVideoRoomExtrasPlugin\Core;

use MyVideoRoomExtrasPlugin\Library\UserRoles;
use MyVideoRoomExtrasPlugin\Library\WordPressUser;
use MyVideoRoomExtrasPlugin\Shortcode as Shortcode;
use MyVideoRoomExtrasPlugin\Shortcode\MyVideoRoomApp;
use MyVideoRoomExtrasPlugin\WoocommerceBookings\ShortCodeConstructor;
use MyVideoRoomExtrasPlugin\Library\SectionTemplates;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\Core\VideoHelpers;
use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference;
use MyVideoRoomExtrasPlugin\Core\Security;
use MyVideoRoomExtrasPlugin\Shortcode\SecurityVideoPreference;

/**
 * Class VideoControllers
 */
class VideoControllers extends Shortcode {


	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'storefrontvideo', array( $this, 'call_storefront_shortcode' ) );

		$this->add_shortcode( 'merchantvideo', array( $this, 'merchant_video_shortcode' ) );

		$this->add_shortcode( 'personalmeetinghost', array( $this, 'personal_meeting_host_shortcode' ) );

		$this->add_shortcode( 'personalmeetingguest', array( $this, 'personal_meeting_guest_shortcode' ) );

		$this->add_shortcode( 'sitevideoroomhost', array( $this, 'site_videoroom_member_shortcode' ) );

		$this->add_shortcode( 'sitevideoroomguest', array( $this, 'site_videoroom_guest_shortcode' ) );
	}



	/**
	 * Display Videoroom for a merchant storefront based on stored Buddypress XProfile Parameters for Merchants
	 * Usage: In all front end storefront locations where seamless permissions video is needed.
	 * Requires - WCFM - BuddyPress
	 * Private function for MVR - not ready for broad plugin
	 *
	 * @return string
	 */
	public function call_storefront_shortcode() {
		global $WCFM;
		$post                 = \get_post();    // getting current page information to compare parent owners.
		$currentpost_store_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );

		// Get Room Template from Vendor and handle blank returns
		$field_name_or_id = $this->get_instance( SiteDefaults::class )->defaults( 'xprofile_storefront_field', $currentpost_store_id );

		$layout_id = \xprofile_get_field_data( $field_name_or_id, $currentpost_store_id );
		if ( ! $layout_id ) {
			$layout_id = $this->get_instance( SiteDefaults::class )->defaults( 'xprofile_storefront_sitedefault' );// get site details from user1's backup site field.
		}

		// deal with logged in users who arent store owners, staff or vendors.
		$user       = \wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class );

		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'store', $currentpost_store_id ),
			$layout_id,
		);

		// Get Reception Setting - for Guests.

		/*
		$reception_setting       = $this->get_instance(VideoHelpers::class)->get_enable_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
		$reception_template      = $this->get_instance(VideoHelpers::class)->get_reception_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
		$video_template          = $this->get_instance(VideoHelpers::class)->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
		$video_reception_state   = $this->get_instance(VideoHelpers::class)->get_video_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
		$video_reception_url     = $this->get_instance(VideoHelpers::class)->get_video_reception_url( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);
		$show_floorplan          = $this->get_instance(VideoHelpers::class)->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM);



		// Floorplan.
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}
		*/

		$reception_setting  = $this->get_instance( SiteDefaults::class )->get_layout_id( 'store_privacy', $currentpost_store_id );
		$reception_template = $this->get_instance( SiteDefaults::class )->get_layout_id( 'store_reception_template', $currentpost_store_id );

		/*
			  if ( $reception_setting ) {
			$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

			if ( $video_reception_state ) {
				echo 'reception';
				$myvideoroom_app->set_reception_video_url( $video_reception_url );
			}
		}*/

		if (
			! \is_user_logged_in() || (
				! $user_roles->is_wordpress_administrator() &&
				! $user_roles->is_wcfm_shop_staff() &&
				! $user_roles->is_wcfm_vendor()
			)
		) {
			return $myvideoroom_app->output_shortcode();
		}

		// get meta data from currently logged in user and return the parent vendor id We use this to know if a user is a child merchant/staff etc.
		$my_vendor_id = \get_user_meta( $user->id, '_wcfm_vendor', true );
		$my_owner_id  = \get_current_user_id();

		// Give Administrators or Store Manager Rights.
		if ( $user_roles->is_wordpress_administrator() || $user_roles->is_wcfm_store_manager() ) {
			if ( $my_owner_id === $currentpost_store_id ) {
				// in case Admin has their own store.
				$myvideoroom_app->enable_admin();
			} else {
				// administrators and store managers do not see reception so they emulate the store owners.
				$myvideoroom_app->disable_reception();
			}

			$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
				$user_id,
				SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM
			);

			return $myvideoroom_app->output_shortcode();
		}

		// Switch Store Owner from Staff and Other.
		// echo Check is a WCFM vendor, or store staff.

		if (
			( $user_roles->is_wcfm_vendor() && $my_owner_id === $currentpost_store_id ) || // case of an Owner in their own store.
			( $user_roles->is_wcfm_shop_staff() && $my_vendor_id === $currentpost_store_id ) // case of a Staff Member in their own store.
		) {
			$myvideoroom_app->enable_admin();
		}

		return $myvideoroom_app->output_shortcode();
	}


	/**
	 * Display Videoroom for Merchants of their own store with settings based on Buddypress XProfile Parameters for Merchants
	 * Usage: This is the admin entrance for merchant stores designed to be used from their site control panel
	 *
	 * @return string
	 */
	public function merchant_video_shortcode() {
		// getting current page information to compare parent owners.

		$user = \wp_get_current_user();

		// Extract Correct Shop Parent ID from Logged in User.
		$xprofile_field_num = $this->get_instance( SiteDefaults::class )->defaults( 'xprofile_storefront_field' );
		$my_vendor_id       = \get_user_meta( $user->id, '_wcfm_vendor', true );// this filter returns staff - if not staff we add owner ID.
		if ( ! $my_vendor_id ) {
			$my_vendor_id = \get_current_user_id();
		}

		$layout_id = $this->get_instance( ShortCodeConstructor::class )->xprofile_build( $xprofile_field_num, 0, $my_vendor_id );

		// Set Display template to Boardroom in case Profile setting is blank.
		if ( ! $layout_id ) {
			$layout_id = $this->get_instance( SiteDefaults::class )->defaults( 'xprofile_storefront_sitedefault' );
		}

		return MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'store', $my_vendor_id ),
			$layout_id,
		)
								->enable_admin()
								->output_shortcode();
	}

	/**
	 * A Shortcode for Personal Meetings Center- Host Entrance
	 * This is used for the Member Backend entry pages to access their preferred Video Layout - it is paired with the personalmeetingguest shortcode
	 * This depends on ultimate membership pro
	 *
	 * @return string
	 */
	public function personal_meeting_host_shortcode() {
		// Establish who is host.
		if ( \is_user_logged_in() ) {
			$user    = \wp_get_current_user();
			$user_id = $user->ID;
		}
		// Reject Invalid Users or Hosts not found/logged it etc.
		if ( ! $user_id ) {
			echo 'User Not Logged In - Can not host meeting <br>';
			return Factory::get_instance( SectionTemplates::class )->meet_signed_out_page_template();
		}
		// Security Engine - blocks room rendering if another setting has blocked it (eg upgrades, site lockdown, or other feature).

		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'pbrhost', SiteDefaults::MODULE_PERSONAL_MEETING_ID, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Parameters.
		$video_template = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		// Build the Room.
				$myvideoroom_app = MyVideoRoomApp::create_instance(
					$this->get_instance( SiteDefaults::class )->room_map( 'userbr', $user_id ),
					$video_template
				)
				->enable_admin();

		// Construct Shortcode Template - and execute.
		$header = Factory::get_instance( SectionTemplates::class )->meet_host_header();

		$shortcode  = $myvideoroom_app->output_shortcode();
		$admin_page = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			$user_id,
			SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM,
			array( 'basic', 'premium' )
		);

		$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
			$user_id,
			SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM
		);
		echo 'I am in host branch';
		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode, $admin_page, $permissions_page );
	}

	/**
	 * A Shortcode for the Boardroom View to Switch by Database Setting - Guest
	 * This is used for the Guest entry or Switch pages to access the Member Selected Video Layout - it is paired with the personalmeetinghost shortcode
	 * It accepts hostname as an argument which it gets from the Guest page URL get request parameter
	 *
	 * @param array $params -- host and invite both passed from users in form at reception. This function passes upstream to Main guest video function
	 *
	 * @return string
	 */
	public function personal_meeting_guest_shortcode( $params = array() ) {
		$host   = $params['host'] ?? htmlspecialchars( $_GET['host'] ?? '' );
		$invite = $params['invite'] ?? htmlspecialchars( $_GET['invite'] ?? '' );

		return $this->boardroom_video_guest( $host, $invite );
	}

	/**
	 * Personal Boardroom Video - Guest entrance.
	 *
	 * @param  string $host - optional user name from parameter passed as url in personal meeting guest shortcode function.
	 * @param  string $invite - optional - invite code from hash generation function.
	 * @return string - the shorcode object.
	 */
	public function boardroom_video_guest( string $host, string $invite ): string {

		// Reject Blank Input.
		if ( ! ( $host ) && ! ( $invite ) ) {
			return Factory::get_instance( SectionTemplates::class )->meet_guest_reception_template();
		}
		// Establish who is host.
		if ( $invite ) {
			$user_id = $this->get_instance( ShortCodeConstructor::class )->invite( $invite, 'in', null );
		} else {
			$user    = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_identifier_string( $host );
			$user_id = $user->ID;
		}
		// Filter out invalid users.
		if ( ! $user_id ) {
			echo 'No Such User or Invite - Please Try Again<br>';
			return Factory::get_instance( SectionTemplates::class )->meet_guest_reception_template();
		}

		// Security Engine - blocks room rendering if another setting has blocked it (eg upgrades, site lockdown, or other feature).

		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'pbrguest', SiteDefaults::MODULE_PERSONAL_MEETING_ID, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		if ( $render_block ) {
			return $render_block;
		}

		// Filter out users trying log into own room as guest.
		$user_checksum = \get_current_user_id();
		if ( \is_user_logged_in() ) {
			if ( $user_checksum === $user_id ) {
				$meet_page = $this->get_instance( \MyVideoRoomExtrasPlugin\Setup\RoomAdmin::class )->get_videoroom_info( 'meet-center', 'url' );
				wp_safe_redirect( $meet_page );
				exit();
			}
		}

		// Get Room Layout and Reception Settings.

		$reception_setting     = $this->get_instance( VideoHelpers::class )->get_enable_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$reception_template    = $this->get_instance( VideoHelpers::class )->get_reception_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_template        = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_reception_state = $this->get_instance( VideoHelpers::class )->get_video_reception_state( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$video_reception_url   = $this->get_instance( VideoHelpers::class )->get_video_reception_url( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );
		$show_floorplan        = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM );

		// Base Room.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'userbr', $user_id ),
			$video_template,
		);
		// Reception.
		if ( $reception_setting && $reception_template ) {
			$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

			if ( $video_reception_state && $video_reception_url ) {

				$myvideoroom_app->set_reception_video_url( $video_reception_url );
			}
		}
		// Floorplan.
		if ( $show_floorplan ) {
			$myvideoroom_app->enable_floorplan();
		}

		// Construct Shortcode Template - and execute.
		$header    = $this->get_instance( SectionTemplates::class )->meet_guest_header();
		$shortcode = $myvideoroom_app->output_shortcode();

		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode );
	}


	/**
	 * A Shortcode for the Management Boardroom View - Member
	 * This is used for the Member admin entry pages to access their preferred Video Layout - it is paired with the sitevideoroomguest function and accessed by the relevant video switch
	 *
	 * @return string
	 */
	public function site_videoroom_member_shortcode() {
		// Reject Logged out Users (cant be admins)- send them to guest entrance.
		if ( ! is_user_logged_in() ) {
			return $this->get_instance( self::class )->site_videoroom_guest_shortcode();
		}

		// Security Engine - blocks room rendering if another setting has blocked it (eg upgrades, site lockdown, or other feature).
		$user_id      = get_current_user_id();
		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'sitevideohost', SiteDefaults::MODULE_SITE_VIDEO_ID, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Room Parameters.
		$video_template  = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'managementbr', 0 ),
			$video_template
		)->enable_admin();

		// Construct Shortcode Template - and execute.
		$header           = Factory::get_instance( SectionTemplates::class )->site_boardroom_host_template();
		$shortcode        = $myvideoroom_app->output_shortcode();
		$admin_page       = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_VIDEO,
			array( 'basic', 'premium' )
		);
		$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_VIDEO
		);

		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode, $admin_page, $permissions_page );
	}

	/**
	 * Site Videroom Guest - .
	 *
	 * A Shortcode for the Management Boardrooms - Guest
	 * This is used for the Guest entry pages to access the Management Meeting Room - it is paired with the sitevideoroomhost shortcode
	 *
	 * @since Version 1
	 */

	public function site_videoroom_guest_shortcode() {

		// Security Engine - blocks room rendering if another setting has blocked it (eg upgrades, site lockdown, or other feature).
		$user_id      = get_current_user_id();
		$render_block = Factory::get_instance( Security::class )->render_block( $user_id, 'sitevideoguest', SiteDefaults::MODULE_SITE_VIDEO_ID, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		if ( $render_block ) {
			return $render_block;
		}

		// Get Parameters for Room Info.
		$user_id               = SiteDefaults::USER_ID_SITE_DEFAULTS;
		$reception_setting     = $this->get_instance( VideoHelpers::class )->get_enable_reception_state( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$reception_template    = $this->get_instance( VideoHelpers::class )->get_reception_template( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$video_template        = $this->get_instance( VideoHelpers::class )->get_videoroom_template( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$video_reception_state = $this->get_instance( VideoHelpers::class )->get_video_reception_state( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$video_reception_url   = $this->get_instance( VideoHelpers::class )->get_video_reception_url( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );
		$disable_floorplan     = $this->get_instance( VideoHelpers::class )->get_show_floorplan( $user_id, SiteDefaults::ROOM_NAME_SITE_VIDEO );

		// Build Base Room.
		$myvideoroom_app = MyVideoRoomApp::create_instance(
			$this->get_instance( SiteDefaults::class )->room_map( 'managementbr', 0 ),
			$video_template,
		);
		// Reception setting.
		if ( $reception_setting ) {
			$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

			if ( $video_reception_state ) {
				$myvideoroom_app->set_reception_video_url( $video_reception_url );
			}
		}
		// Floorplan Disable setting.
		if ( $disable_floorplan ) {
			$myvideoroom_app->disable_floorplan();

		}

		// Construct Shortcode Template - and execute.
		$header    = Factory::get_instance( SectionTemplates::class )->site_boardroom_guest_template();
		$shortcode = $myvideoroom_app->output_shortcode();

		return $this->get_instance( SectionTemplates::class )->shortcode_template_wrapper( $header, $shortcode );
	}





}
