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
use ElementalPlugin\Setup\RoomAdmin;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\Setup\Setup;
use ElementalPlugin\DAO\RoomMap;
use ElementalPlugin\Shortcode\UserVideoPreference;


return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {
	ob_start();
	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );

	$post_id = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'site-video-room', 'post_id' );

	global $wp;
	// Handling Regenerate Command clicked by user-  to recreate pages.
	$regenerate = $params['regenerate'] ?? htmlspecialchars( $_GET['regenerate'] ?? '' );
	if ( $regenerate ) {

		// Check Setting is truly empty and not a back button click etc.
		$check_is_empty = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'site-video-room', 'post_id' );

		if ( ! $check_is_empty ) {
			Factory::get_instance( RoomMap::class )->delete_room_mapping( SiteDefaults::SITE_PAGE_VIDEO_ROOM );
			Factory::get_instance( Setup::class )->create_site_videoroom_page();

			$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
			echo '<h1>Page Refresh Completed</h1>';
			return wp_redirect( $url_base );
			exit();
		}
	}

	// Handling Enable/Disable Module?>

	<div class="wrap">
		

		<h1 style ="display: inline" >Site Video Room</h1>
	<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
				
		<p> The Site Video Room is available for Team wide meetings at the website level. It is created automatically by the plugin, at activation. It can be secured such that any normal
			site administrator is an owner of the room<br>    </p>
		
	<?php
	// Activation/module
	if ( ! Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_SITE_VIDEO_ID ) ) {
		return '';
	}

	?>

		<h2>Current Room Information</h2>
		
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
						$title = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'site-video-room', 'title' );
						if ( $title ) {
							echo $title;
						}
						?>
						</td>
					<td style="width:25%; text-align: left;">
						<?php
						$url = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'site-video-room', 'url' );
						if ( $url ) {
							echo '<a href="' . $url . '" target="_blank">' . $url . '</a>';
						}
						?>
						</td>
					<td style="width:25%; text-align: left;">
					<?php
					$post_id_return = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'site-video-room', 'post_id' );
					if ( $post_id_return ) {
						echo $post_id_return;
					}
					?>
					</td>
					<td style="width:25%; text-align: left;">
					<?php
					if ( Factory::get_instance( SiteDefaults::class )->is_elementor_active() ) {
						if ( $post_id ) {
							echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=elementor" class="button button-primary"target="_blank">Edit in Elementor</a>';
							echo ' - ';
						}
					}
					if ( $post_id ) {
						echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit" class="button button-primary" target="_blank">Edit in WordPress</a>';
					} else {
						$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
						echo '<a href="' . $url_base . '&regenerate=personalmeeting" class="button button-primary" >Page Deleted - Click Here to Regenerate</a>';
					}
					?>
					</td>
				</tr>
				
			</table>
			
			<h2>Customizing the Room</h2>
			<p> You can edit your room look and feel with any page editor of your choice - the page must contain the shortcode <b> [ccsitevideoroom]</b>    </p>
			<p> You can change the room name, its URL, and its parent page in the normal pages interface of WordPress.  </p>
			<p> This room will allow any site admin as a Host, and everyone else will be a guest. For private meetings, please use your own Personal Video Room    </p>
		
			<h2>Room Video Settings</h2>
	<?php
				$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
					SiteDefaults::USER_ID_SITE_DEFAULTS,
					SiteDefaults::ROOM_NAME_SITE_VIDEO,
					array( 'basic', 'premium' )
				);
			echo $layout_setting;
	?>

	</div>
	<div>
	
	<?php
	Factory::get_instance( \ElementalPlugin\Library\ShortcodeDocuments::class )->render_sitevideoroom_shortcode_docs();
	?>
	
	</div>
	<?php

	return ob_get_clean();
};
