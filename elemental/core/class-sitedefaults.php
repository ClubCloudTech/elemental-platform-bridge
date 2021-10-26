<?php

/**
 * Default settings and params
 *
 * @package ElementalPlugin\Core
 */

namespace ElementalPlugin\Core;

use ElementalPlugin\UltimateMembershipPro\MembershipLevel;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\WoocommerceBookings\ShortCodeConstructor;
use ElementalPlugin\WoocommerceBookings\WCHelpers;
use ElementalPlugin\WCFM\WCFMHelpers;
use ElementalPlugin\Factory;
use ElementalPlugin\Dao\RoomMap;

/**
 * Class SiteDefaults
 */
class SiteDefaults extends Shortcode {



	// For Personal Video Module.
	const ROOM_NAME_PERSONAL_BOARDROOM              = 'personal-video-room';
	const ROOM_NAME_PERSONAL_BOARDROOM_SITE_DEFAULT = 'site-default-personal-boardroom';

	// All Up Site Default Master Setting.
	const ROOM_NAME_SITE_DEFAULT = 'site-default-settings';
	const ROOM_NAME_SITEBUILDER  = 'site-builder-room';
	// For WooCommerce Bookings Module.
	const ROOM_NAME_BOOKINGS_SINGLE_SITE_DEFAULT = 'site-default-woocommerce-bookings';
	const ROOM_NAME_BOOKINGS_SINGLE              = 'woocommerce-bookings';
	// For Buddypress Module.
	const ROOM_NAME_BUDDYPRESS_GROUPS_SITE_DEFAULT = 'site-default-bp-groups';
	const ROOM_NAME_BUDDYPRESS_GROUPS              = 'video-bp-groups';
	// For WCFM Module.
	const STORE_NAME_WCFM_VIDEO              = 'wcfm-store-video';
	const STORE_NAME_WCFM_VIDEO_SITE_DEFAULT = 'site-default-wcfm-store-video';

	// Default User ID to Use for Room Site Defaults .
	const USER_ID_SITE_DEFAULTS = 1;

	// Default Database Table Names.
	const TABLE_NAME_MODULE_CONFIG         = 'myvideoroom_extras_module_config';
	const TABLE_NAME_ROOM_MAP              = 'myvideoroom_extras_room_post_mapping';
	const TABLE_NAME_USER_VIDEO_PREFERENCE = 'myvideoroom_extras_user_video_preference';
	const TABLE_NAME_SECURITY_CONFIG       = 'myvideoroom_extras_security_config';

	// Module Names and IDs.
	const MODULE_DEFAULT_VIDEO_NAME = 'default-video-module';
	const MODULE_DEFAULT_VIDEO_ID   = 1;

	// For Site Video Module.
	const MODULE_SITE_VIDEO_NAME       = 'site-video-module';
	const MODULE_SITE_VIDEO_ID         = 2;
	const MODULE_SITE_VIDEO_ADMIN_PAGE = 'admin-settings-sitevideo';
	const MODULE_SITE_VIDEO_DISPLAY    = 'Site Video Room Settings';
	const ROOM_NAME_SITE_VIDEO         = 'site-video-room';
	const ROOM_TITLE_SITE_VIDEO        = 'Video Main Room';
	const ROOM_SLUG_SITE_VIDEO         = 'sitevideo';
	const ROOM_SHORTCODE_SITE_VIDEO    = '[mvr_sitevideoroom]';

	// For Meet Center Module.
	const SITE_PAGE_MEETING_CENTER        = 'meet-center';
	const MODULE_PERSONAL_MEETING_NAME    = 'personal-meeting-module';
	const MODULE_PERSONAL_MEETING_ID      = 3;
	const ROOM_NAME_PERSONAL_MEETING      = 'meet-center';
	const ROOM_TITLE_PERSONAL_MEETING     = 'Video Meetings';
	const ROOM_SLUG_PERSONAL_MEETING      = 'meet';
	const ROOM_SHORTCODE_PERSONAL_MEETING = '[mvr_meetswitch]';

