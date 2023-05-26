<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package elemental/membership/views/organisation/individual/add-new-freetenant.php
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

	$html_library = Factory::get_instance( HTML::class, array( 'add-new-paid-user' ) );

	?>
<div class="elemental-onboard-page elemental-align-left elemental-user-create-form">
	<div id="pageinfo" data-membership="<?php echo esc_attr( $membership_id ); ?>" data-formtype="free-tenant"></div>
	<form method="" action="#">
	<!-- Email Section -->	
	<p class="city wcfm_title wcfm_ele">
			<strong><?php esc_html_e( 'Email', 'elementalplugin' ); ?><span
					class="required">*</span></strong><br>
		</p>
		<label class="screen-reader-text" for="elemental-inbound-email"></label>
		<input type="email" id="elemental-inbound-email" name="elemental-inbound-email"
			class="wcfm-text wcfm_ele elemental-email elemental-text-box"><i id="email-icon"
			class="card elemental-dashicons elemental-icons dashicons-saved" title="Email Ready to Go"
			style="display:none"></i>
	<!-- First Name Section -->
		<p class="city wcfm_title wcfm_ele">
			<strong><?php esc_html_e( 'First Name ', 'elementalplugin' ); ?><span
					class="required">*</span></strong><br>
		</p>

		<label for="first_name" class="screen-reader-text"><?php esc_html_e( 'First Name ', 'elementalplugin' ); ?>
		</label>
		<input type="text" id="first_name" class="wcfm-text wcfm_ele elemental-text-box"
			name="<?php echo esc_attr( $html_library->get_field_name( 'first_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3" maxlength="64" value=""><i id="first-name-icon"
			class="card elemental-dashicons elemental-icons dashicons-saved" title="First Name Ready to Go"
			style="display:none"></i>
	<!-- Last Name Section -->
		<p class="city wcfm_title wcfm_ele">
			<strong><?php esc_html_e( 'Last Name ', 'elementalplugin' ); ?><span
					class="required">*</span></strong>
		</p>

		<label for="last_name" class="screen-reader-text "><?php esc_html_e( 'Last Name ', 'elementalplugin' ); ?></label>

		<input type="text" id="last_name" class="wcfm-text wcfm_ele elemental-text-box"
			name="<?php echo esc_attr( $html_library->get_field_name( 'last_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3" maxlength="64" value=""><i id="last-name-icon"
			class="card elemental-dashicons elemental-icons dashicons-saved" title="Last Name Ready to Go"
			style="display:none;"></i>
<!-- Password Section-->
<p class="city wcfm_title wcfm_ele">
			<strong><?php esc_html_e( 'Password', 'elementalplugin' ); ?><span
					class="required">*</span></strong><br>
		</p>

		<label for="password" class="screen-reader-text "><?php esc_html_e( 'Password', 'elementalplugin' ); ?></label>
		<input type="password" id="password" class="wcfm-text wcfm_ele elemental-password-field"
		pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required
			name="<?php echo esc_attr( $html_library->get_field_name( 'password' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'password' ) ); ?>"
			minlength="8" maxlength="32" value=""><i id="password-icon"
			class="card elemental-dashicons elemental-icons dashicons-saved" title="Password Ready to Go"
			style="display:none;"></i>
			<div id="message" class="elemental-password-table" style="display:none;">
			<table >
			<h4>Password must contain the following:</h4>
				<tbody>
					<tr>
						<td class="elemental-password-td">
						<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
						</td>
					<td class="elemental-password-td">
					<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
					</td>
				<td class="elemental-password-td"><p id="number" class="invalid">A <b>number</b></p></td>
					<td class="elemental-password-td"><p id="length" class="invalid">Minimum <b>8 characters</b></p></td>
				</tr>
				</tbody>
			</table>
		</div>
<!-- Company Name-->
		<p class="city wcfm_title wcfm_ele">
			<strong><?php esc_html_e( 'Company Name', 'elementalplugin' ); ?><span
					class="required">*</span></strong><br>
		</p>
		<label for="company" class="screen-reader-text">
			<?php esc_html_e( 'Company Name ', 'elementalplugin' ); ?>

		</label>

		<input type="text" id="company" class="wcfm-text wcfm_ele elemental-input-restrict-alphanumeric elemental-text-box"
			name="<?php echo esc_attr( $html_library->get_field_name( 'company' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'company' ) ); ?>" maxlength="64"
			value=""> <i id="company-icon" class="card elemental-dashicons elemental-icons dashicons-saved"
			title="Company Ready" style="display:none"></i>
