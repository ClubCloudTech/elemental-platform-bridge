<?php
/**
 * WCFM plugin view
 *
 * WCFM Child Account View
 *
 * @author      Club Cloud based on template from WC Lovers
 * @package     wcfmgs/view
 * @version   1.0.0
 *
 * @param string $add_account_form - add an account form
 */

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\Library\MembershipUMP;

return function (
	string $add_account_form,
	string $accounts_remaining
): string {
	ob_start();

	global $WCFM;

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

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Manage Sponsored Accounts', 'myvideoroom' ); ?></h2>

			<?php
			if ( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
			<a target="_blank" class="wcfm_wp_admin_view text_tip"
				href="<?php echo admin_url( 'users.php?role=shop_staff' ); ?>"
				data-tip="<?php esc_html_e( 'WP Admin View', 'myvideoroom' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}

			if ( $has_new = apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
				echo '<a id="add-new-button" class="add_new_wcfmesc_html_ele_dashboard text_tip" href="' . get_wcfm_shop_staffs_manage_url() . '" data-tip="' . __( 'Add New Account', 'myvideoroom' ) . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>

			<?php
			if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
				echo '<div class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You Have Unlimited Accounts Remaining ', 'myvideoroom' ) . '</div>';
			} elseif ( $accounts_remaining > 0 ) {
				echo '<div class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You Have ', 'myvideoroom' ) . esc_textarea( $accounts_remaining ) . esc_html__( ' accounts remaining', 'myvideoroom' ) . '</div>';
			}
			?>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" style="display:none;">
			<h3 class="elemental-align-left"><?php esc_html_e( 'Add a Sponsored Account', 'myvideoroom' ); ?></h3>
			<?php
				echo $add_account_form;
			?>

		</div>
		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />

		<div id="elemental-membership-table">
			<div class="wcfm-container">
				<div id="wwcfm_shop_staffsesc_html_expander" class="wcfm-content">
					<table id="wcfm-shop-staffs" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Account', 'myvideoroom' ); ?></th>
								<th><?php esc_html_e( 'Created', 'myvideoroom' ); ?></th>
								<th><?php esc_html_e( 'Name', 'myvideoroom' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'myvideoroom' ); ?></th>
							</tr>
						</thead>
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
		</div>
			</div>
</div>
	<?php
	return ob_get_clean();

};
