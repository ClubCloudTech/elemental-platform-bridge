<?php
/**
 * Outputs the security configuration settings for the video plugin
 *
 * @package ElementalPlugin\Views\Admin
 */

/**
 * Render the Security admin page
 *
 * @param string $active_tab
 * @param array $tabs
 * @param array $messages
 *
 * @return string
 */
use ElementalPlugin\Factory;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Library\Templates\SecurityButtons;
use ElementalPlugin\Setup\RoomAdmin;
use ElementalPlugin\DAO\ModuleConfig;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {
	ob_start();
	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );

	// Handling Enable/Disable Module.
	?>

	<div class="security-wrap">

		<h1 style ="display: inline">Room Security Modules</h1> 
	<?php
	echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled();
	?>

		<p> The Room Security Module adds permissions settings to each room that a user can manage, including what
		groups and roles are allowed or denied, if anonymous users are allowed, and some module specific security
		settings like Groups for BuddyPress/BuddyBoss etc. The module also allows a user to turn on or off each 
		and every room, and gives security settings on a per room type, and plugin basis. 
		</p>

	<?php
	// Activation/module.
	if ( ! Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_SECURITY_ID ) ) {
		return '';
	}
	?>


		<h2>Current Room Security Information</h2>

		<table style="width:70%; border: 1px solid black;"  >
				<tr>
					<th style="width:25%; text-align: left; " >Page Name</th>
					<th style="width:25%; text-align: left;" >Page URL</th>
					<th style="width:25%; text-align: left;" >WordPress Post ID</th>
					<th style="width:25%; text-align: left;" >Edit</th>
				</tr>
				<tr>
					<td style="width:25%; text-align: left;">
						<?php
						$title = Factory::get_instance( RoomAdmin::class )
						->get_videoroom_info( 'site-video-room', 'title' );
						if ( $title ) {
							echo $title;
						}
						?>
						</td>
					<td style="width:25%; text-align: left;">
						<?php
						$url = Factory::get_instance( RoomAdmin::class )
						->get_videoroom_info( 'site-video-room', 'url' );
						if ( $url ) {
							echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
						}
						?>
						</td>
					<td style="width:25%; text-align: left;">
					<?php
					$post_id_return = Factory::get_instance( RoomAdmin::class )
					->get_videoroom_info( 'site-video-room', 'post_id' );
					if ( $post_id_return ) {
						echo $post_id_return;
					}
					?>
					</td>
					<td style="width:25%; text-align: left;">
					<?php
					if ( Factory::get_instance( SiteDefaults::class )->is_elementor_active() ) {
						if ( $post_id ) {
							echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id .
							'&action=elementor" class="button button-primary
							"target="_blank">Edit in Elementor</a>';
							echo ' - ';
						}
					}
					if ( $post_id ) {
						echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id .
						'&action=edit" class="button button-primary" target="_blank">Edit in WordPress</a>';
					} else {
						$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
						echo '<a href="' . $url_base . '&regenerate=personalmeeting" 
						class="button button-primary" >Page Deleted - Click Here to Regenerate</a>';
					}
					?>
					</td>
				</tr>

			</table>

			<a id="disabled"></a>
			<h1>Room Security Settings</h1>
	<?php
	$layout_setting = Factory::get_instance( \ElementalPlugin\Shortcode\SecurityVideoPreference::class )
	->choose_settings(
		SiteDefaults::USER_ID_SITE_DEFAULTS,
		SiteDefaults::ROOM_NAME_SITE_DEFAULT,
		null,
		'admin'
	);

			echo $layout_setting;
	?>

	</div>
	<div>

	<?php
	Factory::get_instance( \ElementalPlugin\Library\ShortcodeDocuments::class )
	->render_sitevideoroom_shortcode_docs();
	?>

	</div>
	<?php

	return ob_get_clean();
};
