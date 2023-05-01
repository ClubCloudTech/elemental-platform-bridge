<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/onboarding/individual/manage-individual-paid.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\UltimateMembershipPro\Library\ShortCodesUMP;

return function (
	string $paid_user_form = null,
	string $redirect_url = null
): string {
	ob_start();
	if ( $redirect_url ) {
			echo '<script type="text/javascript"> window.location = "' . esc_url( $redirect_url ) . '"; </script>';
			echo '<h1>' . esc_html_e( 'Thank you for Your Purchase ', 'elemental' ) . '</h1>';
			die();
	}
	?>

<div class="elemental-container elemental-onboard-page" id="elemental-onboard-listing">
	<div class="elemental-container">
		<div id="wcfm_page_load" class="elemental-container"></div>
			<h3 class="elemental-onboard-header">
				<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'Create a New %s Account',
						'elementalplugin'
					),
					esc_textarea( Factory::get_instance( ShortCodesUMP::class )->render_level_name() ),
				);
				?>
			</h2>
			<div class="wcfm-clearfix"></div>

		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container">
			<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container">
				<?php
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
			echo $paid_user_form;
				?>
			</div>

		</div>


		<?php
		return ob_get_clean();

};
