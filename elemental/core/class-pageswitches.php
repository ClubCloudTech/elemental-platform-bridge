<?php
/**
 * Shortcodes for pages
 *
 * @package ElementalPlugin\Core
 */

namespace ElementalPlugin\Core;

use ElementalPlugin\UltimateMembershipPro\MembershipLevel;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\Core\Security;

// required for cleaning correct URL redirects in Firefox.
ob_clean();
ob_start();

/**
 * Class PageSwitches
 */
class PageSwitches extends Shortcode {



	const TABLE_NAME = 'myvideoroom_extras_room_post_mapping';

	/**
	 * Install the shortcode
	 */
	public function install() {

		$this->add_shortcode( 'loginswitch', array( $this, 'login_switch_shortcode' ) );
		$this->add_shortcode( 'registerswitch', array( $this, 'register_switch_shortcode' ) );
		$this->add_shortcode( 'chgpwdswitch', array( $this, 'change_password_switch_shortcode' ) );
		$this->add_shortcode( 'templateswitch', array( $this, 'post_template_switch_shortcode' ) );
		$this->add_shortcode( 'loungeswitch', array( $this, 'lounge_switch_shortcode' ) );
		$this->add_shortcode( 'bookingctrswitch', array( $this, 'booking_ctr_switch_shortcode' ) );
		$this->add_shortcode( 'findswitch', array( $this, 'find_switch_shortcode' ) );
		$this->add_shortcode( 'meetswitch', array( $this, 'meet_switch_shortcode' ) );
		$this->add_shortcode( 'accountctrswitch', array( $this, 'account_center_switch_shortcode' ) );
		$this->add_shortcode( 'sitevideoroom', array( $this, 'sitevideo_switch_shortcode' ) );
		$this->add_shortcode( 'lobbyswitch', array( $this, 'lobby_switch_shortcode' ) );
		$this->add_shortcode( 'staffswitch', array( $this, 'staff_switch_shortcode' ) );

		$this->add_shortcode( 'sitedefaultconfig', array( $this, 'site_default_settings_shortcode' ) );

		$this->add_shortcode( 'proxytest', array( $this, 'proxy_test_function' ) );
	}

	/**
	 * A shortcode to Proxy Functions to Front End for Testing
	 * Used to Setup basic settings
	 * Used to run whatever is in here from the /test page
	 *
	 * @return string
	 */
	public function proxy_test_function() {

	}



	/**
	 * A shortcode to Show the Site Settings Defaults Page
	 * Used to Setup basic settings
	 *
	 * @return string
	 */
	public function site_default_settings_shortcode(): string {
		return $this->get_instance( SectionTemplates::class )->site_default_settings_template();
	}






	/**
	 * A shortcode to switch Staff Header to an Activation template
	 * This is needed as different subscription levels and WordPress roles need different dashboards.
	 *
	 * @return string|null
	 */
	public function staff_switch_shortcode(): ?string {
		// Reject Logged Out Users.
		if ( ! is_user_logged_in() ) {
			return null;
		}
		$user_roles = $this->get_instance( UserRoles::class );

		if ( ! $user_roles->is_wcfm_shop_staff() ) {
			// don't process all roles other than shop staff.
			return null;
		}

		$user             = wp_get_current_user();
		$membership_level = get_user_meta( $user->id, 'ihc_user_levels' );
		$memlev           = explode( ',', $membership_level[0] );
		$array_count      = count( $memlev );

		// Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
		for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
			switch ( $memlev[ $x ] ) {
				case MembershipLevel::VENDOR_STAFF:
					return null;
			}
		}

