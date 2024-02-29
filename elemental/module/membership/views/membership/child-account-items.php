<?php
/**
 * Shows Child/Sponsored Account User Object Items
 *
 * @package ElementalPlugin\Module\Membership\Views\child-account-items.php
 */

use ElementalPlugin\Module\Membership\Membership;

/**
 * Render the Child/Sponsored Account User Items.
 * Used by table for dataroom admin view and dataroom non-admin view
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

	if ( isset( $child_account_object['allusers'] ) ) {
		$data_type     = $child_account_object['allusers'];
		$show_advanced = true;
	} else {
		$data_type     = '';
		$show_advanced = false;
	}
	if ( isset( $child_account_object['parent_name'] ) ) {
		$parent_name = $child_account_object['parent_name'];
	} else {
		$parent_name = '';
	}

		$save_nonce     = wp_create_nonce( Membership::MEMBERSHIP_NONCE_PREFIX_DU . strval( $child_account_object['user_id'] ) );
		$edit_actions[] = array(
			__( 'Delete User' ),
			null,
			'elemental-dashicons dashicons-dismiss elemental-delete-user-account',
			array(
				'data-nonce' => $save_nonce,
				'data-type'  => $data_type,
			),
		);
		if ( true === $show_advanced ) {
			array_push(
				$edit_actions,
				array(
					__( 'Manage Files ' ),
					null,
					'elemental-dashicons dashicons-media-document elemental-file-manager',
					array(
						'data-nonce' => $save_nonce,
						'data-type'  => $data_type,
					),
				)
			);
			array_push(
				$edit_actions,
				array(
					__( 'Manage Users' ),
					null,
					'elemental-dashicons dashicons-admin-users elemental-user-manager',
					array(
						'data-nonce' => $save_nonce,
						'data-type'  => $data_type,
					),
				)
			);
	
		}
		
	?>
<tr class="active elemental-table-mobile">
	<td class="plugin-title column-primary elemental-mobile-table-row-adjust">
		<?php
		echo esc_textarea( $child_account_object['email'] );
		?>
	</td>
	<td>
		<?php
				echo esc_textarea( $child_account_object['display_name'] );
		?>
	</td>

	<td class=" plugin-title column-primary elemental-mobile-table-row-adjust">
		<?php

		echo esc_textarea( $child_account_object['last_login'] );

		?>
	</td>
	<td class="column-description elemental-mobile-table-row-adjust">
		<?php
				echo esc_textarea( $child_account_object['created'] );
		?>
	</td>
	<td>
		<?php
				echo esc_textarea( $parent_name );
		?>
	</td>
	<td>
		<?php
		foreach ( $edit_actions as $action ) {
			?>
		<a href="" class="elemental-icons <?php echo esc_attr( $action[2] ); ?>"
			data-userid="<?php echo esc_attr( $child_account_object['encrypted-user'] ); ?>"
			data-nonce="<?php echo esc_attr( $save_nonce ); ?>" title="<?php echo esc_attr( $action[0] ); ?>" 
			data-type="
			<?php
			if ( isset( $child_account_object['allusers'] ) ) {
				echo esc_attr( $child_account_object['allusers'] );
			}
			?>
			" title="<?php echo esc_attr( $action[0] ); ?>" 
		></a>
			<?php
		}
		?>
	</td>
</tr>


	<?php

	return ob_get_clean();
};
