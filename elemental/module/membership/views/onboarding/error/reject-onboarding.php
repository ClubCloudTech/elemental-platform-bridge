<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/onboarding/error/reject-onboarding.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (): string {
	ob_start();
	?>

<div class="collapse wcfm-collapse" id="elemental-onboard-listing">
	<div class="wcfm-page-headig">
		<span class="wcfmfa fa-user"></span>
		<span class="wcfm-page-heading-text"><?php esc_html_e( 'Account Creation', 'elemental' ); ?></span>
	<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load "></div>
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Create a New Organisation Account', 'elemental' ); ?></h2>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" >
		<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container" >

			<h3 class="elemental-align-left"><?php esc_html_e( 'You Must provide a Valid Membership ID to use this page.', 'elemental' ); ?></h3>

		</div>
		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />
</div>
	</div>
		<?php

		return ob_get_clean();

};
