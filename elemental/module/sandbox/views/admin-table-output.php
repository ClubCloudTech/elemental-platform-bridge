<?php
/**
 * Outputs Sandbox Tabs Table.
 *
 * @package module/sandbox/views/admin-table-output.php
 */

/**
 * Render the Sandbox Tabs Table.
 *
 * @param array   $sandbox_items        The list of rooms.
 * @param ?string $level_type  Category of Room to Filter.
 *
 * @return string
 */
return function (
	array $sandbox_items
): string {
	ob_start();
	?>
	<button class="button button-primary elemental-sandbox-add">
						<i class="dashicons dashicons-plus-alt"></i>
						<?php esc_html_e( 'Add New Sandbox', 'elementalplugin' ); ?>
					</button>
	<?php
	if ( $sandbox_items ) {
		?>
	<table id="elemental-table-basket-frame" class="wp-list-table widefat plugins elemental-table-adjust">
		<thead>
			<tr>
			<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Enabled', 'elementalplugin' ); ?>
				</th>
				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Tab Name', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'User Name Prepend', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Destination URL', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Custom Field 1', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Custom Field 2', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Employee Name', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Company Domain', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Owner User', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Tab Order', 'elementalplugin' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Admin Enforced', 'elementalplugin' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>

		<?php
		$membership_item_render_add_new = include __DIR__ . '/admin-sandbox-item-new.php';
        //phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $membership_item_render_add_new( $level );
		$membership_item_render = include __DIR__ . '/admin-sandbox-item.php';

		foreach ( $sandbox_items as $level ) {
        //phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $membership_item_render( $level );
		}
		?>
		</tbody>
	</table>
		<?php
	} else {
		?>
	<p>
		<?php
		esc_html_e( 'You don\'t have any Items in Sandbox.', 'elementalplugin' );
		?>
	</p>
		<?php
	}
	return ob_get_clean();
};
