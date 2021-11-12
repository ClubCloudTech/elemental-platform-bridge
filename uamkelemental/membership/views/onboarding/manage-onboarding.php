<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package membership/views/onboarding/manage-onboarding.php
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

<div class="elemental-container" id="wcfm_shop_listing">
	<div class="elemental-container">
		<div id="wcfm_page_load" class="elemental-container"></div>
			<h2 class="elemental-onboard-header">
				<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'Create a New %s Organisation Account.',
						'myvideoroom'
					),
					esc_textarea( $membership_data[0]->post_title ),
				);
				?>
			</h2>
			<div class="wcfm-clearfix"></div>

		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container">
			<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container">
				<?php
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
			echo $add_account_form;
				?>
			</div>
			<?php
			if ( $membership_data ) {
				?>

				<div id="elemental-extra" class="elemental-container elemental-background-item">
					<h3 class="elemental-align-left"><?php esc_html_e( 'Plan Details', 'myvideoroom' ); ?></h3>
					<div class="elemental-plan-details mvr-nav-settingstabs-outer-wrap">
					<?php
						// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
						echo $membership_data[0]->post_excerpt;
					?>
					</div>
				</div>
				<div>
					<div id="elemental-notification-frame"></div>
					<div class="wcfm-clearfix"></div><br />
				</div>

				<?php
			}
			?>
		</div>


		<?php
		return ob_get_clean();

};
