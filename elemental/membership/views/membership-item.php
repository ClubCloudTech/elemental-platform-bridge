<?php
/**
 * Shows Membership Room Items
 *
 * @package ElementalPlugin\Membership\Views\membership-item.php
 */

/**
 * Render the Membership Items.
 *
 * @param \stdClass $memberships The room
 * @param ?string $memberships_type  Category of Room to Filter.
 *
 * @return string
 */
return function (
	array $memberships
): string {
	ob_start();

		$save_nonce     = wp_create_nonce( 'delete_room_' . $memberships['level'] );
		$delete_url     = \add_query_arg(
			array(
				'room_id'  => $memberships['id'],
				'confirm'  => null,
				'action'   => 'delete',
				'_wpnonce' => $save_nonce,
			),
			\esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) )
		);
		$edit_actions[] = array(
			__( 'Save' ),
			$delete_url,
			'myvideoroom-dashicons dashicons-database-add myvideoroom-sitevideo-delete',
			array( 'data-nonce' => $save_nonce ),
		);

	?>
	<tr class="active mvr-table-mobile" data-room-id="<?php echo esc_attr( $memberships['level'] ); ?>">
		<td class="plugin-title column-primary myvideoroom-mobile-table-row-adjust">
	<?php
	if ( $memberships['badge_url'] ) {
		?>
			<img src="<?php echo esc_url( $memberships['badge_url'] ); ?>" alt="Level">
		<?php
	}
	?>
		</td>
		<td class="column-description myvideoroom-mobile-table-row-adjust">
	<?php
				echo esc_textarea( $memberships['label'] );
	?>
		</td>
		<td>
	<?php
				echo esc_textarea( $memberships['price_text'] );
	?>
		</td>
		<td class="plugin-title column-primary">
		<label for="number_child">
			<input type="number" min="0" max="10000" name="number_child" value="<?php echo esc_textarea( $memberships['limit'] ); ?>" placeholder="" data-level = "<?php echo esc_textarea( $memberships['level'] ); ?>" id="number_child_<?php echo esc_textarea( $memberships['level'] ); ?>" class="elemental-membership-control" />
		</label>
		<div id="confirmation_<?php echo esc_textarea( $memberships['level'] ); ?>" class = "elemental-membership-displayconf"></div>
		</td>
		<td>
	<?php
	foreach ( $edit_actions as $action ) {
		?>
				<a href="<?php echo esc_url( $action[1] ); ?>"
					class="mvr-icons <?php echo esc_attr( $action[2] ); ?>"
					data-level="<?php echo esc_attr( $memberships['level'] ); ?>"
					title="<?php echo esc_attr( $action[0] ); ?>"
		<?php
		foreach ( $actions[3] ?? array() as $key => $value ) {
			echo esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
		}
		?>
				></a>
		<?php
	}
	?>
		</td>
	</tr>


	<?php

	return ob_get_clean();
};
