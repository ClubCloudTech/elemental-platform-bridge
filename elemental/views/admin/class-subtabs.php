<?php
/**
 * Addon functionality for BuddyPress -Video Room Handlers for BuddyPress
 *
 * @package ElementalPlugin\BuddyPressVideo
 */

namespace ElementalPlugin\Views\Admin;

use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Shortcode\UserVideoPreference;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Factory;

/**
 * Class Template
 */
class SubTabs extends Shortcode {




	/**
	 * Install the shortcode
	 */
	public function install() {
		// $this->add_shortcode( 'displayportfolio', array( $this, 'display_portfolio_shortcode' ) );
	}

	/**
	 * Display_Bp_user_subtab - displays BuddyPress Subtab.
	 *
	 * @return string
	 */
	public function display_bp_user_subtab(): string {
		?>
	<h2>Customizing the User Profile Room</h2>

	<p> The User Profile Video Room is the same room as in the Personal Meeting Tab, and entrances, settings and receptions work the same across both
	rooms. Think of the Profile room, as just another entrance from the BuddyPress environment into the User's personal Video Space.    </p>
		<?php
		// Activation/module.
		if ( ! \ElementalPlugin\Factory::get_instance( \ElementalPlugin\DAO\ModuleConfig::class )->module_activation_button( \ElementalPlugin\Core\SiteDefaults::MODULE_BUDDYPRESS_USER_ID ) ) {
			return '';
		}
		?>

	<h2>Personal Room (Profile and User Video) Default Video Settings</h2>
	<p> These are the Default Room Privacy (reception) and Room Layout settings. These settings will be used by the Room, if the user has not yet set up a room preference</p>
		<?php
		$layout_setting = \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
			\ElementalPlugin\Core\SiteDefaults::USER_ID_SITE_DEFAULTS,
			\ElementalPlugin\Core\SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM_SITE_DEFAULT,
			array( 'basic', 'premium' )
		);
		// phpcs:ignore --(WordPress.Security.EscapeOutput.OutputNotEscaped) - phpcs Layout setting is already sanitised at its construction.
		echo $layout_setting;
		?>
</div>
		<?php

	}

	/**
	 * Display_bp_group_subtab
	 * Displays BuddyPress Groups configuration.
	 *
	 * @return string
	 */
	public function display_bp_group_subtab(): string {
		?>

<h2>Customizing the Group Room</h2>

<p> This room will allow a room admin or moderator to be a Host of a group room, and regular members else will be a guest, signed out users are not allowed in group rooms. 
The moderators/admins can change Room privacy, as well as room and reception layout templates by accessing on the Video Tab of the Group and clicking on the Host tab. 
This will take affect at the next page refresh.    </p>

		<?php
		// Activation/module.
		if ( ! \ElementalPlugin\Factory::get_instance( \ElementalPlugin\DAO\ModuleConfig::class )->module_activation_button( \ElementalPlugin\Core\SiteDefaults::MODULE_BUDDYPRESS_GROUP_ID ) ) {
			return '';
		}
		?>
<h2>Groups Default Video Settings</h2>
<p> These are the Default Room Privacy (reception) and Room Layout settings. These settings will be used by Groups, if the owner has not yet set up a room preference</p>
		<?php
		$layout_setting = \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
			\ElementalPlugin\Core\SiteDefaults::USER_ID_SITE_DEFAULTS,
			\ElementalPlugin\Core\SiteDefaults::ROOM_NAME_BUDDYPRESS_GROUPS_SITE_DEFAULT,
			array( 'basic', 'premium' )
		);
   // phpcs:ignore --(WordPress.Security.EscapeOutput.OutputNotEscaped) - phpcs Layout setting is already sanitised at its construction.
		echo $layout_setting;

	}

}//end class
