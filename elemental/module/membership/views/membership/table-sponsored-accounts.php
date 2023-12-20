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
	string $accounts_remaining = null,
	string $admin_nonce = null,
): string {
	ob_start();
	?>

	<?php
	if ( $user_accounts ) {
		?>
		<div class="wcfm-container wcfm-collapse-content wcfm-main-contentainer" id="user-add-form" data-type="<?php echo esc_attr( $admin_nonce ); ?>">
			<div id="wwcfm_shop_staffsesc_html_expander wcfm-content" class="wcfm-content">
				<table id="elemental-membership-table" class="display wcfm-container wcfm-top-element-container" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Account', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Name', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Last Login', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Created', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Invited By', 'elementalplugin' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'elementalplugin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$child_account_table_render = include __DIR__ . '/child-account-items.php';
						foreach ( $user_accounts as $account ) {
							//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $child_account_table_render($account);
						}
						?>
					</tbody>
					<tfoot>

					</tfoot>
				</table>
				<div class="elemental-clearfix"></div>
				<div class="wcfm-container wcfm-top-element-container" style="box-shadow: none;display: inline;">

					<?php
					echo '<a id="add-new-button" class="text_tip" href="#" data-tip="' . esc_html__( 'Add New Account', 'elementalplugin' ) . '"><button class="text" style="  background-color: #0d173b; border: none;  color: white;  padding: 2%;  text-align: center;  text-decoration: none;  display: inline-block;  font-size: 13px;  margin: 2px 2px;  cursor: pointer;border-radius: 8px;">' . esc_html__( 'Add New', 'wc-frontend-manager' ) . '</button></a>';
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
				esc_html_e( 'You don\'t currently have any users invited.', 'elementalplugin' );
				?>
			</p>
			<?php
					echo '<a id="add-new-button"  href="#" data-tip="' . esc_html__( 'Add New Account', 'elementalplugin' ) . '"><button class="text" style="  background-color: #0d173b; border: none;  color: white;  padding: 2%;  text-align: center;  text-decoration: none;  display: inline-block;  font-size: 13px;  margin: 2px 2px;  cursor: pointer;border-radius: 8px;">' . esc_html__( 'Add New', 'wc-frontend-manager' ) . '</button></a>';
			?>
		</div>
		<?php
	}
	return ob_get_clean();
};
