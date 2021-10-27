<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/manage-child.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 */

return function (
	string $add_account_form
): string {
	ob_start();
	?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
	<div class="wcfm-page-headig">
		<span class="wcfmfa fa-user"></span>
		<span class="wcfm-page-heading-text"><?php esc_html_e( 'Account Creation', 'myvideoroom' ); ?></span>
	<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load "></div>
	<?php

	if ( ! is_user_logged_in() ) {
		?>
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Create a New Organisation Account', 'myvideoroom' ); ?></h2>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" >
		<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container" >
			<h3 class="elemental-align-left"><?php esc_html_e( 'Add an Organisation', 'myvideoroom' ); ?></h3>
		<?php
      // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
      echo $add_account_form;
		?>

		</div>
		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />
</div>
	</div>
		<?php
	} else {
		?>
			<h3 class="elemental-align-left"><?php esc_html_e( 'You Must Be Signed Out to Access this Page', 'myvideoroom' ); ?></h3>
		<?php
	}
	return ob_get_clean();

};
