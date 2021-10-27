<?php
/**
 * WCFM plugin view
 *
 * WCFM Child Account View
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/manage-child.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param string $accounts_remaining - the data on how much account quota is remaining
 * @param string $child_account_table - the Child Accounts Table.
 * @param string $login_form - Login Form if Present.
 */

return function (
	string $add_account_form,
	string $accounts_remaining = null,
	string $child_account_table,
	string $login_form = null
): string {
	ob_start();
	$wcfm_is_allow_manage_staff = apply_filters( 'wcfm_is_allow_manage_staff', true );
	if ( ! $wcfm_is_allow_manage_staff ) {
		wcfm_restriction_message_show( 'Staffs' );
		return '';
	}

	?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
	<div class="wcfm-page-headig">
		<span class="wcfmfa fa-user"></span>
		<span class="wcfm-page-heading-text"><?php esc_html_e( 'Sponsored Accounts', 'myvideoroom' ); ?></span>
	<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load "></div>
	<?php

	if ( is_user_logged_in() ) {
		?>
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Manage Sponsored Accounts', 'myvideoroom' ); ?></h2>

		<?php

		if ( $has_new = apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
			echo '<a id="add-new-button" class="add_new_wcfmesc_html_ele_dashboard text_tip" href="' . get_wcfm_shop_staffs_manage_url() . '" data-tip="' . __( 'Add New Account', 'myvideoroom' ) . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
		}
		?>

		<?php
      // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
      echo $accounts_remaining;
		?>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" style="display:none;">
			<h3 class="elemental-align-left"><?php esc_html_e( 'Add a Sponsored Account', 'myvideoroom' ); ?></h3>
		<?php
      // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
      echo $add_account_form;
		?>

		</div>
		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />

		<div id="elemental-membership-table">
		<?php
     // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
     echo $child_account_table;
		?>
	</div>
</div>

		<?php
	}
	return ob_get_clean();

};