	// For Bookings Page.
	const MODULE_WC_BOOKINGS_NAME        = 'wcbookings-module';
	const MODULE_WC_BOOKINGS_ID          = 5;
	const SITE_PAGE_BOOKINGS_CENTER      = 'bookings-center';
	const ROOM_NAME_BOOKINGS_CENTER      = 'bookings-center';
	const ROOM_TITLE_BOOKINGS_CENTER     = 'Video Bookings';
	const ROOM_SLUG_BOOKINGS_CENTER      = 'bookings';
	const ROOM_SHORTCODE_BOOKINGS_CENTER = '[mvr_bookingctrswitch]';

	// For Room Builder.
	const ROOM_NAME_ROOM_BUILDER      = 'room-builder';
	const ROOM_TITLE_ROOM_BUILDER     = 'Build Your Room';
	const ROOM_SLUG_ROOM_BUILDER      = 'visualiser';
	const ROOM_SHORTCODE_ROOM_BUILDER = '[mvr_visualiser]';

	const MODULE_BUDDYPRESS_NAME = 'buddypress-module';
	const MODULE_BUDDYPRESS_ID   = 4;

	const MODULE_BUDDYPRESS_GROUP_NAME = 'buddypress-group-module';
	const MODULE_BUDDYPRESS_GROUP_ID   = 8;

	const MODULE_BUDDYPRESS_USER_NAME = 'buddypress-user-module';
	const MODULE_BUDDYPRESS_USER_ID   = 9;

	const MODULE_BUDDYPRESS_FRIENDS_NAME = 'buddypress-friends-module';
	const MODULE_BUDDYPRESS_FRIENDS_ID   = 11;

	const MODULE_WCFM_NAME = 'wcfm-module';
	const MODULE_WCFM_ID   = 6;

	const MODULE_TEMPLATES_NAME = 'templates-module';
	const MODULE_TEMPLATES_ID   = 7;

	const MODULE_SECURITY_NAME       = 'security-module';
	const MODULE_SECURITY_ID         = 10;
	const MODULE_SECURITY_ADMIN_PAGE = 'admin-settings-security';
	const MODULE_SECURITY_DISPLAY    = 'Room Security';


	/**
	 * Install the shortcodes
	 */
	public function install() {
		$this->add_shortcode( 'display', array( $this, 'display_defaults' ) );
		$this->add_shortcode( 'logo', array( $this, 'display_logo' ) );
		$this->add_shortcode( 'pageowner', array( $this, 'page_owner' ) );
	}

	public function displayname() {
		$display_name = \bp_get_displayed_user_fullname();
		$user_roles   = $this->get_instance( UserRoles::class );

		if ( ! $display_name ) {
			$user = \wp_get_current_user();

			if ( $user_roles->is_wcfm_vendor() ) {
				$store_user = \wcfmmp_get_store( $user->ID );
				$store_info = $store_user->get_shop_info();

				return $store_info['store_name'];
			} else {
				$display_name = $user->display_name;
			}
		}

		return $display_name;
	}

	// This function checks if we are in MVR/JC - or any other site

	public function is_mvr() {
		$site_title = get_bloginfo( 'name' );

		if ( $site_title === 'Just Coach' || $site_title === 'My Video Room' ) {
			return true;
		} else {
			return false;
		}
	}

	// This function checks if WCFM is enabled in Site

