<?php
/**
 * Handles the Add New User Procedure for Membership Management.
 *
 * @package elemental/membership/views/onboarding/individual/add-new-paidindividual.php
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
	<div class="elemental-onboard-page elemental-align-left">
	<div id="pageinfo" 
	data-membership="<?php echo esc_attr( $membership_id ); ?>"
	data-formtype = "individual" ></div>
	<form method="" action="#">

	<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Email Address', 'elementalplugin' ); ?></strong><br><?php esc_html_e( 'General Company Email address', 'elementalplugin' ); ?></p>
		<label class="screen-reader-text" for="elemental-inbound-email"></label>
		<input type="email"
			id="elemental-inbound-email"
			name="elemental-inbound-email"
			class="wcfm-text wcfm_ele elemental-email elemental-input-form">

	<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Registrant First Name ', 'elementalplugin' ); ?></strong><br><?php esc_html_e( 'Your First Name', 'elementalplugin' ); ?></p>
		<label for="first_name" class="screen-reader-text">
			<i id="first-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="First Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="first_name"
			class="wcfm-text wcfm_ele elemental-input-form"
			name="<?php echo esc_attr( $html_library->get_field_name( 'first_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3"
			maxlength="64"
			value=""
		>
		<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Registrant Last Name ', 'elementalplugin' ); ?></strong><br><?php esc_html_e( 'Your Last Name', 'elementalplugin' ); ?></p>
		<label for="last_name" class="screen-reader-text ">
			<?php esc_html_e( 'Last Name ', 'elementalplugin' ); ?>
			<i id="last-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="Last Name Ready to Go" style="display:none"></i>
		</label>

		<input type="text"
			id="last_name"
			class="wcfm-text wcfm_ele elemental-input-form"
			name="<?php echo esc_attr( $html_library->get_field_name( 'last_name' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'last_name' ) ); ?>"
			minlength="3"
			maxlength="64"
			value=""
		>
		<p class="city wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Company Name', 'elementalplugin' ); ?></strong><br><?php esc_html_e( 'The company name of sandbox owner', 'elementalplugin' ); ?></p>
		<label for="company" class="screen-reader-text">
			<?php esc_html_e( 'Company Name ', 'elementalplugin' ); ?>
			<i id="company-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="Company Ready" style="display:none"></i>
		</label>

		<input type="text"
			id="company"
			class="wcfm-text wcfm_ele elemental-input-form"
			name="<?php echo esc_attr( $html_library->get_field_name( 'company' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'company' ) ); ?>"
			maxlength="64"
			value=""
		>

		<p class="city wcfm_title wcfm_ele "><strong><?php esc_html_e( 'City', 'elementalplugin' ); ?></strong><br><?php esc_html_e( '(optional)', 'elementalplugin' ); ?></p>
		<label for="company" class="screen-reader-text">
			<?php esc_html_e( 'City ', 'elementalplugin' ); ?>
			<i id="company-name-icon" class="card myvideoroom-dashicons mvr-icons dashicons-saved" title="City Ready" style="display:none"></i>
		</label>

		<input type="text"
			id="city"
			class="wcfm-text wcfm_ele elemental-input-form"
			name="<?php echo esc_attr( $html_library->get_field_name( 'city' ) ); ?>"
			aria-describedby="<?php echo \esc_attr( $html_library->get_description_id( 'city' ) ); ?>"
			maxlength="64"
			value=""
		>

		<label class="screen-reader-text" for="country"><?php esc_html_e( 'Country ', 'elementalplugin' ); ?><span class="required">*</span></label>
		<p class="country wcfm_title wcfm_ele"><strong><?php esc_html_e( 'Country ', 'elementalplugin' ); ?><span class="required">*</span></strong></p>
		<select id="country" name="[country]" class="country_select wcfm-select wcfm_ele wcfmvm_country_to_select select2-hidden-accessible" data-required="1" data-required_message="Country: This field is required." style="width: 60%;" tabindex="-1" aria-hidden="true"><option value="">-Select a location-</option><optgroup label="-------------------------------------">
		<option value="GB">United Kingdom (UK)</option><option value="US">United States (US)</option><option value="DE">Germany</option><option value="FR">France</option>
		<option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option>
		<option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="PW">Belau</option><option value="BE">Belgium</option>
		<option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BQ">Bonaire, Saint Eustatius and Saba</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option>
		<option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option>
		<option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo (Brazzaville)</option><option value="CD">Congo (Kinshasa)</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curaçao</option><option value="CY">Cyprus</option>
		<option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="SZ">Eswatini</option><option value="ET">Ethiopia</option>
		<option value="FK">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option>
		<option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option>
		<option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option>
		<option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="CI">Ivory Coast</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option>
		<option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option>
		<option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option>
		<option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia</option>
		<option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option>
		<option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="KP">North Korea</option>
		<option value="MK">North Macedonia</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PS">Palestinian Territory</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option>
		<option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="ST">São Tomé and Príncipe</option>
		<option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="SX">Saint Martin (Dutch part)</option><option value="MF">Saint Martin (French part)</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="SA">Saudi Arabia</option>
		<option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia/Sandwich Islands</option>
		<option value="KR">South Korea</option><option value="SS">South Sudan</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option>
		<option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option>
		<option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option>
		<option value="UM">United States (US) Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="VG">Virgin Islands (British)</option><option value="VI">Virgin Islands (US)</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></optgroup></select>

		<!-- -->
		<p>

			</p>
			<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Factory::get_instance( HttpPost::class )->create_form_submit(
				'add_room',
				esc_html__( 'Next Step', 'elementalplugin' )
			);
			?>

	</form>

		<div class="mvr-left">
		</div>
		<div class="mvr-right">
			<div id="elemental-email-status" data-valid ="" class="elemental-email-status-individual elemental-membership-displayconf"><?php esc_html_e( '.', 'elementalplugin' ); ?>
			</div>
		</div>
		<div class="elemental-clear"></div>
	</div>

	<?php

	return ob_get_clean();
};
