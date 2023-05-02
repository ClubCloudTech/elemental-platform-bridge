<?php
/**
 * Outputs the Maintenance Configuration Settings.
 *
 * @package elemental/module/sitevideo/views/admin/view-settings-maintenance.php
 */

use ElementalPlugin\Module\Membership\Membership;

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
	<div class="elemental-menu-settings elemental-top-margin">
		<div class="elemental-header-table-left">
			<h1><i
					class="elemental-header-dashicons dashicons-database-view"></i><?php esc_html_e( 'Plugin Configuration', 'elemental' ); ?>
			</h1>
		</div>
		<div class="elemental-header-table-right">
		<h3 class="elemental-settings-offset"><i data-target="" class="elemental-header-dashicons dashicons-admin-settings " title="<?php esc_html_e( 'Go to Settings - Personal Meeting Rooms', 'elemental' ); ?>"></i>
			</h3>
		</div>
	</div>
<div class="elemental-clear"></div>

	<!-- Module State and Description Marker -->
<div class="elemental-feature-outer-table">
		<div id="module-state<?php echo esc_attr( $index++ ); ?>" class="elemental-feature-table-small">
			<h2><?php esc_html_e( 'Settings', 'elemental' ); ?></h2>
			<div id="parentmodule<?php echo esc_attr( $index++ ); ?>">
			<input type="button" class="elemental-main-button-enabled"
			id="elemental_refresh_layout" value="<?php esc_html_e( 'Save Settings', 'elemental' ); ?>" style="display: none;">
			</div>
		<div id="notification-update-result" ></div>
		</div>
		<div class="elemental-feature-table-large">
		<table id="elemental-table-basket-frame_<?php echo esc_attr( $index++ ); ?>" class="wp-list-table widefat plugins elemental-table-adjust">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Setting', 'elemental' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Value', 'elemental' ); ?>
				</th>

			</tr>
		</thead>
		<tbody>

		<?php
			$tab_initial = array();
			$tabs        = apply_filters( 'elemental_page_option', $tab_initial );

		foreach ( $tabs as $tab ) {
			?>
		<tr class="active elemental-table-mobile">
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
					<?php esc_html_e( 'Setting', 'elemental' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
					<?php esc_html_e( 'Value', 'elemental' ); ?>
				</th>

			</tr>
		</tfoot>
	</table>	
		</div>
	</div>

	<?php
	return ob_get_clean();
};

