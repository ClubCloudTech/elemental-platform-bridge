<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/onboarding/individual/manage-subscription-free.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
	string $paid_user_form = null,
	string $redirect_url = null,
	array $level_data
): string {
	$user_logged_in = is_user_logged_in();
	$level_name     = $level_data['label'];
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
		<h3 class="elemental-onboard-header elemental-user-create-form">
			<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'Create a New %s Account.',
						'elementalplugin'
					),
					'<b>' . esc_textarea( $level_name ) . '</b>',
				);
			?>
		</h3>
		<p class="elemental-role-description">
			<?php echo esc_textarea( $level_data['description'] ); ?>
		</p>
		<div class="elemental-clearfix"></div>

		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container">
			<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container">
				<?php
				if ( $user_logged_in ) {
					echo '<p class="elemental-user-create-form">' . esc_html__( 'Registration is not available as a user is currently signed in', 'elemental-plugin' ) . '</p>';
				} else {

				// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped (already escaped in its view)
				echo $paid_user_form;
				}
				?>
			</div>

		</div>


		<?php
		return ob_get_clean();

};
