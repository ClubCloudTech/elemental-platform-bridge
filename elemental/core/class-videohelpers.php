<?php
/**
 * This Class formats module settings upstream from user level, to module level, to site level and returns
 * the correct parameters to ensure default settings are always applied
 *
 * One function is added per setting
 *
 * @package ElementalPlugin\BuddyPressVideo
 */

namespace ElementalPlugin\Core;

use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\DAO\UserVideoPreference as UserVideoPreferenceDao;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Core\SiteDefaults;




/**
 * Class Template
 */
class VideoHelpers extends Shortcode {



	/**
	 * Install the shortcodes and auto run functions
	 */
	public function install() {
		// $this->add_shortcode( 'displayportfolio', array( $this, 'display_portfolio_shortcode' ) );
	}

	/*
	Videoroom Template


	*/
	public function get_videoroom_template( int $user_id, string $room_name ) {
		// First try the User's Value
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_layout_id() ) {
			return $current_user_setting->get_layout_id();
		}
		// Now Try the Category Preference

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_layout_id() ) {
			return $current_user_setting->get_layout_id();
		}

		// Now Try the Main Site Default

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->get_layout_id() ) {
			return $current_user_setting->get_layout_id();
		} else {
			return null;
		}
	}



	/*
	Video Reception URL


	*/
	public function get_video_reception_url( int $user_id, string $room_name ) {
		// First try the User's Value
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_url_setting() ) {
			return $current_user_setting->get_reception_video_url_setting();
		}
		// Now Try the Category Preference

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_url_setting() ) {
			return $current_user_setting->get_reception_video_url_setting();
		}

		// Now Try the Main Site Default

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_url_setting() ) {
			return $current_user_setting->get_reception_video_url_setting();
		} else {
			return null;
		}
	}

	// For Video Reception State



	public function get_video_reception_state( int $user_id, string $room_name ) {
		// First try the User's Value
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_enabled_setting() ) {
			return $current_user_setting->get_reception_video_enabled_setting();
		}
		// Now Try the Category Preference

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_enabled_setting() ) {
			return $current_user_setting->get_reception_video_enabled_setting();
		}

		// Now Try the Main Site Default

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_enabled_setting() ) {
			return $current_user_setting->get_reception_video_enabled_setting();
		} else {
			return null;
		}
	}

	/*
	Reception Template

	*/

	public function get_reception_template( int $user_id, string $room_name ) {
		// First try the User's Value
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_id() ) {
			return $current_user_setting->get_reception_id();
		}
		// Now Try the Category Preference

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_reception_video_url_setting() ) {
			return $current_user_setting->get_reception_id();
		}

		// Now Try the Main Site Default

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->get_reception_id() ) {
			return $current_user_setting->get_reception_id();
		} else {
			return null;
		}
	}

	// For Reception State (status)



	public function get_enable_reception_state( int $user_id, string $room_name ) {
		// First try the User's Value
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->is_reception_enabled() ) {
			return $current_user_setting->is_reception_enabled();
		}
		// Now Try the Category Preference

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->is_reception_enabled() ) {
			return $current_user_setting->is_reception_enabled();
		}

		// Now Try the Main Site Default

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->is_reception_enabled() ) {
			return $current_user_setting->is_reception_enabled();
		} else {
			return null;
		}
	}


	/**
	 * Show Floorplan Function
	 * Gets the floorplan setting for a user
	 *
	 * @param  int    $user_id   - required.
	 * @param  string $room_name - required.
	 * @return void
	 */
	public function get_show_floorplan( int $user_id, string $room_name ) {
		// First try the User's Value.
		$video_preference_dao = $this->get_instance( UserVideoPreferenceDao::class );
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_show_floorplan_setting() ) {
			return $current_user_setting->get_show_floorplan_setting();
		}
		// Now Try the Category Preference.

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			$room_name
		);

		if ( $current_user_setting && $current_user_setting->get_show_floorplan_setting() ) {
			return $current_user_setting->get_show_floorplan_setting();
		}

		// Now Try the Main Site Default.

		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);

		if ( $current_user_setting && $current_user_setting->get_show_floorplan_setting() ) {
			return $current_user_setting->get_show_floorplan_setting();
		} else {
			return null;
		}
	}


}//end class
