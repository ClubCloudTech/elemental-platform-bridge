<?php

/**
 * Display section templates
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library\Templates;

use ElementalPlugin\Core\Security;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\DAO\SecurityVideoPreference as SecurityVideoPreferenceDAO;


/**
 * Class SectionTemplate
 */
class SecurityButtons {




	/**
	 * Security Buttons
	 * Render Main Dashboard Template for user's own account control panel
	 *
	 * @return string
	 */


	/**
	 * Site Wide Buttons
	 */
	public static function site_wide_enabled( $input_type = null ) {
		$site_override = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
		if ( ! $site_override ) {
			return null;
		}
		// Format Plugin Base Link to Security Center.
		$plugin_foldername = plugin_basename( __DIR__ );
		$plugin_path       = strstr( $plugin_foldername, '/', true );
		$admin_page        = Security::MODULE_SECURITY_NAME;

		// get Site Override Status.

		$room_disabled  = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'room_disabled' );
		$room_anonymous = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'anonymous_enabled' );
		$roles          = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'allow_role_control_enabled' );
		$output         = null;
		// Rendering Output.
		if ( $site_override ) {
			if ( 'nourl' === $input_type ) {
				$output .= '<a class="button button-primary" style="background-color:#daab33">Site Enforcement Active</a>';
			} else {
				$output .= '<a href="' . get_admin_url() . 'admin.php?page=' . $plugin_path . '&tab=' . $admin_page . '#disabled" class="button button-primary" style="background-color:#daab33">Site Enforcement Active</a>';
			}
		}
		if ( $room_disabled && null === $input_type ) {
			if ( 'nourl' === $input_type ) {
				$output .= '<a class="button button-primary" style="background-color:Red">Site Video Disabled</a>';
			} else {
				$output .= '<a href ="' . get_admin_url() . 'admin.php?page=' . $plugin_path . '&tab=' . $admin_page . '#disabled" class="button button-primary" style="background-color:Red">Site Video Disabled</a>';
			}
		} elseif ( ( $room_anonymous || $roles ) && $site_override ) {
			if ( 'nourl' === $input_type ) {
				$output .= '<a class="button button-primary" style="background-color:blue">Site Mandatory Settings Applied</a>';
			} else {
				$output .= '<a href ="' . get_admin_url() . 'admin.php?page=' . $plugin_path . '&tab=' . $admin_page . '#disabled" class="button button-primary" style="background-color:blue">Site Mandatory Settings Applied</a>';
			}
			if ( $room_anonymous && $site_override ) {
				if ( 'nourl' === $input_type ) {
					$output .= '<a class="button button-primary" style="background-color:blue">Site Anonymous Block Applied</a>';
				} else {
					$output .= '<a href="' . get_admin_url() . 'admin.php?page=' . $plugin_path . '&tab=' . $admin_page . '#disabled" class="button button-primary" style="background-color:blue">Site Anonymous Block Applied</a>';
				}
			}
		}
		return $output;
	}
} // End Class.
