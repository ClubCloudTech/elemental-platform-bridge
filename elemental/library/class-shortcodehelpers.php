<?php
/**
 * Display Shortcode Documentation
 *
 * @package MyVideoRoomExtrasPlugin\Library
 */

namespace MyVideoRoomExtrasPlugin\Library;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Core\VideoControllers;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\WoocommerceBookings\Connect;
use MyVideoRoomExtrasPlugin\Shortcode as Shortcode;


/**
 * Provides Helper Shortcodes for Usage
 */
class ShortcodeHelpers extends Shortcode {

	/**
	 * Install the shortcodes
	 */
	public function install() {

		// $this->add_shortcode( 'displayportfolio', array( $this, 'display_portfolio_shortcode' ) );
		$this->add_shortcode( 'sitevideoroomsettings', array( $this, 'site_video_settings_shortcode' ) );
		$this->add_shortcode( 'personalmeetinghostsettings', array( $this, 'personal_meeting_settings_shortcode' ) );

	}

	/**
	 * Provides Shortcodes for Room Info Scenarios
	 */
	public function site_video_settings_shortcode() {

		?>
<table style="width:70%; border: 1px solid black;"  >
				
				
</table>
	<h1>Site Video Room Settings</h1>
	<p> The Site Video Room is available for Team wide meetings at the website level. It is created automatically by the plugin, at activation. It can be secured such that any normal
	site administrator is an owner of the room<br>	</p>
		<?php
		$layout_setting = \MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
			\MyVideoRoomExtrasPlugin\Core\SiteDefaults::USER_ID_SITE_DEFAULTS,
			\MyVideoRoomExtrasPlugin\Core\SiteDefaults::ROOM_NAME_SITE_VIDEO,
			array( 'basic', 'premium' )
		);
		echo $layout_setting;
		?>
</table>
		<?php
	}


	/**
	 * Provides Shortcodes for Room Info Scenarios
	 */
	public function personal_meeting_settings_shortcode() {

		// Rejecting Logged out Users
		if ( ! is_user_logged_in() ) {
			return null;
		}
		// Get User ID
		$user_id = get_current_user_id();

		?>
	<table style="width:70%; border: 1px solid black;"  >
					
					
	</table>
		<h1>Personal Meeting Video Room Settings</h1>
		<p> The Personal Video Room is private to each user. Use these settings to update your room configuration, privacy, and video layouts<br>	</p>
		<?php
		$layout_setting = \MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
			$user_id,
			\MyVideoRoomExtrasPlugin\Core\SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM,
			array( 'basic', 'premium' )
		);
		echo $layout_setting;
		?>
	</table>
		<?php
	}


}//end class

