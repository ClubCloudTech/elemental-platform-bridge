<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package elemental/membership/views/onboarding/organisation/add-new-organisation.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\HttpPost;

/**
 * Render the admin page
 *
 * @return string
 */
return function (
	int $membership_id = null
): string {
	ob_start();

	$html_library = Factory::get_instance( HTML::class, array( 'site-conference-center-new-room' ) );

	?>
	<div class="elemental-align-left">
	<div id="pageinfo" 
	data-membership="<?php echo esc_attr( $membership_id ); ?>"
	data-formtype = "org">
	</div>
	<form method="" action="">
	<div class="elemental-email-wrapper">
		<label class="elemental-align-left" for="<?php echo esc_attr( $html_library->get_id( 'title' ) ); ?>">
		<?php echo esc_html__( 'Contact Email Address:  ', 'myvideoroom' ) . '<strong>' . esc_html__( '(Please use generic organisation email and not an individual)', 'myvideoroom' ) . '</strong>'; ?>
		</label>
		<input type="email"
			id="elemental-inbound-email"
			name="elemental-inbound-email"
			class="elemental-membership-displayconf elemental-text-input-box-background">
	</div>
	<div>
	<div id="elemental-email-status" data-valid ="" class="elemental-email-status">
	</div>
	<p class="elemental-clear" id="elemental-email-status-box">
	<?php
	esc_html_e(
		'This email address should be a shared mailbox and not an individual email. Example info@yourcompany or drones@yourcompany',
		'myvideoroom'
	);
	?>
		</p>
</div>
<div class="mvr-left">
		<label for="first_name">
			<?php esc_html_e( 'Organisation Display Name ', 'myvideoroom' ); ?>
			<i id="first-name-icon" class="card elemental-dashicons mvr-icons dashicons-saved" title="First Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="first_name"
			name="New User Onboarding"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'slug' ) ); ?>"
			minlength="6"
			maxlength="64"
			value=""
			class="elemental-membership-displayconf elemental-text-input-box-background"
		>
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo Factory::get_instance( HttpPost::class )->create_form_submit(
			'add_room',
			esc_html__( 'Next Step', 'myvideoroom' )
		);
		?>
		</div>
		<div class="mvr-right">

		</div>
		<p class="elemental-clear" id="elemental-email-status-box">
	<?php
	esc_html_e(
		'Once your Organisation account is created you will receive an email with the password. You can then create an individual account for yourself.',
		'myvideoroom'
	);
	?>
	</p>
	</form>
	</div>
	<?php

	return ob_get_clean();
};
