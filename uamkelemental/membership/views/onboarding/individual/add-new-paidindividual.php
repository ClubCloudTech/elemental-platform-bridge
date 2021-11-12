<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package elemental/membership/views/onboarding/individual/add-new-paidindividual.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\HTML;
use \MyVideoRoomPlugin\Library\HttpPost;

/**
 * Render the admin page
 *
 * @return string
 */
return function (
	int $membership_id = null
): string {
	ob_start();
	global $WCFM;

	$html_library = Factory::get_instance( HTML::class, array( 'add-new-paid-user' ) );

	?>
	<div class="mvr-nav-settingstabs-outer-wrap myvideoroom-welcome-page elemental-align-left elemental-input-form">
	<div id="pageinfo" 
	data-membership="<?php echo esc_attr( $membership_id ); ?>"
	data-formtype = "individual" ></div>
	<form method="" action="#">

	<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Email Address', 'myvideoroom' ); ?></strong></p>
		<label class="screen-reader-text" for="elemental-inbound-email"></label>
		<input type="email"
			id="elemental-inbound-email"
			name="elemental-inbound-email"
			class="wcfm-text wcfm_ele elemental-email">

	<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'First Name ', 'myvideoroom' ); ?></strong></p>
		<label for="first_name" class="screen-reader-text">
			<i id="first-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="First Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="first_name"
			class="wcfm-text wcfm_ele"
			name="<?php echo esc_attr( $html_library->get_field_name( 'first_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3"
			maxlength="64"
			value=""
		>
		<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Last Name ', 'myvideoroom' ); ?></strong></p>
		<label for="last_name" class="screen-reader-text">
			<?php esc_html_e( 'Last Name ', 'myvideoroom' ); ?>
			<i id="last-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="Last Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="last_name"
			class="wcfm-text wcfm_ele"
			name="<?php echo esc_attr( $html_library->get_field_name( 'last_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3"
			maxlength="64"
			value=""
		>
		<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Company (optional) ', 'myvideoroom' ); ?></strong></p>
		<label for="company" class="screen-reader-text">
			<?php esc_html_e( 'Company (optional) ', 'myvideoroom' ); ?>
			<i id="company-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="Company Ready" style="display:none"></i>
		</label>

		<input type="text"
			id="company"
			class="wcfm-text wcfm_ele"
			name="<?php echo esc_attr( $html_library->get_field_name( 'company' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'company' ) ); ?>"
			maxlength="64"
			value=""
		>
	<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field(
			apply_filters(
				'wcfm_membership_registration_fields_address',
				array(
					'country' => array(
						'label'             => __( 'Country', 'wc-frontend-manager' ),
						'type'              => 'country',
						'name'              => '[country]',
						'custom_attributes' => array( 'required' => 1 ),
						'class'             => 'wcfm-select wcfm_ele wcfmvm_country_to_select',
						'label_class'       => 'wcfm_title wcfm_ele',
						'attributes'        => array( 'style' => 'width: 60%;' ),
						'value'             => isset( $field_value['country'] ) ? $field_value['country'] : '',
					),
					'city'    => array(
						'label'       => __( 'City/Town (optional)', 'myvideoroom' ),
						'type'        => 'text',
						'name'        => '[city]',
						'class'       => 'wcfm-text wcfm_ele',
						'label_class' => 'wcfm_title wcfm_ele',
						'value'       => isset( $field_value['city'] ) ? $field_value['city'] : '',
					),
				)
			)
		);
	?>
		<p>
		<?php
		esc_html_e(
			'Once your account is created you will receive an email with the password. You can reset it from any login screen.',
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

		<div class="mvr-left">
		</div>
		<div class="mvr-right">
			<div id="elemental-email-status" data-valid ="" class="elemental-email-status-individual elemental-membership-displayconf"><?php esc_html_e( '.', 'myvideoroom' ); ?>
			</div>
		</div>
		<div class="elemental-clear"></div>
	</div>

	<?php

	return ob_get_clean();
};
