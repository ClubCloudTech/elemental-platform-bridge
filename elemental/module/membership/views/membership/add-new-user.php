<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package ElementalPlugin\Module\Membership\Views\add-new-user.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\HttpPost;

/**
 * Render the admin page
 *
 * @return string
 */
return function (): string {
	ob_start();

	$html_library = Factory::get_instance( HTML::class, array( 'site-conference-center-new-room' ) );

	?>
	<div class="row erow headrow" style=" text-align: center; background: #323064;color:white;font-size:15px;padding:1%; border-top-left-radius: 10px; border-top-right-radius: 10px;">
		<div class="col-xs-6" id="demoContainer">
			Add User
		</div>
	</div>
	<div class="mvr-nav-settingstabs-outer-wrap elemental-align-left">

		<form method="" action="#">


			<div class="form-group">
				<label class="col-xs-3 control-label" for="<?php echo esc_attr( $html_library->get_id( 'title' ) ); ?>">
					<?php esc_html_e( 'Email Address ', 'myvideoroom' ); ?>
				</label>
				<span>( 
				<?php
						esc_html_e(
							'Please enter the User email address, note this must be unique on our platform, and we will confirm prior to submission',
							'myvideoroom'
						);
				?>
						)</span>
				<div class="col-xs-5">

					<input type="email" id="elemental-inbound-email" name="elemental-inbound-email" class="form-control elemental-membership-displayconf">
					<span id="elemental-email-status" data-valid="" class="elemental-email-status elemental-membership-displayconf"></span>
				</div>

			</div>

			<div class="form-group">
				<label class="col-xs-3 control-label" for="first_name">
					<?php esc_html_e( 'First Name ', 'myvideoroom' ); ?>
					<i id="first-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="First Name Ready to Go" style="display:none"></i>
				</label>
				<div class="col-xs-5">
					<input type="text" class="form-control" id="first_name" name="<?php echo esc_attr( $html_library->get_field_name( 'slug' ) ); ?>" aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'slug' ) ); ?>" minlength="3" maxlength="64" value="" style="border: 1px solid black; width:60%;">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-3 control-label" for="last_name">
					<?php esc_html_e( 'Last Name ', 'myvideoroom' ); ?>
					<i id="last-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved " title="Last Name Ready to Go" style="display:none"></i>
				</label>
				<div class="col-xs-5">
					<input type="text" class="form-control" id="last_name" name="<?php echo esc_attr( $html_library->get_field_name( 'slug' ) ); ?>" aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'slug' ) ); ?>" minlength="3" maxlength="64" value="" style="border: 1px solid black; width:60%;">
				</div>
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
				esc_html__( 'Add User', 'myvideoroom' )
			);
			?>
		</form>


	</div>
	<?php

	return ob_get_clean();
};
