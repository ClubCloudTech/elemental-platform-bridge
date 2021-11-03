<?php
/**
 * Outputs the Maintenance Configuration Settings.
 *
 * @package my-video-room/module/sitevideo/views/admin/view-settings-maintenance.php
 */

/**
 * Render Admin Page Settings Maintenance.
 *
 * @return string
 */

use ElementalPlugin\XProfile\XProfile;

return function (): string {
	ob_start();
	$index = 7812;

	?>
	<!-- Module Header -->
	<div class="myvideoroom-menu-settings">
		<div class="myvideoroom-header-table-left">
			<h1><i
					class="myvideoroom-header-dashicons dashicons-database-view"></i><?php esc_html_e( 'Plugin Parameter Configuration', 'myvideoroom' ); ?>
			</h1>
		</div>
		<div class="myvideoroom-header-table-right">
		<h3 class="myvideoroom-settings-offset"><i data-target="" class="myvideoroom-header-dashicons dashicons-admin-settings " title="<?php esc_html_e( 'Go to Settings - Personal Meeting Rooms', 'myvideoroom' ); ?>"></i>
			</h3>
		</div>
	</div>

<div class="elemental-clear"></div>

	<!-- Module State and Description Marker -->
<div class="myvideoroom-feature-outer-table">
		<div id="module-state<?php echo esc_attr( $index++ ); ?>" class="myvideoroom-feature-table-small">
			<h2><?php esc_html_e( 'Settings', 'myvideoroom' ); ?></h2>
			<div id="parentmodule<?php echo esc_attr( $index++ ); ?>">
			<input type="button" class="mvr-main-button-enabled"
			id="myvideoroom_refresh_layout" value="<?php esc_html_e( 'Save Settings', 'myvideoroom' ); ?>" style="display: none;">
			</div>
		</div>
		<div class="myvideoroom-feature-table-large">
		<table id="mvr-table-basket-frame_<?php echo esc_attr( $index++ ); ?>" class="wp-list-table widefat plugins myvideoroom-table-adjust">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Setting', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Value', 'my-video-room' ); ?>
				</th>

			</tr>
		</thead>
		<tbody>

		<?php
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - functions are escaped upstream.
			echo apply_filters( 'elemental_page_option', '' );
		?>
	<tr class="active mvr-table-mobile">
		<td>
		<span><?php esc_html_e( 'Xprofile Field Countries', 'myvideoroom' ); ?></span>
		</td>
		<td>
		<input type="number" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="<?php echo esc_attr( Xprofile::SETTING_XPROFILE_COUNTRY ); ?>" value="<?php echo get_option( XProfile::SETTING_XPROFILE_COUNTRY ); ?>">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="<?php \esc_html_e( 'Field Name for XProfile Countries', 'myvideoroom' ); ?>"></i>
		</td>

	</tr>
		</tbody>
		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Setting', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Value', 'my-video-room' ); ?>
				</th>

			</tr>
		</tfoot>
	</table>	
		</div>
	</div>

	<?php
	return ob_get_clean();
};

