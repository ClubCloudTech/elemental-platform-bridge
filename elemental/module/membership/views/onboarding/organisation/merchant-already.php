<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package membership/views/onboarding/organisation/merchant-already.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
	array $membership_data
): string {
	ob_start();
	?>

<div class="" id="elemental-onboard-listing">

	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load "></div>
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Account Creation Conflict', 'elemental' ); ?></h2>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" >
		<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container" >
<h2>
			<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'You already have a %s membership on the platform.',
						'elemental'
					),
					esc_textarea( $membership_data[0][0]->post_title ),
				);
			?>
			</h2><hr>
			<h3 class="elemental-align-left"><?php esc_html_e( 'Please sign out to buy a new organisation membership, or upgrade your current organisation in your account center', 'elemental' ); ?></h3>

		</div>
		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />
</div>
	</div>
		<?php

		return ob_get_clean();

};
