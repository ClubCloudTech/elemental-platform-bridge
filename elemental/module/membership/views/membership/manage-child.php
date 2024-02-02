<?php

/**
 * WCFM plugin view
 *
 *
 * @author  ClubCloud
 * @package module/membership/views/membership/manage-child.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param string $accounts_remaining - the data on how much account quota is remaining
 * @param string $child_account_table - the Child Accounts Table.
 * @param string $login_form - Login Form if Present.
 */

return function (
	string $add_account_form,
	string $child_account_table,
	string $login_form = null
): string {
	ob_start();
	?>
	<style>
		.elemental-sandbox-wrap {
			border: none ! important;
		}
	</style>
	<div class="collapse wcfm-collapse" id="elemental-onboard-listing">

		<div class="wcfm-collapse-content">
			<div id="wcfm_page_load "></div>
			<?php

			if ( is_user_logged_in() ) {
				?>


				<div id="elemental-notification-frame"></div>
				<div class="elemental-clearfix"></div><br />

				<div id="elemental-membership-table">
					<?php
					// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
					echo $child_account_table;
					?>

				</div>
				<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" style="display:none;">
					<h3 class="elemental-align-left"><?php esc_html_e( 'Invite User to your Dataroom', 'elemental' ); ?></h3>
					<?php
					// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
					echo $add_account_form;
					?>

				</div>
		</div>

				<?php
			}
			return ob_get_clean();
};
