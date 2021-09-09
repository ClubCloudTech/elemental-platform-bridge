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
use MyVideoRoomExtrasPlugin\Setup\Setup;
use MyVideoRoomExtrasPlugin\DAO\RoomMap;
use MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference;
return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = require __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();

	?>

	<div class="wrap">
		

		<h1 style ="display: inline">WooCommerce Frontend Manager (WCFM) Integration</h1>
		<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
		<p>This area controls WCFM multi-store site integration to provide video rooms for each merchant store, as well as handle merchant bookings via video </p>
	</div>

	<?php
			// Activation/module
	if ( ! \MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\DAO\ModuleConfig::class )->module_activation_button( \MyVideoRoomExtrasPlugin\Core\SiteDefaults::MODULE_WCFM_ID ) ) {
		return '';
	}
	?>
	
	<?php
	if ( ! \MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Core\SiteDefaults::class )->is_wcfm_active() ) {
		echo '<h2>WCFM is not Installed - Settings Disabled</h2>';
	} else {
		?>
				
				<h2>WCFM Merchant Video Room Default Settings</h2>
				<p> This room will allow any store owner or staff member of the store as a Host, and everyone else will be a guest. For private meetings, please use your own Personal Video Room	</p>
				<p> Bookings are handled separately if WooCommerce Bookings is installed, each booking generates its own meeting room automatically, the store room is designed for public and not booking use	</p>
				<p> Please note this setting only applies for Merchant Stores inside of WCFM storefront video. Each Merchant Stores select their own settings for Video Privacy. Settings selected here 
					will apply unless the user specifies their own Reception template, status, and meeting layout. 
				</p>
				<hr>
				<h3>Video Room Defaults</h3>
		<?php
				$layout_setting = \MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
					1,
					\MyVideoRoomExtrasPlugin\Core\SiteDefaults::STORE_NAME_WCFM_VIDEO_SITE_DEFAULT,
					array( 'basic', 'premium' )
				);
				echo $layout_setting;
	}
	?>
				<hr>

				<div>
					<?php
						\MyVideoRoomExtrasPlugin\Factory::get_instance( \MyVideoRoomExtrasPlugin\Library\ShortcodeDocuments::class )->render_wcfm_shortcode_docs();
					?>
				</div>


	<?php

	return ob_get_clean();
};

