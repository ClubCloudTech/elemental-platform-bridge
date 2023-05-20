<?php
/**
 * Onboarding
 *
 * Manage Onboarding.
 *
 * @author  Fred Baumhardt
 * @package elemental/membership/views/onboarding/individual/manage-individual-free.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (): string {
	ob_start();
	?>

<div class="elemental-container" id="elemental-onboard-listing">
	<div class="elemental-container">
		<div id="wcfm_page_load" class="elemental-container"></div>
		<h2 class="elemental-onboard-header">
			<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'Create a New %s Individual Account.',
						'elemental'
					),
					esc_textarea( 'Free' ),
				);
			?>
		</h2>
		<div class="elemental-clearfix"></div>

		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container">
			<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container">
				<?php

				$output = do_shortcode( '[ihc-register]' );
				if ( $output ) {
				// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
					echo $output;
				} else {
					echo '<p class="elemental-user-create-form">' . esc_html__( 'Registration is not available as a user is currently signed in', 'elemental-plugin' ) . '</p>';
				}
				?>
			</div>

		</div>


		<?php
		return ob_get_clean();

};
