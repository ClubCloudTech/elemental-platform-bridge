<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

/**
 * Render the admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Templates\SecurityButtons;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Library\ShortcodeDocuments;
use ElementalPlugin\Shortcode\UserVideoPreference;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {
	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start(); ?>
<div class="wrap">


		<h1 style ="display: inline">BuddyPress Integration and Video Rooms</h1>
	<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
		<p> Extras includes integration to BuddyPress which adds Video Rooms to the User Profile Page and to Groups. For Users, they get their personal video room rendered in the 
		BuddyPress Profile Loop as a Separate Video Meeting Tab with automatic control of their own Video Room settings, guests viewing a User Profile can enter Video Rooms straight
		the Profile. For Groups, Owners, and Moderators can Enable or Disable Group Video Rooms, as well as 
		control their Layouts, Templates and Reception Settings.
		<br>    </p>

	<?php
				// Activation/module.
	if ( ! Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_BUDDYPRESS_ID ) ) {
		return '';
	}
	?>

	<?php
	if ( Factory::get_instance( SiteDefaults::class )->is_buddypress_available() ) {
		?>



			<script type="text/javascript">
			function activateTab(pageId) {
				var tabCtrl = document.getElementById( 'tabCtrl' );
				var pageToActivate = document.getElementById(pageId);
				for (var i = 0; i < tabCtrl.childNodes.length; i++) {
					var node = tabCtrl.childNodes[i];
					if (node.nodeType == 1) { /* Element */
						node.style.display = (node == pageToActivate) ? 'block' : 'none';
					}
				}
			}

			</script>

<hr>
				<ul class="menu" >


						<a class="cc-menu-header" href="javascript:activateTab( 'page1' )" style=" 
							display: block; 
							float: left;
							border: 1px solid #ccc;
							border-bottom: none;
							margin-left: .5em;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;

							">User Profile Rooms </a>

						<a class="cc-menu-header" href="javascript:activateTab( 'page2' )" style="
							display: block; 
							float: left;
							border: 1px solid #ccc;
							border-bottom: none;
							margin-left: .5em;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;

							">Group Rooms </a>

						<a class="cc-menu-header" href="javascript:activateTab( 'page3' )" style="
							display: block; 
							float: left;
							border: 1px solid #ccc;
							border-bottom: none;
							margin-left: .5em;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;

							">BuddyPress Friends Video </a>


				<a class="cc-menu-header" href="javascript:activateTab( 'page4' )" style="
							display: block; 
							float: left;
							border: 1px solid #ccc;
							border-bottom: none;
							margin-left: .5em;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;

							">BuddyPress Shortcodes</a>

				</ul>

					<div id="tabCtrl"
						style="
						background: #e0e0e0;  
						display: block;
						float: left;
						border: 1px solid #ccc;
						padding: 5px 10px;
						font-size: 14px;
						line-height: 1.71428571;
						font-weight: 600;
						background: #e5e5e5;
						color: #555;
						text-decoration: none;
					">


						<div id="page1" style="
							display: block; float: left;
							border: 1px solid #ccc;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;
							">

							<h2>BuddyPress Profile Room Support</h2>
							<p> This module adds a personal meeting room of the user straight into the BuddyPress profile of the user. This Video Room is the same room as in the Personal Meeting Tab, and entrances, settings, invitations and reception settings work the same across both
									rooms. The room functions as a BuddyPress specific entrance from the BuddyPress environment into the User's personal Video Space.    </p>
		<?php
		// Activation/module.
		Factory::get_instance( ModuleConfig::class )->sub_module_activation_button( SiteDefaults::MODULE_BUDDYPRESS_USER_ID );
		?>

										<h2>Personal Room (Profile and User Video) Default Video Settings</h2>
										<p> These are the Default Room Privacy (reception) and Room Layout settings. These settings will be used by the Room, if the user has not yet set up a room preference</p>
										<?php
										$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
											SiteDefaults::USER_ID_SITE_DEFAULTS,
											SiteDefaults::ROOM_NAME_PERSONAL_BOARDROOM_SITE_DEFAULT,
											array( 'basic', 'premium' )
										);
										echo $layout_setting;
										?>
						</div>


						<div id="page2" style="
							display: none; 
							float: left;
							border: 1px solid #ccc;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;
							">

							<h2>BuddyPress Group Room Support</h2>

								<p> This module will add a Video Room to each BuddyPress group. It will allow a room admin or moderator of a BuddyPress group to be a <b>Host</b> of a group room. Regular members will be guests, signed out users are not allowed in group rooms. 
								The moderators/admins can change Room privacy, as well as room and reception layout templates by accessing on the Video Tab of the Group and clicking on the Host tab. 
								This will take affect at the next page refresh.    </p>

		<?php
		// Activation/module.
		if ( ! Factory::get_instance( ModuleConfig::class )->sub_module_activation_button( SiteDefaults::MODULE_BUDDYPRESS_GROUP_ID ) ) {
		}
		?>
									<h2>Groups Default Video Settings</h2>
									<p> These are the Default Room Privacy (reception) and Room Layout settings. These settings will be used by Groups, if the owner has not yet set up a room preference</p>
		<?php
									$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
										SiteDefaults::USER_ID_SITE_DEFAULTS,
										SiteDefaults::ROOM_NAME_BUDDYPRESS_GROUPS_SITE_DEFAULT,
										array( 'basic', 'premium' )
									);
		echo $layout_setting;
		?>

						</div>

						<div id="page3" style="
							display: none; 
							float: left;
							border: 1px solid #ccc;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;
							">

							<h2>BuddyPress Friends Video Control</h2>

								<p> Controls BuddyPress Friends behaviour and whether you want to enable access control restrictions for BuddyPress Friends. Users have the option to 
								restrict access to their video rooms to friends only. This section turns on or off support for the feature in the Room Security Engine and is configured in the Room Security Tab.    </p>

								<br>
								<?php
								// Activation/module.
								Factory::get_instance( ModuleConfig::class )->sub_module_activation_button( SiteDefaults::MODULE_BUDDYPRESS_FRIENDS_ID );

								?>



						</div>

						<div id="page4" style="
							display: none; 
							float: left;
							border: 1px solid #ccc;
							border-bottom: none;
							padding: 5px 10px;
							font-size: 14px;
							line-height: 1.71428571;
							font-weight: 600;
							background: #e5e5e5;
							color: #555;
							text-decoration: none;
							">
		<?php echo Factory::get_instance( ShortcodeDocuments::class )->render_buddypress_shortcode_docs(); ?>

						</div>



					</div> 

					<hr>
		<?php

	} else {
		echo '<h2>BuddyPress is not Installed - Settings Disabled</h2>';
	}

	return ob_get_clean();
};
