<?php
/**
 * Outputs User Membership Levels Table.
 *
 * @package module/sandbox/views/admin-table-output.php
 */

/**
 * Render the Membership Levels Table.
 *
 * @param array   $membership_levels        The list of rooms.
 * @param ?string $level_type  Category of Room to Filter.
 *
 * @return string
 */
return function (
	array $membership_levels
): string {
	ob_start();
	?>

	<?php
	if ( $membership_levels ) {
		?>
	<table id="mvr-table-basket-frame" class="wp-list-table widefat plugins myvideoroom-table-adjust">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Badge', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Level', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Price', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Allowed Accounts', 'my-video-room' ); ?>
				</th>

				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Store Template', 'my-video-room' ); ?>
				</th>
				<th scope="col" class="manage-column column-name column-primary">
		<?php esc_html_e( 'Landing Template', 'my-video-room' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$membership_item_render = include __DIR__ . '/membership-item.php';
		foreach ( $membership_levels as $level ) {
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