		return do_shortcode( '[elementor-template id="32704"]' );
	}

	/**
	 * A shortcode to switch The Management Meeting Room to Host and Guests
	 *
	 * @return string
	 */
	public function sitevideo_switch_shortcode() {
		// Fetch User Parameters and Roles...
		$user       = wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class );

		// Handling Admin Roles - sending them to Admin Lounge.
		if ( $user_roles->is_wordpress_administrator() ) {
			return $this->get_instance( \ElementalPlugin\Core\VideoControllers::class )->site_videoroom_member_shortcode();
		} else {
			return $this->get_instance( \ElementalPlugin\Core\VideoControllers::class )->site_videoroom_guest_shortcode();
		}
	}


	/**
	 * Category Login Page Switch shortcode
	 * This shortcode is used to switch the Login Page to a template bypassing the lock Buddypress puts on an active login page
	 *
	 * @return string
	 */
	public function login_switch_shortcode(): string {
		return do_shortcode( '[elementor-template id="31245"]' );
	}

	/**
	 * Category Register Page Switch shortcode
	 * This shortcode is used to switch the Register Page archives to different templates
	 *
	 * @return string
	 */
	public function register_switch_shortcode(): string {
		// getting current page information to compare parent owners - using 730 which is Site setting for User 1.
		$switch_data = \xprofile_get_field_data( 730, 1 );

		// Handling a Blank Setting.
		if ( ! $switch_data ) {
			$switch_data = 'Sports_Club';
		}

		switch ( $switch_data ) {
			case 'Sports_Club':
				return do_shortcode( '[elementor-template id="25202"]' );

			default:
				return ' The switch found no template for this selection type<br>';
		}
	}

	/**
	 * Category Change Password Page Switch shortcode
	 * This shortcode is used to switch the Change Password Page archives to different templates
	 *
	 * @return string
	 */
	public function change_password_switch_shortcode(): string {
		return do_shortcode( '[elementor-template id="35692"]' );
	}



	/**
	 * Display Storefront Layout - Change the look of each individual store
	 * Usage: In all front end storefront locations where seamless permissions video is needed.
	 *
	 * @return string
	 */
	public function post_template_switch_shortcode(): string {
		$user_id = $this->get_instance( SiteDefaults::class )->page_owner();
		if ( ! is_numeric( $user_id ) ) {
			return do_shortcode( '[elementor-template id="16211"]' );
		}

		$user_roles = $this->get_instance( UserRoles::class );

		// Empty error handling is done in Site Default Area in SiteDefaults::xp.
		$xprofile_setting = $this->get_instance( SiteDefaults::class )->get_layout_id( 'storeswitch', $user_id );

		if ( $user_roles->is_wcfm_vendor()
			&& ! $this->get_instance( SiteDefaults::class )->is_premium_check( $user_id )
		) {
			// Looking for Merchants that are NOT Premium.
			$url = bp_core_get_user_domain( $user_id );

			wp_redirect( $url );
			exit();
		}

		switch ( $xprofile_setting ) {
			case 'Family':
				return do_shortcode( '[elementor-template id="33485"]' );
			case 'Afternoon':
				return do_shortcode( '[elementor-template id="36175"]' );
			case 'YellowOffice':
				return do_shortcode( '[elementor-template id="36343"]' );
			case 'Linden':
				return do_shortcode( '[elementor-template id="36329"]' );
			case 'Pure':
				return do_shortcode( '[elementor-template id="36367"]' );
			case 'Splat':
				return do_shortcode( '[elementor-template id="22197"]' );
			case 'High_Energy':
				return do_shortcode( '[elementor-template id="22217"]' );
			case 'Shop_Window':
				return do_shortcode( '[elementor-template id="22199"]' );
			case 'Spa':
				return do_shortcode( '[elementor-template id="22734"]' );
			case 'Family-Simple':
			default:
				return do_shortcode( '[elementor-template id="33482"]' );
		}
	}




	/**
	 * A shortcode to switch Club Main Lounge to different Subscription Levels
	 * This code switches to the correct subscription template based on subscriber, and handles Admin or Special WP roles.
	 * This is needed as different subscription levels and WordPress roles need different dashboards.
	 *
	 * @return string
	 */
	public function lounge_switch_shortcode(): string {
		// Fetch User Parameters and Roles.
		$user       = wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class );

		// Handling Admin Roles - sending them to Admin Lounge.
		if ( $user_roles->is_wordpress_administrator() ) {
			return do_shortcode( '[elementor-template id="20006"]' );
		}

		if ( \Factory::get_instance( \ElementalPlugin\Core\SiteDefaults::class )->is_wcfm_active() ) {
			// If user is non-admin Then get membership level and Re-create Array from WordPress text input.
			$membership_level = get_user_meta( $user->id, 'ihc_user_levels' );
			$memlev           = explode( ',', $membership_level[0] );
			$array_count      = count( $memlev );

			// Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
			for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
				switch ( $memlev[ $x ] ) {
					case MembershipLevel::BUSINESS:
						// Coach gold.
						return do_shortcode( '[elementor-template id="26220"]' );
					case MembershipLevel::PREMIUM:
						// Coach silver.
						return do_shortcode( '[elementor-template id="26234"]' );
					case MembershipLevel::BASIC:
						// Coach bronze.
						return do_shortcode( '[elementor-template id="17081"]' );
					case MembershipLevel::PLATINUM:
						return do_shortcode( '[elementor-template id="17230"]' );
					case MembershipLevel::DIAMOND:
						return do_shortcode( '[elementor-template id="17225"]' );
					case MembershipLevel::SITE_ADMIN:
						return do_shortcode( '[elementor-template id="20006"]' );
					case MembershipLevel::AMBASSADOR:
						return do_shortcode( '[elementor-template id="17502"]' );
					case MembershipLevel::VENDOR_STAFF:
						return do_shortcode( '[elementor-template id="22906"]' );
				}
			}
		}
		return '';
	}

	/**
	 * Shortcode to switch The Booking Center Dashboard to different Subscription Levels and experiences
	 * This is needed as different subscription levels and WordPress roles need different dashboards.
	 */
	public function booking_ctr_switch_shortcode(): string {

		// Security Engine - Checking if Page can be Rendered
		$render_block = Factory::get_instance( Security::class )->render_block( 0, 'connect', SiteDefaults::MODULE_WC_BOOKINGS_ID );
		if ( $render_block ) {
			return $render_block;
		}

		// signed out user.
		if ( ! is_user_logged_in() ) {
			return $this->get_instance( SectionTemplates::class )->booking_ctr_signed_out_template();
		}

		// Fetch User Parameters and Roles.
		$user       = wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class );

		// Handling Admin Roles - sending them to Admin Template.
		if ( $user_roles->is_wordpress_administrator() ) {
			return $this->get_instance( SectionTemplates::class )->booking_ctr_site_admin_template();
		} elseif ( \Factory::get_instance( \ElementalPlugin\Core\SiteDefaults::class )->is_wcfm_active()
			&& ( $user_roles->is_wcfm_vendor()
			|| $user_roles->is_wcfm_shop_staff() )
		) {
			return $this->get_instance( SectionTemplates::class )->booking_ctr_store_owner_template();
		}

		// normal user.
		if ( is_user_logged_in() ) {
			return $this->get_instance( SectionTemplates::class )->booking_center_signedin_template();
		}

		return '';
	}

	/**
	 * A shortcode to switch The FIND pages to different Subscription Levels and login levels
	 * Used by the /find main page to use correct template in elementor/templates
	 *
	 * @return string
	 */
	public function find_switch_shortcode(): string {
		if ( is_user_logged_in() ) {
			return $this->get_instance( SectionTemplates::class )->find_signed_in_template();
		} else {
			return $this->get_instance( SectionTemplates::class )->find_signed_out_template();
		}
	}

	/**
	 * A shortcode to switch The  Meet Centre
	 * the /meet room works from this logic
	 */
	public function meet_switch_shortcode() {

		// handle signed out users and return signed out templates.
		if ( ! is_user_logged_in() ) {
			return $this->get_instance( SectionTemplates::class )->meet_signed_out_page_template();
		}

		// Fetch User Parameters and Roles.
		$user       = wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class, array( $user ) );

		// Handling Store Owner Roles - sending them to Store Owner Template.
		if ( $user_roles->is_wordpress_administrator() ) {
			return $this->get_instance( SectionTemplates::class )->meet_admin_page_template();
		}
		// TODO - add WCFM Support Here

		if ( Factory::get_instance( \ElementalPlugin\Core\SiteDefaults::class )->is_mvr() ) {
			return $this->get_instance( \ElementalPlugin\Mvr\PageSwitches::class )->meet_helper( $user->id );
		}

		return $this->get_instance( SectionTemplates::class )->meet_signed_in_page_template();

	}

	/**
	 * A shortcode to switch The Account Center tab in dashboard to normal users or store owners
	 * This is needed as different subscription levels and WordPress roles need different dashboards.
	 *
	 * @return string
	 */
	public function account_center_switch_shortcode(): string {
		// Fetch User Parameters and Roles.
		$user       = wp_get_current_user();
		$user_roles = $this->get_instance( UserRoles::class );

		// Handling Admin Roles.
		if ( $user_roles->is_wordpress_administrator() ) {
			// admin lounge.
			return $this->get_instance( SectionTemplates::class )->account_control_centre_dashboard();
		}

		// If user is non-admin Then get membership level and Re-create Array from WordPress text input.
		$membership_level = get_user_meta( $user->id, 'ihc_user_levels' );
		$memlev           = explode( ',', $membership_level[0] );
		$array_count      = count( $memlev );

		// Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
		for ( $x = 0; $x <= $array_count - 1; $x ++ ) {
			switch ( $memlev[ $x ] ) {
				case MembershipLevel::BUSINESS: // Coach gold.
				case MembershipLevel::PREMIUM: // Coach silver.
				case MembershipLevel::BASIC: // Coach bronze.
				case MembershipLevel::PLATINUM:
				case MembershipLevel::DIAMOND:
				case MembershipLevel::SITE_ADMIN:
				case MembershipLevel::AMBASSADOR:
				case MembershipLevel::VENDOR_STAFF:
					return $this->get_instance( SectionTemplates::class )->account_control_centre_dashboard();
			}
		}

		// sets default case in case no selection by merchant.
		return $this->get_instance( SectionTemplates::class )->account_control_centre_alternate_dashboard();
	}


	/**
	 * A shortcode to switch entry Lobby
	 * This code switches to the correct subscription template based on subscriber, and handles Admin or Special WP roles.
	 *
	 * @return string
	 */
	public function lobby_switch_shortcode(): string {
		// Fetch User Parameters and Roles To make Routing Decisions.
		$user    = wp_get_current_user();
		$user_id = $user->id;

		$user_roles = $this->get_instance( UserRoles::class );

		// Handling Any Role Type that was registered - add new role cases here.
		switch ( true ) {
			case $user_roles->is_wcfm_shop_staff():
				return $this->get_instance( SectionTemplates::class )->staff_lobby_get_credentials_template();
		}

		// Get Call for the Lobby Registration type from xprofile field 1268 which is the Pre-Reg type field.
		$switch_data = xprofile_get_field_data( 1268, $user_id );
		if ( ! $switch_data ) {
			return 'Xprofile return failure - no entry found<br>';
		}

		// Otherwise - Return default registered user - Sales Template.
		return $this->get_instance( SectionTemplates::class )->staff_lobby_sales_landing_template();
	}
}
