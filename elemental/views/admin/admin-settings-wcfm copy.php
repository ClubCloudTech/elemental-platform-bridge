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


return function (
	string $active_tab,
	array $tabs,
	array $messages = array()
): string {

	$render = include __DIR__ . '/header.php';
	echo $render( $active_tab, $tabs, $messages );
	ob_start();

	?>


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
				<h2><?php esc_html_e( 'Requirements:', 'myvideoroom' ); ?></h2>
			</div>
			<div class="myvideoroom-feature-table-large">
				<p>
					<?php
							esc_html_e(
								'This Addin Pack provides support for the Personal Meeting Room engine, and any plugins/extensions that require the pack.',
								'myvideoroom'
							);
					?>
				</p>
				<div id="childmodule<?php echo esc_attr( $index++ ); ?>">
					<p><?php esc_html_e( 'Dependency Check - There are No Dependencies for this Module', 'myvideoroom' ); ?>
					</p>
				</div>
			</div>
		</div>
		<!-- Screenshot Marker -->

	


	<h3>Video Room Defaults</h3>
	<?php
		$layout_setting = Factory::get_instance( UserVideoPreference::class )->choose_settings(
			1,
			SiteDefaults::STORE_NAME_WCFM_VIDEO_SITE_DEFAULT,
		);
				echo $layout_setting;

	?>



	<?php

	return ob_get_clean();
};
