<?php
/**
 * Integration Addin functionality for BuddyPress
 *
 * @package MyVideoRoomPlugin\Modules\BuddyPress
 */

namespace ElementalPlugin\BuddyPress\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Search\Library\GroupSearch;
use ElementalPlugin\Search\Library\SearchAjax;
use ElementalPlugin\Search\Library\SiteSearch;
use ElementalPlugin\Search\Search;
use MyVideoRoomPlugin\Module\BuddyPress\Library\BuddyPressVideo;


/**
 * Class BuddyPress
 */
class BuddyPress {
	const SHORTCODE_TAG = 'elemental_';
	// Constants For Buddypress Video Modules.
	const MODULE_BUDDYPRESS_NAME                   = 'buddypress-module';
	const MODULE_BUDDYPRESS_SLUG                   = 'buddypress';
	const MODULE_BUDDYPRESS_DISPLAY                = 'BuddyPress Settings';
	const MODULE_BUDDYPRESS_ID                     = 434;
	const MODULE_BUDDYPRESS_ADMIN_LOCATION         = '/modules/buddypress/views/view-settings-buddypress.php';
	const MODULE_BUDDYPRESS_VIDEO_SLUG             = 'myvideoroom';
	const MODULE_BUDDYPRESS_GROUP_NAME             = 'buddypress-group-module';
	const MODULE_BUDDYPRESS_GROUP_ID               = 837;
	const MODULE_BUDDYPRESS_USER_NAME              = 'buddypress-user-module';
	const MODULE_BUDDYPRESS_USER_ID                = 956;
	const MODULE_BUDDYPRESS_FRIENDS_NAME           = 'buddypress-friends-module';
	const MODULE_BUDDYPRESS_FRIENDS_ID             = 117;
	const ROOM_NAME_BUDDYPRESS_GROUPS_SITE_DEFAULT = 'site-default-bp-groups';
	const ROOM_NAME_BUDDYPRESS_GROUPS              = 'video-bp-groups';
	const DISPLAY_NAME_BUDDYPRESS_GROUPS           = 'Group ';
	const SETTING_IS_FRIEND                        = 'is_friend';
	const SETTING_DO_NOT_DISTURB                   = 'Do-Not-Disturb';
	const SETTING_STEALTH                          = 'Stealth-Remove-Video';
	const SETTING_DEFAULT_TAB_NAME                 = 'MyVideoRoom';
	const OPTION_BUDDYPRESS_USER_TAB               = 'myvideoroom-buddypress-user-tab';
	const OPTION_BUDDYPRESS_GROUP_TAB              = 'myvideoroom-buddypress-group-tab';

	/**
	 * Initialise On Module Activation
	 * Once off functions for activating Module
	 */
	public function activate_module() {

	}
	/**
	 * Is Buddypress Active - checks if BuddyPress is enabled.
	 *
	 * @return bool
	 */
	public function is_buddypress_available(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'buddypress/bp-loader.php' );
	}


	/**
	 * Is User Module Active - checks if Module of User Rooms is enabled.
	 *
	 * @return bool
	 */
	public function is_group_module_available() {
		$buddypress = $this->is_buddypress_available();
		if ( ! $buddypress ) {
			return false;
		}
		if ( function_exists( 'bp_is_active' ) && \bp_is_active( 'groups' ) ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Can Module Be Activated- checks if BuddyPress is enabled, and checks Personal Video Module state.
	 *
	 * @return bool
	 */
	public function can_module_be_activated():bool {
		return $this->is_buddypress_available();
	}
	/**
	 * Install - initialisation function of class - used to call Shortcodes or main class functions.
	 *
	 * @return void|null.
	 */
	public function init() {
		if ( ! $this->is_buddypress_available() ) {
			return null;
		}
		//$this->setup_root_nav_action();
		add_action( 'bp_setup_nav', array( $this, 'setup_group_nav_action' ) );

	}


	/**
	 * Naming Screen Functions Section - This section hosts the page construction templates for each named clickable function.
	 * Insert each function that the constructor above instantiates inside each separate template function
	 */


	/**
	 * Main Constructor - Adds Tabs for the User Video Room.
	 *
	 * @return null|void
	 */
	public function setup_root_nav_action() {
		if ( ! $this->is_buddypress_available() ) {
			return null;
		}

			$tab_name = \get_option( 'myvideoroom-buddypress-user-tab' );
			if ( ! $tab_name ) {
				$tab_name = self::SETTING_DEFAULT_TAB_NAME;
			}
			// Setup My Video Tab. Section 1.
			\bp_core_new_nav_item(
				array(
					'name'                    => $tab_name,
					'slug'                    => self::MODULE_BUDDYPRESS_VIDEO_SLUG,
					'show_for_displayed_user' => true,
					'screen_function'         => array( $this, 'myvideo_render_main_screen_function' ),
					'item_css_id'             => 'far fa-address-card',
					'position'                => 1,
				)
			);
	}
	/**
	 * User Main Screen Function.
	 * This function loads all tabs and subtabs by mounting template and then action.
	 *
	 * @return null|void
	 */
	public function myvideo_render_main_screen_function() {
		add_action( 'bp_template_content', array( Factory::get_instance( BuddyPressVideo::class ), 'bp_boardroom_video_host' ) );
		\bp_core_load_template( \apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * Default User Room Display Function.
	 *
	 * @return null|void
	 */
	public function setup_group_nav_action() {
		// Checks for existence of Groups in BP, module enabled etc.
		if ( ! $this->is_group_module_available() ) {
			return null;
		}
		global $bp;
		if ( $bp->groups ) {
			$tab_name = 'sssdf';
			$slug = 'ggrs';

		}
		$slug = bp_get_groups_slug();
		// Determine user to use.
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			$user_domain = false;
		}
		$groups_link = trailingslashit( $user_domain . $slug );
		\bp_core_new_subnav_item(
			array(
				'name'            => __( 'Global Directory Groups', 'myvideoroom' ),
				'slug'            => 'findgroups',
				'parent_url'      => $groups_link,
				'parent_slug'     => $slug,
				'screen_function' => array( $this, 'find_group_screen_function' ),
				'position'        => 30,
			)
		);
		if ( $bp->groups && $bp->groups->current_group ) {
		// Setup My Video Tab. Section 1.

		}
	}

	/**
	 * This function renders the group Video Meet tab function
	 *
	 * @return void
	 */
	public function find_group_screen_function():void {
		\add_action( 'bp_template_content', array( $this, 'render_find_group_screen' ) );
		\bp_core_load_template( \apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * Render Group Find Page
	 *
	 * @return ?string
	 */
	public function render_find_group_screen():?string {

		\ob_start();
		echo Factory::get_instance( GroupSearch::class )->elemental_group_shortcode();
		return \ob_end_flush();

	}
}
