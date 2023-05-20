<?php
/**
 * Outputs User Membership Levels Table.
 *
 * @package module/wcfm/views/table-membership-accounts.php
 */

/**
 * Render the Membership User Table.
 *
 * @param array   $user_accounts the array of objects with user accounts.
 *
 * @return string
 */
return function (
	array $user_accounts = null
): string {
	ob_start();
	?>

	<?php
	if ( $user_accounts ) {
		?>
<div class="wcfm-container">
	<div id="wwcfm_shop_staffsesc_html_expander" class="wcfm-content">
		<table id="elemental-membership-table" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Account', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Type', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Created', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Name', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'elementalplugin' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$child_account_table_render = require __DIR__ . '/child-account-items.php';
				foreach ( $user_accounts as $level ) {
					//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $child_account_table_render( $level );
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php esc_html_e( 'Account', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Type', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Created', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Name', 'elementalplugin' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'elementalplugin' ); ?></th>
				</tr>
			</tfoot>
		</table>
		<div class="elemental-clearfix"></div>
	</div>
</div>
		<?php
	} else {
		?>
<div class="elemental-align-left wcfm-container wcfm-top-element-container">
<p>
		<?php
			esc_html_e( 'You don\'t currently have any User Accounts Created.', 'elementalplugin' );
		?>
</p>
</div>
		<?php
	}
	return ob_get_clean();
};
