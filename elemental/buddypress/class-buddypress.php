<?php

/**
 * Addon functionality for BuddyPress
 *
 * @package ElementalPlugin\BuddyPress
 */

namespace ElementalPlugin\BuddyPress;

use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Shortcode\MyVideoRoomApp;
use ElementalPlugin\Buddypress\BuddyPressVideo;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Factory;
use ElementalPlugin\DAO\SecurityVideoPreference as SecurityVideoPreferenceDAO;
use ElementalPlugin\Shortcode\SecurityVideoPreference;
use ElementalPlugin\Shortcode\UserVideoPreference;
use ElementalPlugin\WCFM\WCFMHelpers;

/**
 * Class BuddyPress
 */
class BuddyPress extends Shortcode {





	/**
	 * Install - initialisation function of class - used to call Shortcodes or main class functions.
	 *
	 * @return void
	 */
	public function install() {
		$this->add_shortcode( 'bpgroupname', array( $this, 'bp_groupname_shortcode' ) );
		$this->add_shortcode( 'displayportfolio', array( $this, 'display_portfolio_shortcode' ) );

		$is_module_enabled       = Factory::get_instance( ModuleConfig::class )->read_enabled_status( SiteDefaults::MODULE_BUDDYPRESS_ID );
		$is_user_module_enabled  = Factory::get_instance( ModuleConfig::class )->read_enabled_status( SiteDefaults::MODULE_BUDDYPRESS_USER_ID );
		$is_group_module_enabled = Factory::get_instance( ModuleConfig::class )->read_enabled_status( SiteDefaults::MODULE_BUDDYPRESS_GROUP_ID );

		if ( $is_module_enabled ) {
			if ( $is_user_module_enabled ) {
				add_action( 'bp_init', array( $this, 'setup_root_nav_action' ), 1000 );
			}

			if ( $is_group_module_enabled ) {
				add_action( 'bp_init', array( $this, 'setup_group_nav_action' ) );
			}
		}
	}



	/**
	 * Enable - enables BuddyPress actions
	 *
	 * @return void
	 */
	public function enable() {
		add_action( 'bp_init', array( $this, 'setup_root_nav_action' ), 1000 );
		add_action( 'bp_init', array( $this, 'setup_group_nav_action' ) );
	}

	/**
	 * Disable- disables BuddyPress initialisation actions.
	 *
	 * @return void
	 */
	public function disable() {
		remove_action( 'bp_init', array( $this, 'setup_root_nav_action' ), 1000 );
		remove_action( 'bp_init', array( $this, 'setup_group_nav_action' ) );
	}

	/**
	 * Naming Screen Functions Section - This section hosts the page construction templates for each named clickable function.
	 * Insert each function that the constructor above instantiates inside each separate template function
	 * Example - if the tab above has cc_group_video_meeting_content as the screen function - the rendering function cc_group_video_meeting_content must be built below for the tab to render content
	 */

