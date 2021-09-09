<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package MyVideoRoomExtrasPlugin\Views\Admin
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
use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\Library\Templates\SecurityButtons;
use MyVideoRoomExtrasPlugin\Setup\RoomAdmin;
use MyVideoRoomExtrasPlugin\DAO\ModuleConfig;
use MyVideoRoomExtrasPlugin\Library\ShortcodeDocuments;
use MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = require __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();

	$post_id = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'bookings-center', 'post_id' );

	global $wp;
	// Handling Regenerate Command clicked by user-  to recreate pages.
	$regenerate = $params['regenerate'] ?? htmlspecialchars( $_GET['regenerate'] ?? '' );
	if ( $regenerate ) {
		// Check Setting is truly empty and not a back button click etc.
		$check_is_empty = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'bookings-center', 'post_id' );

		if ( ! $check_is_empty ) {

			Factory::get_instance( \MyVideoRoomExtrasPlugin\DAO\RoomMap::class )->delete_room_mapping( SiteDefaults::SITE_PAGE_BOOKINGS_CENTER );
			Factory::get_instance( \MyVideoRoomExtrasPlugin\Setup\Setup::class )->create_bookings_center_page();

			$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
			echo '<h1>Page Refresh Completed</h1>';
			return wp_redirect( $url_base );
			exit();
		}
	}

	?>

	<div class="wrap">
			

			<h1 style ="display: inline">Woocommerce Bookings Integration and Booking Center</h1>
			<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
			<p> Extras includes integration to WoocommerceBookings which it can automatically show you booking rooms that are custom for a single booking, and can not be accessed 
			before or after the booking. The booking room becomes open 15 minutes ahead of time, and guests will join the reception area. Merchants can deliver any product as a booking 
			and users can see their upcoming video bookings in their myaccount pages of WooCommerce and go straight into Video Bookings.<br>	</p>

			<?php
			// Activation/module
			if ( ! Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_WC_BOOKINGS_ID ) ) {
				return '';
			}
			?>
		
	<?php
	if ( Factory::get_instance( SiteDefaults::class )->is_woocommerce_bookings_active() ) {
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
							$title = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'bookings-center', 'title' );
						if ( $title ) {
							echo $title;}
						?>
						</td>
						<td style="width:25%; text-align: left;">
						<?php
							$url = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'bookings-center', 'url' );
						if ( $url ) {
							echo '<a href="' . $url . '" target="_blank " >' . $url . '</a>';}
						?>
						</td>
						<td style="width:25%; text-align: left;">
						<?php
							$post_id_return = Factory::get_instance( RoomAdmin::class )->get_videoroom_info( 'bookings-center', 'post_id' );
						if ( $post_id_return ) {
							echo $post_id_return;}
						?>
						</td>
						<td style="width:25%; text-align: left;">
						<?php
						if ( Factory::get_instance( SiteDefaults::class )->is_elementor_active() ) {
							if ( $post_id ) {
								echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=elementor" class="button button-primary" target="_blank">Edit in Elementor</a>';
								echo ' - ';
							}
						}
						if ( $post_id ) {
							echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit" class="button button-primary" target="_blank">Edit in WordPress</a>';
						} else {
							$url_base = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
							echo '<a href="' . $url_base . '&regenerate=personalmeeting" class="button button-primary">Page Deleted - Click Here to Regenerate</a>';

						}
						?>
					</td>
				</tr>
				
			</table>
				<hr>
				<h2>Customizing the Room</h2>
				<p> You can edit your room look and feel with any page editor of your choice - the page must contain the shortcode <b> [ccbookingctrswitch]</b>	</p>
				<p> The Booking Center has its own page that shows signed in users and admins/merchants their next booking, and prompts signed out users for a booking number, the 
				booking center also has built in form handlers that can accept order numbers, and booking invite numbers straight to a booking room without any form. This is useful to 
				email links to customers on order completion, and allow them to go straight in. 	</p>
				<p> You can change the room name, its URL, and its parent page in the normal pages interface of WordPress. Please note whilst the system updates its internal
				links if you change the meeting page URL external emails, or other invites may not be updated by your users' guests. Its a good idea to link to reception page from the 
				main area of your site.	</p>
				<p> This room will allow only a store admin site user to be a Host of a booking room, and everyone else will be a guest. The store owner/merchant can change Bookings privacy, as well as room and reception
				layout templates by accessing on the front end and clicking on the Host tab of the booking center, and well as below on this page, . This will take affect at the next page refresh.	</p>
			<hr>
				<h2>Bookings Video Settings</h2>
				<p> Please note this applies for single site WooCommerce Bookings settings only. For Multi-Store applications please see the WooCommerce Frontend Manager Tab. If you have 
				WCFM Installed this Room Configuration only applies to the Main Store, and the Merchant Stores select their own settings.</p>
				<?php
				$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
					SiteDefaults::USER_ID_SITE_DEFAULTS,
					SiteDefaults::ROOM_NAME_BOOKINGS_SINGLE,
					array( 'basic', 'premium' )
				);
				echo $layout_setting;
				?>
		</div>
		<hr>
		
				<div>
					<?php
						Factory::get_instance( ShortcodeDocuments::class )->render_wcbookings_shortcode_docs();
					?>
				</div>
	
	
		
		<?php

	} else {
		echo '<h2>WooCommerce Bookings is not Installed - Settings Disabled</h2>';
	}

	return ob_get_clean();
};

