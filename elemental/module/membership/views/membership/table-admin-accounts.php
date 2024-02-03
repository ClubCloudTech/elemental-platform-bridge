<?php

/**
 * Outputs User Membership Levels Table.
 *
 * @package ElementalPlugin\Module\Membership\Views\table-sponsored-accounts.php
 */

/**
 * Render the Membership User Table.
 *
 * @param array   $user_accounts the array of objects with user accounts.
 *
 * @return string
 */
return function (
	array $user_accounts = null,
	string $accounts_remaining = null
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
							<th><?php esc_html_e( 'Created', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Type', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Name', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'elementalplugin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$child_account_table_render = include __DIR__ . '/admin-account-items.php';
						foreach ( $user_accounts as $level ) {
							//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $child_account_table_render($level);
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th><?php esc_html_e( 'Account', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Created', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Type', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Name', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'elementalplugin' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="elemental-clearfix"></div>
				<div class="wcfm-container wcfm-top-element-container" style="box-shadow: none;display: inline;">

					<?php
					echo '<a id="add-new-button" class="add_new_wcfmesc_html_ele_dashboard text_tip" href="' . esc_url( get_wcfm_shop_staffs_manage_url() ) . '" data-tip="' . esc_html__( 'Add New Account', 'elementalplugin' ) . '"><button class="text" style="  background-color: #dc143c; border: none;  color: white;  padding: 1%;  text-align: center;  text-decoration: none;  display: inline-block;  font-size: 13px;  margin: 2px 2px;  cursor: pointer;border-radius: 8px;">' . esc_html__( 'Add New', 'wc-frontend-manager' ) . '</button></a>';
					?>

					<?php
					// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
					echo $accounts_remaining;
					?>

					<div class="elemental-clearfix"></div>
				</div>
			</div><!-- wwcfm_shop_staffsesc_html_expander end -->
		</div><!-- Div wcfm-container end -->
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
