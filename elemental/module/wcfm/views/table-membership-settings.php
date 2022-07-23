<?php
/**
 * Outputs User Membership Levels Table.
 *
 * @package ElementalPlugin\Module\WCFM\Views\table-membership-settings.php
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
					<th><?php esc_html_e( 'Account', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Created', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Name', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'myvideoroom' ); ?></th>
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
					<th><?php esc_html_e( 'Account', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Created', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Name', 'myvideoroom' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'myvideoroom' ); ?></th>
				</tr>
			</tfoot>
		</table>
		<div class="wcfm-clearfix"></div>
	</div>
</div>
		<?php
	} else {
		?>
<div class="elemental-align-left wcfm-container wcfm-top-element-container">
<p>
		<?php
			esc_html_e( 'You don\'t currently have any User Accounts Created.', 'myvideoroom' );
		?>
</p>
</div>
		<?php
	}
	return ob_get_clean();
};
