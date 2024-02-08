<?php

/**
 * Outputs User Membership Levels Table.
 *
 * @package ElementalPlugin/module/membership/views/membership/table-sponsored-accounts.php
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
	string $admin_nonce = null,
): string {
	ob_start();

	?>
<div class="wcfm-container wcfm-collapse-content wcfm-main-contentainer" id="user-add-form"
    data-type="<?php echo esc_attr( $admin_nonce ); ?>" data-borg="assimilation">
    <div id="wwcfm_shop_staffsesc_html_expander wcfm-content" class="wcfm-content">
        <div class="topnav">
            <input id="elemental-search-input" type="text" placeholder="Search.." style="width:80%; height:38px;">
            <button type="submit" id="elemental-search-submit-button" class="elemental-search-button"><i
                    class="fa fa-search "></i></button>
            <a href="#" id="clear-search"
                style="display: none;"><?php esc_html_e( 'Clear Search', 'elementalplugin' ); ?></a>
            <?php
		echo '<a id="add-new-button" class="text_tip" href="#" data-tip="' . esc_html__( 'Add New Account', 'elementalplugin' ) . '"><button class="text" style="  background-color: #0d173b; border: none;  color: white;  padding: 2%;  text-align: center;  text-decoration: none;  display: inline-block;  font-size: 13px;  margin: 2px 2px;  cursor: pointer;border-radius: 8px;">' . esc_html__( 'Add New', 'wc-frontend-manager' ) . '</button></a>';
?>
        </div>

        <?php
	if ( $user_accounts ) {
		?>
        <table id="elemental-membership-table" class="display wcfm-container wcfm-top-element-container" cellspacing="0"
            width="100%">
            <thead>
                <tr>
                    <th><a href="#" class="elemental-column-sort"
                            data-sort-field="email"><?php esc_html_e( 'Account', 'elementalplugin' ); ?></a></th>
                    <th><a href="#" class="elemental-column-sort"
                            data-sort-field="display_name"><?php esc_html_e( 'Name', 'elementalplugin' ); ?></a></th>
                    <th><a href="#" class="elemental-column-sort"
                            data-sort-field="last_login"><?php esc_html_e( 'Last Login', 'elementalplugin' ); ?></a>
                    </th>
                    <th><a href="#" class="elemental-column-sort"
                            data-sort-field="created"><?php esc_html_e( 'Created', 'elementalplugin' ); ?></a></th>
                    <th><a href="#" class="elemental-column-sort"
                            data-sort-field="parent_name"><?php esc_html_e( 'Invited By', 'elementalplugin' ); ?></a>
                    </th>
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


        <?php
	} else {
		?>

        <div class="elemental-align-left wcfm-container wcfm-top-element-container">
            <p>
                <?php
				esc_html_e( 'No Users Found.', 'elementalplugin' );
		?>
            </p>
            <?php
					echo '<a id="add-new-button"  href="#" data-tip="' . esc_html__( 'Add New Account', 'elementalplugin' ) . '"><button class="text" style="  background-color: #0d173b; border: none;  color: white;  padding: 2%;  text-align: center;  text-decoration: none;  display: inline-block;  font-size: 13px;  margin: 2px 2px;  cursor: pointer;border-radius: 8px;">' . esc_html__( 'Add New', 'wc-frontend-manager' ) . '</button></a>';
		?>
        </div>
    </div><!-- wwcfm_shop_staffsesc_html_expander end -->
</div><!-- Div wcfm-container end -->
<?php
	}
	return ob_get_clean();
};