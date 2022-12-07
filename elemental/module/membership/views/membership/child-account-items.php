<?php
/**
 * Shows Child/Sponsored Account Room Items
 *
 * @package ElementalPlugin\Module\Membership\Views\child-account-items.php
 */

use ElementalPlugin\Module\Membership\Membership;

/**
 * Render the Child/Sponsored Account User Items.
 *
 * @param \stdClass $child_account_object The room
 * @param ?string $child_account_object_type  Category of Room to Filter.
 *
 * @return string
 */
return function (
	array $child_account_object
): string {
	ob_start();

		$save_nonce     = wp_create_nonce( Membership::MEMBERSHIP_NONCE_PREFIX_DU . strval( $child_account_object['user_id'] ) );
		$edit_actions[] = array(
			__( 'Delete User' ),
			null,
			'elemental-dashicons dashicons-dismiss elemental-delete-user-account',
			array( 'data-nonce' => $save_nonce ),
		);

		?>
	<tr class="active mvr-table-mobile" data-room-id="<?php echo esc_attr( $child_account_object['level'] ); ?>">
		<td class="plugin-title column-primary elemental-mobile-table-row-adjust">
	<?php

	echo esc_textarea( $child_account_object['email'] );

	?>
		</td>

		<td class="plugin-title column-primary elemental-mobile-table-row-adjust">
	<?php

	echo esc_textarea( $child_account_object['account_type'] );

	?>
		</td>
		<td class="column-description elemental-mobile-table-row-adjust">
	<?php
				echo esc_textarea( $child_account_object['created'] );
	?>
		</td>
		<td>
	<?php
				echo esc_textarea( $child_account_object['display_name'] );
	?>
		</td>
		<td>
	<?php
	foreach ( $edit_actions as $action ) {
		?>
				<a href=""
					class="mvr-icons <?php echo esc_attr( $action[2] ); ?>"
					data-userid="<?php echo esc_attr( $child_account_object['user_id'] ); ?>"
					data-nonce="<?php echo esc_attr( $save_nonce ); ?>"
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