	public function is_wcfm_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'wc-frontend-manager/wc_frontend_manager.php' ) ) {
			// plugin is active
			return true;
		} else {
			return false;
		}
	}

	// This function checks if Elementor is Active in Site

	public function is_elementor_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			// plugin is active
			return true;
		} else {
			return false;
		}
	}


	// This function checks if BuddyPress is Active in Site

	public function is_buddypress_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
			// plugin is active
			return true;
		} else {
			return false;
		}
	}


	// This function checks if Woocommerce Bookings is Active in Site

	public function is_woocommerce_bookings_active() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'woocommerce-bookings/woocommerce-bookings.php' ) ) {
			// plugin is active
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Determines premium users versus normal
	 * This is used for both members and guests and switches room permissions and options seamlessly
	 * It accepts hostname or invite as an argument which it gets from the page URL get request parameter
	 */
	public function is_premium_check( $owner_id = '' ) {
		if ( ! $owner_id ) {
			$owner_id = \bp_loggedin_user_id();
		}

		$user = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $owner_id );

		$membership_level = \get_user_meta( $user->id, 'ihc_user_levels' );
		$memlev           = explode( ',', $membership_level[0] );
		$array_count      = count( $memlev );
		// Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
		for ( $x = 0; $x <= $array_count - 1; $x++ ) {
			switch ( $memlev[ $x ] ) {
				case MembershipLevel::BUSINESS:
					return true;
				case MembershipLevel::PREMIUM:
					return true;
				case MembershipLevel::BASIC:
					// not needed in this tree- but added for compatibility.
					return false;
				case MembershipLevel::VENDOR_STAFF:
					// Vendor Staff.
					// Get Owner Store ID -and check their subscription Level.
					$parent_id = $this->get_instance( WCFMHelpers::class )->staff_to_parent( $owner_id );
					return ! ! $this->is_premium_check( $parent_id );
			}
		}

		return false;
	}

	/**
	 * Find Correct Page Owner
	 *
	 * @return array|int|mixed|string|null Page Owner ID
	 */
	public function page_owner() {
		// First Try BuddyPress

		$user_id = \bp_displayed_user_id();
		if ( $user_id ) {
			$owner_id = $this->get_instance( WCFMHelpers::class )->staff_to_parent( $user_id );

			return $owner_id;
		}

		$store_name = urldecode( get_query_var( get_option( 'wcfm_store_url' ) ) );
		$user       = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_slug( $store_name );
		$owner_id   = $user->ID;

		if ( $owner_id == '' ) { // Next try WCFM
			global $WCFM;
			global $post;
			$postID   = $post->ID;
			$owner_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $postID );
			// $postID = get_the_ID( ).get_queried_object_id( ).$owner_id;
			// echo $postID;
			// echo $owner_id;
		}
		// Finally Try Post Owner
		if ( $owner_id == '' ) {
			$owner_id = \get_post_field( 'post_author', \get_queried_object_id() );
		}

		return $owner_id;
	}


	/**
	 * A constructor for XProfile Calls centrally
	 *
	 * @param $type
	 * @param $input_id
	 *
	 * @return array|false|int|mixed|string|void|null
	 */
	public function get_layout_id( $type, $input_id ) {
		if ( ! $type ) {
			return 'ERR: CC103 - No Xprofile Type Provided';
		}

		switch ( $type ) {

			case 'storeswitch':
				$fieldnum         = $this->defaults( 'store_template' );
				$xprofile_setting = \xprofile_get_field_data( $fieldnum, $input_id );
				if ( $xprofile_setting == '' ) {
					$xprofile_setting = $this->defaults( 'xprofilesitestoredefault' );
				}

				return $xprofile_setting;

			case 'pbrreception':
				$field             = $this->defaults( 'xprofilebreakoutprivacy', $input_id );
				$reception_setting = \xprofile_get_field_data( $field, $input_id );
				// echo 'Reception-Setting->'.$reception_setting. '-Field-returned->' .$field.'in-id->'.$input_id.'ipc->'.$this->get_instance( SiteDefaults::class  )->is_premium_check( $input_id );

				if ( ! $reception_setting ) {
					$reception_setting = \xprofile_get_field_data( $this->defaults( 'xprofilebreakoutprivacysitedefault' ), 1 ); // get site details from user1's backup site field
				}

				return ( 'No' !== $reception_setting );

			case 'pbr_reception_template':
				$xprofile_setting = \xprofile_get_field_data( $this->defaults( 'pbr_reception_template' ), $input_id );
				if ( $xprofile_setting == '' ) {
					$xprofile_setting = \xprofile_get_field_data( $this->defaults( 'reception_template_sitedefault' ), 1 ); // get site details from user1's backup site field
				}

				return $xprofile_setting;
				break;

			case 'store_xprofile':
				$fieldnum         = $this->defaults( 'xprofile_storefront_field', $input_id );
				$xprofile_setting = \xprofile_get_field_data( $fieldnum, $input_id );
				if ( $xprofile_setting == '' ) {
					$xprofile_setting = $this->defaults( 'xprofile_storefront_sitedefault' );
				}

				return $xprofile_setting;
				break;

			case 'parent_security':
				$fieldnum         = $this->defaults( 'parent_security' );
				$xprofile_setting = \xprofile_get_field_data( $fieldnum, $input_id );
				if ( $xprofile_setting == '' ) {
					return 'No';
				}

				return $xprofile_setting;
				break;

			case 'store_reception_template':
				$fieldnum         = $this->defaults( 'store_reception_template' );
				$xprofile_setting = \xprofile_get_field_data( $fieldnum, $input_id );
				if ( $xprofile_setting == '' ) {
					$xprofile_setting = $this->defaults( 'reception_template_sitedefault' );
				}

				return $xprofile_setting;
				break;

			case 'store_privacy':
				// To be deleted when Tested @TODO Fred- replaced with direct calls

				$fieldnum         = $this->defaults( 'xprofile_storefront_reception', $input_id );
				$xprofile_setting = \xprofile_get_field_data( $fieldnum, $input_id );
				if ( $xprofile_setting == '' ) {
					$xprofile_setting = $this->defaults( 'xprofile_storefront_reception_sitedefault' );
				}
				if ( $xprofile_setting == 'No' ) {
					$xprofile_setting = null;
				} else {
					$xprofile_setting = 'reception=true';
				}

				return $xprofile_setting;
				break;

			case 'bookings':
				$store_slug = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $input_id, 'store_slug', 0 );

				return 'Booking-' . $store_slug . '-' . $input_id;
				break;

			case 'group':
				$group     = groups_get_group( array( 'group_id' => $input_id ) );  // get group by ID
				$groupname = $group->slug;

				return 'Group-' . $groupname . '-Space';
				break;

			case 'mvr':
				$user       = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $input_id );
				$user_roles = $this->get_instance( UserRoles::class, array( $user ) );

				// IF staff member - then replace the ID with Owner ID.
				if ( $user && $user_roles->is_wcfm_shop_staff() ) {
					$parent_id  = $user->_wcfm_vendor;
					$user_field = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $parent_id );
					$input_id   = $parent_id;
				}
				$displayid    = $user_field->display_name;
				$output       = preg_replace( '/[^A-Za-z0-9\-]/', '', $displayid ); // remove special characters from username
				$outmeetingid = $this->get_instance( ShortCodeConstructor::class )->invite( $input_id, 'user', null );

				return 'Space-' . $output . '-' . $outmeetingid;
				break;
		}
	}

	/**
	 * Stores Site Default Parameters to Use
	 * Video functions call this function to get default roonm names to ensure all generate consistently
	 */
	public function defaults( $type, $input = '' ) {
		switch ( $type ) {
			case 'numberupcomingbookings': // Number of Bookings to enter in header switches
				return 2;
			break;
			case 'timewindowfilter': // Time window to look for a booking to put a room monitor in- so 12 hours ahead is the max time a switch will look to put a room monitor in
				return 43200;  // 12*60*60 is 12 hours in seconds;
				break;
				// Store Main Video Room
			case 'xprofile_storefront_sitedefault':
				return \xprofile_get_field_data( 2560, 1 ); // get site details from user1's backup site field
				break;
			case 'xprofile_storefront_field': // The field setting for Video Storefront
				if ( $this->is_premium_check( $input ) == true ) {
					return 569;
				} else {
					return 2735;
				}
				break;
			case 'xprofile_storefront_reception': // for selecting whether or not reception is used at all
				if ( $this->is_premium_check( $input ) == true ) {
					return 2751;
				} else {
					return 2748;
				}
				break;
				// @TODO Fred - plumb into a new function below that creates a new room storage setting for Site Defaults
			case 'xprofile_storefront_reception_sitedefault': // for selecting whether or not reception is used at all - fallback
				return \xprofile_get_field_data( 2997, 1 );
				break;
				// @TODO Fred - plumb into a new function below that creates a new room storage setting for Site Defaults
			case 'reception_template_sitedefault': // for selecting the template reception will use
				return \xprofile_get_field_data( 3109, 1 );
				break;

				// Account Security Setting
			case 'parent_security':
				return 3078;
				break;

				// Store Template Setting - Only affects Premium Customers
			case 'store_template':
				return 2073;  // Field in Group 5
				break;
			case 'xprofilesitestoredefault':
				return \xprofile_get_field_data( 2815, 1 ); // get site details from user1's backup site field
				break;
			case 'store_reception_template':
				return 3113;
				break;

				// Breakout Rooms ( Meeting Room )
			case 'xprofilebreakout':
				if ( $this->is_premium_check( $input ) == true ) {
					return 801;
				} else {
					return 2922;
				}
				break;
			case 'xprofilebreakoutsitedefault':
				return 2544;  // Side Default ( fallback )  for Video Selection for Personal Boardroom ( Premium )
				break;
			case 'xprofilebreakoutprivacy':
				if ( $this->is_premium_check( $input ) == true ) {
					return 2775;
				} else {
					return 2871;
				}
				break;
			case 'xprofilebreakoutprivacysitedefault': // Sets the default on or off site wide privacy setting in fallback case
				return 2941;  // 2941 is the Setting in xprofile for the Site default privacy setting of User 1
				break;
			case 'pbr_reception_template': // Sets the default Appearance of Reception
				return 3120;
				break;

				// Taxonomies
			case 'video_storefront': // The Friendly Name Video Storefront Displays
				return 'Video Space';
				break;
			case 'video_storefront_slug': // The Slug that Video Storefront uses to render
				return 'videospace';
				break;
			case 'staff_storefront': // The Friendly Name Connections Page Displays
				return 'Connections';
				break;
			case 'staff_storefront_slug': // The Slug that Connections Page uses to render
				return 'connections';
				break;
			case 'marketplace_name': // Friendly Written name ( not slug ) that we use for storefront marketplaces
				return get_option( 'wcfm_store_url' );
				break;
			case 'go': // The field setting for Storefront
				$post_slug = get_post_field( 'post_name', 23335 );

				return $post_slug;
				break;
			case 'manage_ctr_name': // The Name of the Manage Centre that is used.
				$post_slug = get_post_field( 'post_name', 29701 );

				return $post_slug;
				break;
			case 'staff_name': // How We Refer to Staff
				return 'Members';
				break;
			case 'store_taxonomy': // How we Refer to Store Settings
				return 'Family Space ';
				break;
			case 'account_type': // How we Refer to Store Settings
				return 'Family';
				break;
			case 'overview_slug': // How we Refer to Tab 'Overview' used in Youzer BP tabs
				return 'overview';
				break;
			case 'overview_name': // How we Refer to Tab 'Overview' used in Youzer BP tabs
				return 'Overview';
				break;

				// URLs
			case 'marketplace_url': // URL of the Marketplace for the stores that have WCFM listings
				return get_site_url() . '/' . get_option( 'wcfm_store_url' ) . '/';
				break;
			case 'profile_url': // Returns the name and location of the profile in buddypress
				return bp_core_get_user_domain( get_current_user_id() );
				break;
			case 'profile_staff_url': // Returns the name and location of the profile in buddypress
				return bp_core_get_user_domain( $input );
				break;
			case 'widget_setting': // Returns the name and location of the profile in buddypress
				return bp_core_get_user_domain( $input ) . 'widgets';
				break;

			case 'portfolio': // Returns the name and location of the profile in buddypress
				return bp_core_get_user_domain( $input ) . $this->defaults( 'overview_slug' );
				break;
			case 'portfolio_render': // Returns the name and location of the profile in buddypress
				return get_site_url() . '/' . 'tf20/?id=' . $input;
				break;
			case 'marketplace_landing_url': // Returns the name and location of the profile in buddypress
				$post_slug = get_post_field( 'post_name', 26523 );

				return get_site_url() . '/' . $post_slug;
				break;
			case 'videospacebasicurl': // Returns URL of a private video space - NOTE this must be set manually in User under the video space tab ( as you can't store programmatically with them )
				$base = bp_core_get_user_domain( bp_displayed_user_id() );

				return $base . 'videospace';
				break;
			case 'videospacestaffurl': // Returns URL of a private video space - NOTE this must be set manually in User under the video space tab ( as you can't store programmatically with them )
				$base = $this->get_instance( WCHelpers::class )->get_my_store( 'url' );

				return $base . 'videospace';
				break;
			case 'product_iframe': // Returns URL of a private video space - NOTE this must be set manually in User under the video space tab ( as you can't store programmatically with them )
				$url = get_site_url() . '/tf19/?id=' . $input;

				return $url;
				break;
			case 'currentstoremenu': // Returns URL of a store
				$base = $this->get_instance( WCHelpers::class )->get_my_store();
				if ( $base == '' ) {
					$base = $this->get_instance( WCHelpers::class )->get_my_store( 'visitor' );

					return $base . 'videospace';
				} else {
					return $this->get_instance( WCHelpers::class )->get_my_store( 'url' );
				}

				break;
			case 'bpstore': // Returns URL of a store.
				$base   = $this->defaults( 'marketplace_url' );
				$family = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( 0, 'store_slug', $this->get_instance( WCFMHelpers::class )->staff_to_parent( $input ) );

				return $base . $family;
				break;
			case 'currentstore':
				if ( $base == '' ) {
					$base = $this->get_instance( WCHelpers::class )->get_my_store( 'visitor' );

					return $base . 'videospace';
				} else {
					return $this->get_instance( WCHelpers::class )->get_my_store( 'url' );
				}

				break;
			case 'siteurl': // Returns URL of a private video space - NOTE this must be set manually in User under the video space tab ( as you can't store programmatically with them )
				return get_site_url();
				break;
			case 'gourl': // The field setting for Storefront
				$post_slug = get_post_field( 'post_name', 23335 );

				return get_site_url() . '/' . $post_slug;
				break;
		}
	}

	/**
	 * Wrapper to encapsulate Default function into Shortcode
	 */
	public function display_defaults( $params = array() ) {
		extract(
			shortcode_atts(
				array(
					'type' => '',
				),
				$params
			)
		);
		if ( $type == '' ) {
			return null;
		}

		return $this->defaults( $type );
	}

	/**
	 * Generates all default Room Names for All Functions that use Video Rooms
	 * Video functions call this function to get default room names to ensure all generate consistently
	 *
	 * @param $type
	 * @param $input_id
	 *
	 * @return string
	 */
	public function room_map( $type, $input_id = null ) {
		if ( ! $type ) {
			return 'ERR: CC101 - No Room Type Provided';
		}

		switch ( $type ) {
			case 'managementbr':
				return $this->get_instance( FiltersUtilities::class )->name_split( ( get_bloginfo( 'name' ) ) ) . '-Management-Boardroom'; // namesplit gets first letters of site title sent

			case 'userbr':
				$user_field = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $input_id );

				$displayid    = $user_field->display_name;
				$output       = preg_replace( '/[^A-Za-z0-9\-]/', '', $displayid ); // remove special characters from username
				$outmeetingid = $this->get_instance( ShortCodeConstructor::class )->invite( $input_id, 'user', null );

				return 'Space-' . $output . '-' . $outmeetingid;

			case 'store':
				// OK
				$store_name = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( 0, 'store_slug', $input_id );
				// Handling Store not having been setup yet, we hash the store owner ID
				if ( is_numeric( $store_name ) || $store_name == '' ) {
					return $this->get_instance( MenuHelpers::class )->nice_name( (int) $input_id ) . \ElementalPlugin\Library\MeetingIdGenerator::get_meeting_hash_from_user_id( $input_id );
				}
				$output = preg_replace( '/[^A-Za-z0-9\-]/', '', $store_name ); // remove special characters from username

				return 'Space-' . $output;

			case 'bookings':
				$store_slug = $this->get_instance( WCHelpers::class )->orderinfo_by_booking( $input_id, 'store_slug', 0 );

				return 'Booking-' . $store_slug . '-' . $input_id;

			case 'group':
				global $bp;

				return 'Group-' . $bp->groups->current_group->slug . '-Space';

			case 'mvr':
				$user       = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( (int) $input_id );
				$user_roles = $this->get_instance( UserRoles::class, array( $user ) );

				// IF staff member - then replace the ID with Owner ID
				if ( $user && $user_roles->is_wcfm_shop_staff() ) {
					$parent_id = $user->_wcfm_vendor;

					$user_field = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $parent_id );

					$input_id = $parent_id;
				}
				$displayid    = $user_field->display_name;
				$output       = preg_replace( '/[^A-Za-z0-9\-]/', '', $displayid ); // remove special characters from username
				$outmeetingid = $this->get_instance( ShortCodeConstructor::class )->invite( $input_id, 'user', null );

				return 'Space-' . $output . '-' . $outmeetingid;
		}
	}

	// Club Cloud - a function to reutn the URL of Site image or another picture
	public function display_logo() {
		$url = '/wp-content/uploads/2021/01/cropped-Silver.png';

		return '<div class="yz-primary-nav-img" style="background-image: url( ' . $url . '  )"></div>';
	}

	/**
	 * Club Cloud - a function to return the Slug of meet center page - in case it is renamed
	 */
	public function meet_center_slug() {
		$post_id = Factory::get_instance( RoomMap::class )->read();
		$slug    = get_post_field( 'post_name', $post_id );
		return $slug;
	}
}
