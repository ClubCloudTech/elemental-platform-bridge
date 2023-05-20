<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package membership/views/onboarding/organisation/manage-onboarding.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
	string $add_account_form,
	array $membership_data = null
): string {
	ob_start();
	?>

<div class="elemental-onboard-outerwrap" id="elemental-onboard-listing">
	<div class="elemental-container">
		<div id="wcfm_page_load" class="elemental-container"></div>
			<h4 class="elemental-onboard-header">
				<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'Create a New %s Organisation.',
						'elemental'
					),
					esc_textarea( $membership_data[0]->post_title ),
				);
				?>
			</h4>
			<div class="elemental-clearfix"></div>

		<div id="elemental-adduser-frame" class="elemental-onboard-text">
			<div id="elemental-adduser-target">
				<?php
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
			echo $add_account_form;
				?>
			</div>
		</div>


		<?php
		return ob_get_clean();

};
