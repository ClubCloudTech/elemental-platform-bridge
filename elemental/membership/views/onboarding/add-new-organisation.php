<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package elemental/membership/views/onboarding/add-new-organisation.php
 */

use ElementalPlugin\Factory;
use MyVideoRoomPlugin\Library\HTML;
use \MyVideoRoomPlugin\Library\HttpPost;

/**
 * Render the admin page
 *
 * @return string
 */
return function (): string {
	ob_start();

	$html_library = Factory::get_instance( HTML::class, array( 'site-conference-center-new-room' ) );

	?>
	<div class="mvr-nav-settingstabs-outer-wrap myvideoroom-welcome-page elemental-align-left">

	<form method="" action="#">

		<div class="elemental-email-wrapper">
		<label class="elemental-align-left" for="<?php echo esc_attr( $html_library->get_id( 'title' ) ); ?>">
	<?php echo esc_html__( 'Contact Email Address:  ', 'myvideoroom' ) . '<strong>' . esc_html__( '(Please use generic organisation email and not an individual)', 'myvideoroom' ) . '</strong>'; ?>
		</label>
		<input type="email"
			id="elemental-inbound-email"
			name="elemental-inbound-email"
			class="elemental-membership-displayconf"

		></div>
		<label class="elemental-align-right" for="elemental-email-status-box">
	<?php esc_html_e( 'Status ', 'myvideoroom' ); ?>
		</label>
		<div id="elemental-email-status" data-valid ="" class="elemental-email-status elemental-membership-displayconf"><?php esc_html_e( 'Waiting to Check ', 'myvideoroom' ); ?> </div>

		<p class="elemental-clear" id="elemental-email-status-box">
	<?php
	esc_html_e(
		'This email address must be unique on our system and should be if possible a shared address independent of an individual in the organisation who could leave/change roles. drones@organisation, info@organisation, etc work well and usually can receive notifications. ',
		'myvideoroom'
	);
	?>
		</p>

		<hr />
<div class="mvr-left">
		<label for="first_name">
			<?php esc_html_e( 'Organisation Display Name ', 'myvideoroom' ); ?>
			<i id="first-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="First Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="first_name"
			name="<?php echo esc_attr( $html_library->get_field_name( 'slug' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'slug' ) ); ?>"
			minlength="6"
			maxlength="64"
			value=""
		>
		</div>
		<div class="mvr-right">
		</div>
		<hr />

		<p>
	<?php
	esc_html_e(
		'Once your Sponsored account is created the user will receive an email with their reset password procedure',
		'myvideoroom'
	);
	?>
		</p>

	<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo Factory::get_instance( HttpPost::class )->create_form_submit(
		'add_room',
		esc_html__( 'Next Step', 'myvideoroom' )
	);
	?>
	</form>
	</div>
	<?php

	return ob_get_clean();
};
