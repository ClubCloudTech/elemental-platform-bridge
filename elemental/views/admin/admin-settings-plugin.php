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

return function (): string {
	ob_start();
	$index = 7812;

	?>
	<!-- Module Header -->
	<div class="myvideoroom-menu-settings elemental-top-margin">
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
		<div id="notification-update-result" ></div>
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
			$tab_initial = array();
			$tabs        = apply_filters( 'elemental_page_option', $tab_initial );

		foreach ( $tabs as $tab ) {
			?>
		<tr class="active mvr-table-mobile">
			<?php
				// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - functions are escaped upstream.			
				echo $tab;
			?>
		</tr>
				<?php
		}
		?>
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