<!--  Country-->
		<label class="screen-reader-text" for="country"><?php esc_html_e( 'Country ', 'elementalplugin' ); ?><span
				class="required">*</span></label>
		<p class="country wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Country ', 'elementalplugin' ); ?><span
					class="required">*</span></strong></p>
		<select id="country" name="[country]"
			class="elemental-text-box country_select wcfm-select wcfm_ele wcfmvm_country_to_select select2-hidden-accessible "
			data-required="1" data-required_message="Country: This field is required." style="width: 60%;" tabindex="-1"
			aria-hidden="true">
			<option value="">-Select a location-</option>
			<optgroup label="-------------------------------------">
				<option value="GB">United Kingdom (UK)</option>
				<option value="US">United States (US)</option>
				<option value="FR">France</option>
				<option value="DE">Germany</option>
				<option value="ZA">South Africa</option>
				<option value="AD">Andorra</option>
				<option value="AU">Australia</option>
				<option value="AT">Austria</option>
				<option value="BH">Bahrain</option>
				<option value="BE">Belgium</option>
				<option value="BA">Bosnia and Herzegovina</option>
				<option value="BW">Botswana</option>
				<option value="BR">Brazil</option>
				<option value="BG">Bulgaria</option>
				<option value="HR">Croatia</option>
				<option value="CY">Cyprus</option>
				<option value="CZ">Czech Republic</option>
				<option value="DK">Denmark</option>
				<option value="EE">Estonia</option>
				<option value="SZ">Eswatini</option>
				<option value="FI">Finland</option>
				<option value="GI">Gibraltar</option>
				<option value="GR">Greece</option>
				<option value="GG">Guernsey</option>
				<option value="HU">Hungary</option>
				<option value="IS">Iceland</option>
				<option value="IN">India</option>
				<option value="IE">Ireland</option>
				<option value="IM">Isle of Man</option>
				<option value="IL">Israel</option>
				<option value="IT">Italy</option>
				<option value="JP">Japan</option>
				<option value="JE">Jersey</option>
				<option value="KE">Kenya</option>
				<option value="KW">Kuwait</option>
				<option value="LV">Latvia</option>
				<option value="LB">Lebanon</option>
				<option value="LS">Lesotho</option>
				<option value="LI">Liechtenstein</option>
				<option value="LT">Lithuania</option>
				<option value="LU">Luxembourg</option>
				<option value="MT">Malta</option>
				<option value="MU">Mauritius</option>
				<option value="MD">Moldova</option>
				<option value="MC">Monaco</option>
				<option value="ME">Montenegro</option>
				<option value="MZ">Mozambique</option>
				<option value="NA">Namibia</option>
				<option value="NL">Netherlands</option>
				<option value="NZ">New Zealand
				<option value="NG">Nigeria</option>
				<option value="MK">North Macedonia</option>
				<option value="NO">Norway</option>
				<option value="PL">Poland</option>
				<option value="PT">Portugal</option>
				<option value="PR">Puerto Rico</option>
				<option value="QA">Qatar</option>
				<option value="RO">Romania</option>
				<option value="SM">San Marino</option>
				<option value="SA">Saudi Arabia</option>
				<option value="RS">Serbia</option>
				<option value="SG">Singapore</option>
				<option value="SK">Slovakia</option>
				<option value="SI">Slovenia</option>
				<option value="KR">South Korea</option>
				<option value="ES">Spain</option>
				<option value="SE">Sweden</option>
				<option value="CH">Switzerland</option>
				<option value="TR">Turkey</option>
				<option value="UA">Ukraine</option>
				<option value="AE">United Arab Emirates</option>
				<option value="VA">Vatican</option>
			</optgroup>
		</select>
		<i id="country-icon" class="card elemental-dashicons elemental-icons dashicons-saved"
				title="Country Ready" style="display:none"></i>

		<div class="">
		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Factory::get_instance( HttpPost::class )->create_form_submit(
				'add_subscription',
				esc_html__( 'Activate Account', 'elementalplugin' )
			);
		?>
		<strong>Requirements:</strong>
	<div id="elemental-company-status" style="display:none;"data-valid="" class="elemental-company-status-individual elemental-membership-displayconf"></div>
	<div id="password-strength" style="display:none;" class="elemental-membership-displayconf elemental-email-taken">No Password</div>	
	<div id="elemental-email-status" style="display:none;" data-valid="" class="elemental-membership-displayconf"></div>
	</div>

	<!-- Button -->
	<div class="elemental-clear"></div>
	</form>


</div>

	<?php

	return ob_get_clean();
};
