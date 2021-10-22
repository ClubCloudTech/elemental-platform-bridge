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

<div class="mvr-nav-shortcode-outer-wrap mvr-nav-shortcode-outer-border">
	<!-- Module Header -->
	<div class="myvideoroom-menu-settings <?php echo esc_attr( $target ); ?>">
		<div class="myvideoroom-header-table-left-reduced">
			<h1><i
					class="myvideoroom-header-dashicons dashicons-plugins-checked"></i><?php esc_html_e( 'WooCommerce Frontend Manager (WCFM) Integration', 'myvideoroom' ); ?>
			</h1>
		</div>
		<div class="myvideoroom-header-table-right-wide">
		<h3 class="myvideoroom-settings-offset"><?php esc_html_e( 'Settings:', 'myvideoroom' ); ?><i data-target="<?php echo esc_attr( $target ); ?>" class="myvideoroom-header-dashicons dashicons-admin-settings mvideoroom-information-menu-toggle-selector" title="<?php esc_html_e( 'Go to Settings - Personal Meeting Rooms', 'myvideoroom' ); ?>"></i>
			</h3>
		</div>
	</div>

	<!-- Dependencies and Requirements Marker -->
	<div id="video-host-wrap" class="mvr-nav-settingstabs-outer-wrap">
		<div class="myvideoroom-feature-outer-table">
			<div id="feature-state<?php echo esc_attr( $index++ ); ?>" class="myvideoroom-feature-table-small">
				<h2><?php esc_html_e( 'About:', 'myvideoroom' ); ?></h2>
						<?php
			// Activation/module
			echo Factory::get_instance( ModuleConfig::class )->module_activation_button( SiteDefaults::MODULE_WCFM_ID );
				
			?>
			</div>
			<div class="myvideoroom-feature-table-large">
				
				<div id="childmodule<?php echo esc_attr( $index++ ); ?>">
					
		<p><?php esc_html_e( 'This area controls WCFM multi-store site integration to provide video rooms for each merchant store, as well as
		handle merchant bookings via video', 'myvideoroom' ); ?>
					</p>
				</div>
				<p><?php esc_html_e( 'Dependency Check - There are No Dependencies for this Module', 'myvideoroom' ); ?>
					</p>
				</div>
			</div>
		</div>
		<!-- Dependencies and Requirements Marker -->
	<div id="video-host-wrap" class="mvr-nav-settingstabs-outer-wrap">
		<div class="myvideoroom-feature-outer-table">
			<div id="feature-state<?php echo esc_attr( $index++ ); ?>" class="myvideoroom-feature-table-small">
				<h2><?php esc_html_e( 'Requirements:', 'myvideoroom' ); ?></h2>
			</div>
			<div class="myvideoroom-feature-table-large">
			<p>
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
				</p>
				<div id="childmodule<?php echo esc_attr( $index++ ); ?>">
				</div>
			</div>
		</div>
		<!-- Dependencies and Requirements Marker -->
		<div id="video-host-wrap" class="mvr-nav-settingstabs-outer-wrap">
		<div class="myvideoroom-feature-outer-table">
			<div id="feature-state<?php echo esc_attr( $index++ ); ?>" class="myvideoroom-feature-table-small">
				<h2><?php esc_html_e( 'Premium Accounts', 'myvideoroom' ); ?></h2>
			</div>
			<div class="myvideoroom-feature-table-large">

				<div id="childmodule<?php echo esc_attr( $index++ ); ?>">

				</div>
			</div>
		</div>
		<!-- Screenshot Marker -->
	<div id="video-host-wrap" class="mvr-nav-settingstabs-outer-wrap">
		<div class="myvideoroom-feature-outer-table">
			<div id="feature-state<?php echo esc_attr( $index++ ); ?>" class="myvideoroom-feature-table-small">
				<h2><?php esc_html_e( 'Video Room Defaults', 'myvideoroom' ); ?></h2>
			</div>
			<div class="myvideoroom-feature-table-large">
			<?php
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			1,
			SiteDefaults::STORE_NAME_WCFM_VIDEO_SITE_DEFAULT,
		);
				echo $layout_setting;
	
	?>
			
			</div>
		</div>


</div>

<?php

	return ob_get_clean();
};