	/**
	 * Renders the Video Meeting tab Content that is a child of groups
	 *
	 * @param array $parameters- as its a switching function multiple types input.
	 *
	 * @return bool|string|true|null
	 */
	public function bp_groupname_shortcode( $params = array() ) {
		global $bp;

		$type = $params['type'] ?? 'name';

		$group_link = $bp->root_domain . '/' . \bp_get_groups_root_slug() . '/' . $bp->groups->current_group->slug . '/';

		switch ( $type ) {
			case 'name':
				return $bp->groups->current_group->name;
			case 'url':
				return $group_link;

			case 'ownerid':
				return $bp->groups->current_group->creator_id;
			case 'groupid':
				return $bp->groups->current_group->id;
			case 'status':
				return $bp->groups->current_group->status;
			case 'description':
				return $bp->groups->current_group->description;
			case 'banner':
				if ( \bp_has_groups( $user_id ) ) {
					while ( \bp_groups() ) {
						\bp_the_group();

						// Get the Cover Image.
						$group_cover_image_url = \bp_attachments_get_attachment(
							'url',
							array(
								'object_dir' => 'groups',
								'item_id'    => \bp_get_group_id(),
							)
						);

						echo '<img src="' . $group_cover_image_url . ' ">';
					}
				}

				break;

			case 'permissions':
				if ( \groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id )
				|| \groups_is_user_mod( $bp->loggedin_user->id, $bp->groups->current_group->id )
				|| \is_super_admin()
				|| \is_network_admin()
				) {
					return true;
				}

				break;

			case 'guest':
				$xprofile_field   = 2483;
				$xprofile_setting = \xprofile_get_field_data( $xprofile_field, $bp->groups->current_group->creator_id );

				if ( ! $xprofile_setting ) {
					// going to site level backup field.
					$xprofile_setting = \xprofile_get_field_data( 2502, 1 );
				}

				// this setting comes from field 2555 in buddypress from the creator.
				$reception = \xprofile_get_field_data( 2555, $bp->groups->current_group->creator_id );

				$myvideoroom_app = MyVideoRoomApp::create_instance(
					$this->get_instance( SiteDefaults::class )->room_map( 'group', $bp->groups->current_group->id ),
					$xprofile_setting,
				);

				if ( $reception ) {
					$myvideoroom_app->enable_reception();
				}

				return $myvideoroom_app->output_shortcode();

			case 'ownerbutton':
				if ( ! \is_user_logged_in() ) {
					// dont process signed out users.
					return null;
				}

				// To check if user is group owner.
				$user_id    = $bp->loggedin_user->id;
				$creator_id = $bp->groups->current_group->creator_id;

				if ( $creator_id === $user_id ) {
					return \do_shortcode( '[elementor-template id="32982"]' );
				} else {
					return \do_shortcode( '[elementor-template id="33018"]' );
				}

			case 'ownername':
				$owner_id = $bp->groups->current_group->creator_id;

				$owner_object = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $owner_id );
				$display_name = $owner_object->display_name;

