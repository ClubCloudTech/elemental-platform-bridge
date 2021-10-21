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
use \MyVideoRoomPlugin\Shortcode\UserVideoPreference;
use \MyVideoRoomPlugin\DAO\ModuleConfig;
use \MyVideoRoomPlugin\Module\Security\Templates\SecurityButtons;

return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();

	?>

<div class="wrap">


	<h1 style="display: inline">WooCommerce Frontend Manager (WCFM) Integration</h1>
	<?php echo Factory::get_instance( SecurityButtons::class )->site_wide_enabled(); ?>
	<p>This area controls WCFM multi-store site integration to provide video rooms for each merchant store, as well as
		handle merchant bookings via video </p>
</div>

<?php
	// Activation/module
	echo Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_WCFM_ID );
		
	?>

<?php
	if ( ! Factory::get_instance( SiteDefaults::class )->is_wcfm_active() ) {
		echo '<h2>WCFM is not Installed - Settings Disabled</h2>';
	} else {
		?>

<h2><?php esc_html_e( 'WCFM Merchant Video Room Default Settings', 'myvideoroom' ); ?> </h2>
		<?php
				esc_html_e(
					'This room will allow any store owner or staff member of the store as a Host, and everyone else will be a guest. For private meetings, please use your own Personal Video Room 
				Bookings are handled separately if WooCommerce Bookings is installed, each booking generates its own meeting room automatically, the store room is designed for public and not booking use 
				Please note this setting only applies for Merchant Stores inside of WCFM storefront video. Each Merchant Stores select their own settings for Video Privacy. Settings selected here
				will apply unless the user specifies their own Reception template, status, and meeting layout. ',
					'myvideoroom'
				);
		?>

<hr>
<h3>Video Room Defaults</h3>
<?php
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			1,
			SiteDefaults::STORE_NAME_WCFM_VIDEO_SITE_DEFAULT,
		);
				echo $layout_setting;
	}
	?>
<hr>



<?php

	return ob_get_clean();
};