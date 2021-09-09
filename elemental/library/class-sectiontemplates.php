<?php

/**
 * Display section templates
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Core\VideoControllers;
use ElementalPlugin\Factory;
use ElementalPlugin\Shortcode\UserVideoPreference;
use ElementalPlugin\WoocommerceBookings\Connect;
use ElementalPlugin\Shortcode as Shortcode;


/**
 * Class SectionTemplate
 */
class SectionTemplates extends Shortcode {


	/**
	 * Render form when no booking is found
	 *
	 * @return string
	 */
	public function no_bookings_found_form(): string {
		return $this->call_elementor_template( 24508 );
	}

	// ---
	// Meet Center Template Section.

	/**
	 * Render Guest /Meet Page Template for no invite and no username -
	 * Template used for Trapping no input into meet centre and asking user for invite, or username
	 *
	 * @return string
	 */
	public function meet_guest_reception_template() {
		// Exit for MVR
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29447 );
		}

		wp_enqueue_style( 'cc-guest-reception-template', plugins_url( '/stylesheets/guest-reception.css', __FILE__ ) );

		?>
		<div class="cc-row">
			<h2 class="cc-reception-header">Please Select Your Meeting Host at <?php echo get_bloginfo( 'name' ); ?></h2>

			<table style="width:100%">
				<tr>
					<th style="width:50%"><img src="
									<?php
									// Get ClubCloud Logo from Plugin folder for Form, or use Site Logo if loaded in theme
									$custom_logo_id = get_theme_mod( 'custom_logo' );
									$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
									if ( ! $image ) {
										$image = plugins_url( '/images/logoCC-clear.png', __FILE__ );
										echo $image;
									} else {
										echo $image[0];
									}
									?>
									" alt="Site Logo"></th>
					<th>
						<form action="">
							<label for="host">Host's Username: </label>
							<input type="text" id="host" name="host">
							<p class="cc-title-label">This is the Site Username for the user you would like to join </p>
							<h3> OR </h3>
							<label for="host">Host's Invite Code: </label>
							<input type="text" id="invite" name="invite">
							<p class="cc-title-label">This is the Invite Code XXX-YYY-ZZZ for the meeting </p>
							<input type="submit" value="Submit">
						</form>

					</th>

				</tr>

			</table>
		</div>
		<?php

	}
	/**
	 * Render Meeting Center Admin or Owner Page template for meetings -
	 *
	 * @TODO   - Fred to clean up
	 * @return string
	 */
	public function meet_admin_page_template() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29498 );
			// return $this->get_instance( \ElementalPlugin\Mvr\PageSwitches::class )->meet_switch_shortcode();
		}

		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 )
		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<div class="cc-menue-container">
			<ul class="menu">
				<a class="cc-menu-header" href="javascript:activateTab( 'page1' )">Join a Meeting</a>
				<a class="cc-menu-header" href="javascript:activateTab( 'page2' )">Host a Meeting</a>
				<a class="cc-menu-header" href="javascript:activateTab( 'page3' )">Host Settings</a>
			</ul>
			<div id="tabCtrl">
				<div id="page1" style="display: block;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_guest_shortcode(); ?></div>
				<div id="page2" style="display: none;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_host_shortcode(); ?></div>
				<div id="page3" style="display: none;">
					<h2 class="cc-menu-header">Hosting Settings - Personal Room </h2>

		<?php
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			1,
			SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM,
			array( 'basic', 'premium' )
		);
					echo $layout_setting;
		?>
				</div>
			</div>
			<br><br>
			<p>Admin Signed in as
		<?php
		$current_user = wp_get_current_user();
		echo $current_user->user_login;
		?>
		</div>
		<?php

	}

	/**
	 * Render Meeting Center Signed Out Page template for meetings - to switch template to signed out users for meeting Page
	 */
	public function meet_signed_out_page_template() {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29492 );
			// return $this->get_instance( \ElementalPlugin\Mvr\PageSwitches::class )->meet_switch_shortcode();
		}

		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 )
		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header" href="javascript:activateTab( 'page1' )">Join a Meeting</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page2' )">Sign In</a>
		</ul>
		<div id="tabCtrl">
			<div id="page1" style="display: block;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_guest_shortcode(); ?></div>
			<div id="page2" style="display: none;"><?php echo 'PlaceHolder for Login Form'; ?></div>
		</div>
		</div>

		<?php

	}

	/**
	 * Render Meeting Center Signed In normal user Page template for meetings
	 */
	public function meet_signed_in_page_template() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29495 );
		}

		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 )
		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header" href="javascript:activateTab( 'page1' )">Join a Meeting</a>
		</ul>
		<div id="tabCtrl">
			<div id="page1" style="display: block;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_guest_shortcode(); ?></div>

		</div>
		</div>
		<br><br>
		<p>User Signed in as
		<?php
		$current_user = wp_get_current_user();
		echo $current_user->user_login;
		?>
		</p>
		<?php

	}

	/**
	 * Render guest header template for meetings - used above guest room video shortcodes - provides meeting invite links, name, owner etc
	 *
	 * @return string
	 */
	public function meet_guest_header(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29533 );
		}
	}

	/**
	 * Render Host header template for meetings - used above Host room video shortcodes - provides meeting invite links, name, owner etc
	 *
	 * @return string
	 */
	public function meet_host_header(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 34497 );
		}
	}


	// ---
	// Booking Center Template Section.
	// Used for Action Centre Template Selection.

	/**
	 * Render Booking Center Admin Page template for bookings -
	 *
	 * @return string
	 */
	public function booking_ctr_site_admin_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 28653 );
		}

		// Check for WCFM - If exists WCFM Template - Else Normal User Signed In Template
		if ( Factory::get_instance( SiteDefaults::class )->is_wcfm_active() ) {
			// @TODO make template for Store Owners as Well
			echo 'StoreOwnerTemplate is on the TODO List';
		} else {
			return $this->booking_center_signedin_template();
		}
	}

	/**
	 * Render Booking and Meet Center Normal Signed In Template -
	 *
	 * @return string
	 */
	public function booking_center_signedin_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 30955 );
		}

		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 );
		$user_id = get_current_user_id();
		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header" href="javascript:activateTab( 'page1' )">Video Booking</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page2' )">Join Meeting</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page3' )">Host a Meeting</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page4' )">Host Settings</a>
		</ul>
		<div id="tabCtrl">
			<div id="page1" style="display: block;"><?php echo Factory::get_instance( Connect::class )->connect(); ?></div>
			<div id="page2" style="display: none;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_guest_shortcode(); ?></div>
			<div id="page3" style="display: none;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_host_shortcode(); ?></div>
			<div id="page4" style="display: none;">
		<?php
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings( $user_id, SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM, array( 'basic', 'premium' ) );
		echo $layout_setting;
		?>
			</div>
		</div>

		<?php
		return '';
	}
	// WCFM CONNECT TEMPLATE SECTION

	/**
	 * Render guest visitor header template for Store Video Rooms - used above visiting store video rooms - provides meeting invite links, name, owner etc
	 *
	 * @TODO   Fred - these can and should be upgraded to more advanced templates in Elementor
	 * @return string
	 */
	public function wcfmc_visitor_header(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 33491 );
		}
	}
	/**
	 * Render a Template to Automatically Wrap the Video Shortcode with additional tabs to add more functionality
	 *  Used to add Admin Page for each Room for Hosts, Returns Header and Shortcode if no additional pages passed in
	 *
	 * @return null
	 */



	public function shortcode_template_wrapper( string $header = null, string $shortcode = null, string $admin_page = null, $permissions_page = null ) {
		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab-header.css', __FILE__ ), false, 1.12 );

		if ( $admin_page === null ) {
			return $header . $shortcode;
		}
		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 );
		echo $header;

		// Randomizing Page ID's for Javascript as multiple frames can be rendered from this function

		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header-template" href="javascript:activateTab( 'page1' )">Video Room</a>
		<?php
		if ( \ElementalPlugin\Factory::get_instance( \ElementalPlugin\DAO\ModuleConfig::class )->module_activation_status( \ElementalPlugin\Core\SiteDefaults::MODULE_SECURITY_ID ) ) {
			echo '<a class="cc-menu-header-template" href="javascript:activateTab(\'page2\' )">Room Permissions</a>';
		}
		?>
			<a class="cc-menu-header-template" href="javascript:activateTab( 'page3' )">Video Host Settings</a>

		</ul>
		<div id="tabCtrl" style="margin-top : 10px; line-height: 2;">
			<div id="page1" style="display: block;"><?php echo $shortcode; ?></div>
			<div id="page2" style="display: none;"><?php echo $permissions_page; ?></div>
			<div id="page3" style="display: none;"><?php echo $admin_page; ?></div>

		</div>
		<?php

		return null;
	}

	/**
	 * Shortcode_template_wrapper_for WCFM
	 * Uses a different Javascript set of variables to allow multiple shortcodes to be rendered on the same page
	 *
	 * @param  string $header           - to place on top of room.
	 * @param  string $shortcode        - room to execute.
	 * @param  string $admin_page       - admin page if any.
	 * @param  string $permissions_page - if any.
	 * @return string - the completed formatted page.
	 */
	public function shortcode_template_wrapper_wcfm( string $header, string $shortcode, string $admin_page = null, $permissions_page = null ) {
		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab-header.css', __FILE__ ), false, 1.12 );
		if ( $admin_page === null ) {
			return $header . $shortcode;
		}
		echo $header;

		?>
		<script type="text/javascript">
			function activateTab2(pageId) {
				var tabCtrl2 = document.getElementById('tabCtrl2');
				var pageToActivate2 = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl2.childNodes.length; i++) {
					var node3 = tabCtrl2.childNodes[i];
					if (node3.nodeType == 1) {
						/* Element */
						node3.style.display = (node3 == pageToActivate2) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header-template" href="javascript:activateTab2( 'page4' )">Video Room</a>
		<?php
		if ( \ElementalPlugin\Factory::get_instance( \ElementalPlugin\DAO\ModuleConfig::class )->module_activation_status( \ElementalPlugin\Core\SiteDefaults::MODULE_SECURITY_ID ) ) {
			echo '<a class="cc-menu-header-template" href="javascript:activateTab2(\'page5\' )">Room Permissions</a> ';
		}
		?>
			<a class="cc-menu-header-template" href="javascript:activateTab2( 'page6' )">Video Host Settings</a>
		</ul>
		<div id="tabCtrl2" style="margin-top : 10px; line-height: 2;">
			<div id="page4" style="display: block;"><?php echo $shortcode; ?></div>
			<div id="page5" style="display: none;"><?php echo $permissions_page; ?></div>
			<div id="page6" style="display: none;"><?php echo $admin_page; ?></div>
		</div>
		<?php

		return null;
	}





	/**
	 * Render guest host header template for Store Video Rooms - used above owner/staff own store video rooms - provides meeting invite links, name, owner etc
	 *
	 * @TODO   Fred - these can and should be upgraded to more advanced templates in Elementor
	 * @return string
	 */
	public function wcfmc_host_header() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 35409 );
		} else {
			return 'MVR Test Failed';
		}
	}


	/**
	 * Render MVR Specific Upgrade Template - will become the basis for a UMP Module - so include in plugin v1+
	 *
	 * @return string,null
	 */
	public function mvr_ump_wcfm_upgrade_template() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29585 );
		} else {
			return null;
		}
	}



	/**
	 * Render Booking Center Store Owner Page template for bookings -
	 *
	 * @return string
	 */
	public function booking_ctr_store_owner_template(): string {

		// Filter MVR - suitable for Elementor Template Change
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 28637 );
		}

		// Check for WCFM - If exists WCFM Template - Else Normal User Signed In Template
		if ( Factory::get_instance( SiteDefaults::class )->is_wcfm_active() ) {
			// @TODO make template for Store Owners as Well
			echo 'StoreOwnerTemplate is on the TODO List';
		} else {
			return $this->booking_center_signedin_template();
		}
	}

	/**
	 * Render Booking Center Request Booking Number Form -
	 *
	 * @return string
	 */
	public function booking_ctr_request_booking_number_form(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 30831 );
		}

		wp_enqueue_style( 'cc-booking-ctr', plugins_url( '/stylesheets/booking-ctr.css', __FILE__ ) );

		?>
		<div class="cc-row">
			<h2 class="cc-reception-header">Please Enter Your Booking Number </h2>

			<table style="width:100%">
				<tr>
					<th style="width:50%"><img src="
										<?php
										// Get ClubCloud Logo from Plugin folder for Form, or use Site Logo if loaded in theme
										$custom_logo_id = get_theme_mod( 'custom_logo' );
										$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
										if ( ! $image ) {
											$image = plugins_url( '/images/logoCC-clear.png', __FILE__ );
											echo $image;
										} else {
											echo $image[0];
										}
										?>
										" alt="Site Logo"></th>
					<th>
						<form action="">
							<label for="host">My Booking Number: </label>
							<input type="text" id="booking" name="booking">
							<p class="cc-title-label">This is the number you received when making your booking, check email and purchase confirmation details (and Junk Mail folder) </p>
							<input type="submit" value="Submit">
						</form>

					</th>

				</tr>

			</table>
		</div>
		<?php

		return '';
	}


	/**
	 * Render Booking Center Signed Out Page template for bookings -
	 *
	 * @return string
	 */
	public function booking_ctr_signed_out_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 28648 );
		}

		wp_enqueue_style( 'cc-menutab-header', plugins_url( '/stylesheets/menu-tab.css', __FILE__ ), false, 1.11 )
		?>
		<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById('tabCtrl');
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node2 = tabCtrl.childNodes[i];
					if (node2.nodeType == 1) {
						/* Element */
						node2.style.display = (node2 == pageToActivate) ? 'block' : 'none';
					}
				}
			}
		</script>
		<ul class="menu">
			<a class="cc-menu-header" href="javascript:activateTab( 'page1' )">Video Booking</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page2' )">Meet</a>
			<a class="cc-menu-header" href="javascript:activateTab( 'page3' )">Sign In</a>
		</ul>
		<div id="tabCtrl">
			<div id="page1" style="display: block;"><?php echo Factory::get_instance( Connect::class )->connect(); ?></div>
			<div id="page2" style="display: none;"><?php echo Factory::get_instance( VideoControllers::class )->personal_meeting_guest_shortcode(); ?></div>
			<div id="page3" style="display: none;"><?php echo 'PlaceHolder for Login Form'; ?></div>
		</div>
		</div>

		<?php
		return '';
	}

	// ---
	// MVR Profile and WCFM Video Center Template Section.
	// Used for MVR Profile and WCFM Driven Video Centre Template Selection.

	/**
	 * Render Booking Center Admin Page template for bookings -
	 *
	 * @return string
	 */
	public function mvr_ctr_basic_admin_template(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 28653 );
		}
	}

	// ---
	// Control Panel Templates Section.
	// Used for Control Center (WCFM Store Settings) Tabs both from BP and from WCFM Store Manager.

	/**
	 * Render Payments Control Panel Admin Page template
	 *
	 * @return string
	 */
	public function account_centre_landing(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 35999 );
		}
	}

	/**
	 * Render Account/Family Center Advanced/Payments Control Panel Tab
	 *
	 * @return string
	 */
	public function control_panel_store_advanced(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 36024 );
		}
	}

	/**
	 * Render Account/Family Center Products Control Panel Tab
	 *
	 * @return string
	 */
	public function control_panel_store_products(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 36003 );
		}
	}

	/**
	 * Render Account/Family Center Video Control Panel Tab
	 *
	 * @return string
	 */
	public function control_panel_store_video(): string {
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 36009 );
		}
	}

	// ---
	// Find and Search PagesTemplates.

	/**
	 * Render Find Pages Signed In user Template
	 *
	 * @return string
	 */
	public function find_signed_in_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29134 );
		}
	}
	/**
	 * Render Find Pages Signed Out user Template
	 *
	 * @return string
	 */
	public function find_signed_out_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 31200 );
		}
	}

	// ---
	// Site Video Room Templates.

	/**
	 * Render Site Videoroom Host Template
	 *
	 * @return string
	 */
	public function site_boardroom_host_template() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 27972 );
		}

		return null;
	}

	/**
	 * Render Management Boardroom Guest Template
	 *
	 * @return string
	 */
	public function site_boardroom_guest_template() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 27975 );
		}
	}

	// Render siteDefault Selection Templates.

	/**
	 * Render Site Default Setting for Boardroom, Privacy, and Reception Template
	 *
	 * @return string
	 */
	public function site_default_settings_template(): string {
		wp_enqueue_style(
			'cc-boardroom',
			plugins_url( '/stylesheets/boardroom-header.css', __FILE__ ),
			array(),
			$this->get_plugin_version()
		);

		ob_start();
		?>
		<div class="myvideoroom-extras-boardroom-header">
			<div class="row">
				<div class="column">
					<h2><?php echo esc_html( get_bloginfo( 'name' ) ); ?> Site Default Configuration</h2>
				</div>
			</div><br />

			<h2> Site Wide Video Room Default Settings</h2>

		<?php
		echo Factory::get_instance( UserVideoPreference::class )->choose_settings(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT,
			array( 'basic', 'premium' )
		);
		?>
		</div>
		<?php

		return ob_get_clean();
	}

	// ---
	// BuddyPress Groups Templates

	/**
	 * Render Group Host Template
	 *
	 * @return string
	 */
	public function bp_group_host_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 32917 );
		}
	}

	/**
	 * Render Group Guest Template
	 *
	 * @return string
	 */
	public function bp_group_guest_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 32949 );
		}
	}

	// ---
	// BuddyPress Plugin Control Panel Center Templates

	/**
	 * Render Main Dashboard Template for user's own account control panel
	 *
	 * @return string
	 */
	public function bp_plugin_control_centre_dashboard() {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			// @todo Amanda to do landing template for admin
			// return $this->call_elementor_template( 32917 );
		}

		wp_enqueue_style( 'cc-guest-reception-template', plugins_url( '/stylesheets/guest-reception.css', __FILE__ ) );

		?>
		<div class="cc-row">
			<h2 class="cc-reception-header">Video Settings for <?php echo get_bloginfo( 'name' ); ?></h2>

			<table style="width:100%">
				<tr>
					<th style="width:50%"><img src="
									<?php
									// Get ClubCloud Logo from Plugin folder for Form, or use Site Logo if loaded in theme
									$custom_logo_id = get_theme_mod( 'custom_logo' );
									$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
									if ( ! $image ) {
										$image = plugins_url( '/images/logoCC-clear.png', __FILE__ );
										echo $image;
									} else {
										echo $image[0];
									}
									?>
									" alt="Site Logo"></th>
					<th>
						Please Choose Configuration Option from Tab Above

					</th>

				</tr>

			</table>
		</div>
		<?php

	}


	/**
	 * Security Templates
	 * Render Main Dashboard Template for user's own account control panel
	 *
	 * @return string
	 */



	public function room_blocked_by_user() {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}

		wp_enqueue_style( 'cc-guest-reception-template', plugins_url( '/stylesheets/guest-reception.css', __FILE__ ) );

		?>
		<div class="cc-row">
			<h2 class="cc-reception-header"><?php echo get_bloginfo( 'name' ); ?> </h2>



			<table style="width:100%">
				<tr>
					<th style="width:50%">
		<?php
		 // Get ClubCloud Logo from Plugin folder for Form, or use Site Logo if loaded in theme.
		 $custom_logo_id = get_theme_mod( 'custom_logo' );
		 $image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( ! $image ) {
			$image_src = plugins_url( '/mvr-imagelogo.png', __DIR__ );
		} else {
			$image_src = $image[0];
		}
		?>

						<img src="<?php echo esc_url( $image_src ); ?>" alt="Site Logo">
					</th>
					<th>
						Your administrator has disabled this room type. Please contact the Site Owner to re-enable this room type.

					</th>

				</tr>

			</table>
		</div>
		<?php

		return ' ';
	}






	// ---
	// Account Control Panel Center Templates

	/**
	 * Render Main Dashboard Template for user's own account control panel
	 *
	 * @return string
	 */
	public function account_control_centre_dashboard(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 27468 );
		}
	}
	/**
	 * Render Main Dashboard Alternate (non Owner-Store-Admin) for user's own account control panel
	 *
	 * @return string
	 */
	public function account_control_centre_alternate_dashboard(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 29160 );
		}
	}

	// ---
	// Staff-Lobby Landing Templates

	/**
	 * Render Staff Upgrade Template for staff members who need to activate a subscription
	 *
	 * @return string
	 */
	public function staff_lobby_get_credentials_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 22918 );
		}
	}

	/**
	 * Render Staff Upgrade Template for staff members who need to activate a subscription
	 *
	 * @return string
	 */
	public function staff_lobby_sales_landing_template(): string {

		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
			return $this->call_elementor_template( 22921 );
		}
	}

	/**
	 * Render an Elementor Template by the id
	 *
	 * @param int $elementor_template_id The id of the elementor template id.
	 *
	 * @return string
	 */
	private function call_elementor_template( int $elementor_template_id ): string {
		return \do_shortcode( '[elementor-template id="' . $elementor_template_id . '"]' );
	}
}