				return $display_name;
		}
	}
	/**
	 * Permissions Helpers
	 * These functions provide support to tabs based on user status
	 */

	/**
	 * Bp_is_user_admin - returns admin status of a user in a group.
	 *
	 * @param  mixed $group_id - required.
	 * @param  mixed $user_id  - optional.
	 * @return bool
	 */
	public function bp_is_user_admin( $group_id, $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$is_admin          = false;
		$user_groups_admin = bp_get_user_groups(
			$user_id,
			array(
				'is_admin' => true,
			)
		);

		if ( isset( $user_groups_admin[ $group_id ] ) ) {
			$is_admin = true;
		}
		return $is_admin;
	}

	/**
	 * Bp_is_user_moderator - returns whether a user id is a moderator of a BuddyPress Group
	 *
	 * @param  mixed $group_id - required.
	 * @param  mixed $user_id  - not required.
	 * @return bool
	 */
	public function bp_is_user_moderator( $group_id, $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$is_mod          = false;
		$user_groups_mod = bp_get_user_groups(
			$user_id,
			array(
				'is_mod' => true,
			)
		);

		if ( isset( $user_groups_mod[ $group_id ] ) ) {
			$is_mod = true;
		}
		return $is_mod;
	}

	/**
	 * Bp_is_user_member - checks whether user is member of a group
	 *
	 * @param  mixed $group_id - required.
	 * @param  mixed $user_id  - optional.
	 * @return bool
	 */
	public function bp_is_user_member( $group_id, $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$is_member          = false;
		$user_groups_member = bp_get_user_groups( $user_id );

		if ( isset( $user_groups_member[ $group_id ] ) ) {
			$is_member = true;
		}
		return $is_member;
	}



	/**
	 * Bp_can_host_group - returns whether user is a host of a group or not
	 *
	 * @param  mixed $group_id required.
	 * @param  mixed $user_id  optional.
	 * @return bool
	 */
	public function bp_can_host_group( $group_id, $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$is_user_admin     = $this->bp_is_user_admin( $group_id, $user_id );
		$is_user_moderator = $this->bp_is_user_moderator( $group_id, $user_id );

		if ( $is_user_admin || $is_user_moderator || is_super_admin() || is_network_admin() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Bp_is_room_active - returns room state from DB
	 *
	 * @param  mixed $room_name - required.
	 * @param  mixed $user_id   - optional.
	 * @return bool
	 */
	public function bp_is_room_active( $room_name, $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$room_disabled = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( $user_id, $room_name, 'room_disabled' );

		if ( $room_disabled ) {
			return false;
		}
		return true;
	}

	/**
	 * Block_friends_display - Handles whether Block non friends setting is enabled, and returns to caller whether to block access to room or not
	 *
	 * @param  mixed $user_id - not required. Will take logged in user if not passed.
	 * @return bool
	 */
	public function block_friends_display( $user_id = null ): bool {
		$site_override         = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		$site_friends_override = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'bp_friends_setting' );

		if ( ! $user_id ) {
			$user_id = \bp_displayed_user_id();
		}
		$visitor_id           = get_current_user_id();
		$friends_status       = \friends_check_friendship_status( $user_id, $visitor_id );
		$user_friends_setting = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM, 'bp_friends_setting' );

		if ( $site_override && $site_friends_override ) {
			$bp_friends_setting = $site_friends_override;
		} else {
			$bp_friends_setting = $user_friends_setting;
		}
		// Controlling Output based on status and overrides above.
		// Are we in Own Profile ?
		if ( $user_id === $visitor_id ) {
			return false;
		}
		// Are We Friends ?
		if ( 'is_friend' === $friends_status ) {
			return false;
		}
		// Is Setting set to Do Not Disturb (in which case Render Block will need to display a template and we fall through here) OR is Setting Allow All ?
		elseif ( '' === $bp_friends_setting || 'Do-Not-Disturb' === $bp_friends_setting ) {
			return false;
		}
		// If none of the above fire the filter.
		return true;
	}



	/**
	 * Main Constructor
	 * - This function loads all tabs and subtabs in one action
	 * - each tab calls a 'screen function' which must be in the screen function section
	 * You can add tabs, and sub tabs here - The parent slug defines if it is a sub navigation item, or a navigation item
	 */
	public function setup_root_nav_action() {
		$hide_tab_from_user = $this->block_friends_display();
		if ( ! $hide_tab_from_user ) {

			// Setup My Video Tab. Section 1
			\bp_core_new_nav_item(
				array(
					'name'                    => 'My Video Room',
					'slug'                    => 'my-video-room',
					'show_for_displayed_user' => true,
					'screen_function'         => array( $this, 'myvideo_render_main_screen_function' ),
					'item_css_id'             => 'far fa-address-card',
					'position'                => 1,
				)
			);
		}
	}


	/**
	 * My Video Room Section 1
	 * - This function loads all tabs and subtabs in one action
	 * - each tab calls a 'screen function' which must be in the screen function section
	 */
	public function myvideo_render_main_screen_function() {
		// add title and content here - last is to call the members plugin.php template.
		\add_action( 'bp_template_content', array( $this, 'bp_myvideo_tab_action' ) );
		\bp_core_load_template( \apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	public function bp_myvideo_tab_action() {
		echo $this->get_instance( BuddyPressVideo::class )->bp_boardroom_video_host();
	}



	/**
	 * Function to display Groups Video Room Tab in every Video Room
	 * This function has an issue with certain Elementor pages that call it - which means it can be disabled to edit the pages.
	 *
	 * @param array $params
	 *
	 * @return false|string
	 */


	public function setup_group_nav_action() {
		global $bp;
		if ( \bp_is_active( 'groups' ) && $bp->groups && $bp->groups->current_group ) {
			$group_link = $bp->root_domain . '/' . $bp->groups->slug . '/' . $bp->groups->current_group->slug . '/';

			\bp_core_new_subnav_item(
				array(
					'name'            => 'Video Room',
					'slug'            => 'video-meeting',
					'parent_url'      => $group_link,
					'parent_slug'     => $bp->groups->current_group->slug,
					'screen_function' => array( $this, 'group_video_main_screen_function' ),
					'user_has_access' => $this->bp_is_room_active( $bp->groups->current_group->slug, $bp->groups->current_group->creator_id ),
					'position'        => 300,
					'item_css_id'     => 'group-css',
				)
			);

			\bp_core_new_subnav_item(
				array(
					'name'            => 'Video Settings',
					'slug'            => 'video-settings',
					'parent_url'      => $group_link,
					'parent_slug'     => $bp->groups->current_group->slug,
					'screen_function' => array( $this, 'group_video_admin_screen_function' ),
					'position'        => 300,
					'user_has_access' => $this->bp_can_host_group( get_current_user_id() ),
					'item_css_id'     => 'group-css',
				)
			);
		}
	}



	public function bp_render_group_settings() {
		global $bp;
		$group_id = $bp->groups->current_group->slug;

		$user_id        = $bp->groups->current_group->creator_id;
		$security_tab   = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
			$user_id,
			$group_id,
			$group_id
		);
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			$user_id,
			$group_id,
			array( 'basic', 'premium' )
		); ?>
		<script type="text/javascript">
			function activateTab2(pageId) {
				var tabCtrl2 = document.getElementById( 'tabCtrl2' );
				var pageToActivate2 = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl2.childNodes.length; i++) {
					var node2 = tabCtrl2.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate2) ? 'block' : 'none';
					}
				}
			}
		</script>

		<ul class="menu">
			<div style="display: flex!important;    justify-content: space-between!important; width: 50%;">
				<a class="cc-menu-header-template" href="javascript:activateTab2( 'page5' )">
					<h2>Room Permissions</h2>Set Security
				</a>
				<a class="cc-menu-header-template" href="javascript:activateTab2( 'page6' )">
					<h2>Video Host Settings</h2>Set Display Settings
				</a>
			</div>
		</ul>

		<div id="tabCtrl2" style="margin-top : 10px; line-height: 2;">
			<div id="page5" style="display: block;">
		<?php
													echo $security_tab;
		?>
			</div>
			<div id="page6" style="display: none;">
		<?php
													echo $layout_setting
		?>
			</div>

		</div>


		<?php
	}







	/**
	 * This function renders the group Video Meet tab function
	 */
	public function group_video_main_screen_function() {
		// add title and content here - last is to call the members plugin.php template.
		\add_action( 'bp_template_content', array( $this, 'group_video_meeting_content_action' ) );
		\bp_core_load_template( \apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * This function renders the Video Meeting tab Content that is a child of Video meet
	 */
	public function group_video_meeting_content_action() {
		return $this->get_instance( BuddyPressVideo::class )->groupmeet_switch();
	}


	/**
	 * Functions to Render Group Admin Panel - Screen Function and Template
	 */


	/**
	 * This function renders the Group Admin Control Panel
	 */
	public function group_video_admin_screen_function() {
		// add title and content here - last is to call the members plugin.php template.
		\add_action( 'bp_template_content', array( $this, 'bp_render_group_settings' ) );
		\bp_core_load_template( \apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}

	/**
	 * This function renders the Video Meeting tab Content that is a child of Video meet
	 */
	public function group_video_admin_content_action() {
		echo $this->get_instance( BuddyPressVideo::class )->groupmeet_switch();
	}

	/**
	 * Function to display the Portfolio area outside of the Buddypress normal nav menu
	 * This function has an issue with certain Elementor pages that call it - which means it can be disabled to edit the pages.
	 *
	 * @param array $params
	 *
	 * @return false|string
	 */
	public function display_portfolio_shortcode( $params = array() ) {
		$id = $params['id'] ?? null;
		return $this->display_portfolio( $id );
	}

	public function display_portfolio( $id ) {
		$uri = $_SERVER['REQUEST_URI'];
		if ( strpos( $uri, 'elementor' ) != true ) {
			return null;
		}

		if ( $id ) {
			$user_id = $id;
		} else {
			$user_id = \bp_displayed_user_id();
		}
		$parent_id = $this->get_instance( WCFMHelpers::class )->staff_to_parent( $user_id );
		$bp        = \buddypress();

		// backup the child Id.
		$child_id = $bp->displayed_user->id;

		// set to the parent Id.
		$bp->displayed_user->id = $parent_id;

		// render whatever you want
		// Get Overview Widgets
		$profile_widgets = apply_filters(
			'yz_profile_main_widgets',
			\yz_option(
				'yz_profile_main_widgets',
				array(
					'slideshow' => 'visible',
					'project'   => 'visible',
					'skills'    => 'visible',
					'portfolio' => 'visible',
					'quote'     => 'visible',
					'instagram' => 'visible',
					'services'  => 'visible',
					'post'      => 'visible',
					'link'      => 'visible',
					'video'     => 'visible',
					'reviews'   => 'visible',
				)
			)
		);
		ob_start();

		include_once YZ_PUBLIC_CORE . 'functions/yz-general-functions.php';
		include_once YZ_PUBLIC_CORE . 'functions/yz-profile-functions.php';
		include_once YZ_PUBLIC_CORE . 'functions/yz-user-functions.php';
		include_once YZ_PUBLIC_CORE . 'class-yz-widgets.php';
		\yz_widgets()->get_widget_content( $profile_widgets );

		// reset.
		$bp->displayed_user->id = $child_id;

		return ob_get_clean();

	}
}
