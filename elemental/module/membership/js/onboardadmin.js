/**
 * Ajax control and Rendering JS for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Module\Membership\js\MembershipAdmin.js
 */
window.addEventListener(
	"load",
	function() {
		jQuery(
			function($) {
				/**
				 * Initialise Functions on Load
				 */
				function init() {
					$( '#submit' ).hide();
					form_type         = $( '#pageinfo' ).attr( 'data-formtype' );
					registration_flag = $( '.wcfm_registration_form_heading ' ).html();
					if ( registration_flag && registration_flag.includes( 'Registration' ) ) {
						change_to_step2();
					}
					/**
					 * Organisation Case
					 */
					if (form_type === 'org') {

						$( '#submit' ).prop( 'disabled', true );
						$( '#elemental-inbound-email' ).on( 'keyup', chkEmail );
						$( '#elemental-inbound-email' ).on( 'focusout', chkEmail );
						$( '#first_name' ).on( 'keyup', checkShowOrg );

						$( '#submit' ).click(
							function(e) {
								e.stopPropagation();
								e.preventDefault();
								createOrganisation( e );
							}
						);

						$( '#elemental-inbound-email' ).click(
							function(e) {
								e.stopPropagation();
								e.preventDefault();
								$( '#submit' ).prop( 'value', 'Add User' );
							}
						);

						$( '#first_name' ).click(
							function(e) {
								e.stopPropagation();
								e.preventDefault();
								$( '#submit' ).prop( 'value', 'Add User' );
							}
						);

						$( '#mvr-main-button-cancel' ).click(
							function(e) {
								e.stopPropagation();
								e.preventDefault();
								$( '#elemental-membership-table' ).show();
								$( '#elemental-notification-frame' ).empty();
							}
						);
					}
					/**
					 * Initialise Individual Case.
					 */
					if (form_type === 'individual') {

						$( '#submit' ).prop( 'disabled', true );
						// Email.
						$( '#elemental-inbound-email' ).on( 'keyup', chkEmail );
						$( '#elemental-inbound-email' ).on( 'focusout', chkEmail );
						// First Name.
						$( '#first_name' ).keyup(
							function(e) {
								e.stopPropagation();
								checkShowindv();
							}
						);
						$( '#first_name' ).focusout(
							function(e) {
								e.stopPropagation();
								checkShowindv();
							}
						);
						// Last Name.
						$( '#last_name' ).keyup(
							function(e) {
								e.stopPropagation();

								checkShowindv();
							}
						);
						$( '#last_name' ).focusout(
							function(e) {
								e.stopPropagation();
								checkShowindv();
							}
						);
						// Country Change.
						$( "#select2-country-container" ).mouseout(
							function(e) {
								checkShowindv();
							}
						)
						$( "#select2-country-container" ).mouseover(
							function(e) {
								checkShowindv();
							}
						)
						$( "#city" ).mouseover(
							function(e) {
								checkShowindv();
							}
						)

						// Submit.
						$( '#submit' ).click(
							function(e) {
								e.stopPropagation();
								e.preventDefault();
								createIndividual( e );
							}
						);
					}
				}
				/**
				 * Shared Functions
				 */

				/**
				 * Check email exists (used in main add new user form)
				 */
				var chkEmail = function(event) {
					event.stopPropagation();

					$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
					$( '#elemental-email-status' ).addClass( 'elemental-invalid' );
					var email       = event.target.value,
						valid_email = validateEmail( email );

					if ( ! valid_email) {
						$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
						$( '#elemental-email-status' ).removeClass( 'elemental-email-available' );
						$( '#elemental-email-status' ).removeClass( 'elemental-email-taken' );
						$( '#elemental-email-status' ).html( 'Invalid Address' );
						$( '#elemental-email-status' ).addClass( 'elemental-invalid' );
						return false;
					} else {
						$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
						$( '#elemental-email-status' ).addClass( 'elemental-checking' );
						$( '#elemental-email-status' ).html( 'Checking is Free' );
					}
					var form_data = new FormData();
					form_data.append( 'action', 'elemental_onboardadmin_ajax' );
					form_data.append( 'action_taken', 'check_email' );
					form_data.append( 'email', email );
					form_data.append( 'security', elemental_onboardadmin_ajax.security );
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_onboardadmin_ajax.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function(response) {
								var state_response = JSON.parse( response );
								console.log( state_response.available );
								if (state_response.available === false) {
									$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
									$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
									$( '#elemental-email-status' ).addClass( 'elemental-email-taken' );
									$( '#elemental-email-status' ).html( 'Email Taken' );
								} else {
									$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
									$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
									$( '#elemental-email-status' ).addClass( 'elemental-email-available' );
									$( '#elemental-email-status' ).html( 'Email Available' );
									$( '#elemental-email-status' ).attr( 'data-status', 'checked' );
									checkShowOrg();
								}
							},
							error: function(response) {
								console.log( 'Error Uploading' );
							}
						}
					);
				}

				/**
				 * Validate email format (pre check)
				 */
				function validateEmail(email) {
					var re = /\S+@\S+\.\S+/;
					return re.test( email );
				}

				/**
				 * Organisation Functions
				 */

				/**
				 * Check Organisation Dependencies
				 */
				function checkShowOrg() {
					var first_name = $( '#first_name' ).val().length,
						status     = $( '#elemental-email-status' ).data( 'status' );

					if (first_name >= 6) {
						$( '#first-name-icon' ).show();
					} else {
						$( '#first-name-icon' ).hide();
					}

					if (status === 'checked' && first_name >= 6) {
						$( '#submit' ).show();
						$( '#submit' ).prop( 'disabled', false );
					} else {
						return false;
					}
				}
				/**
				 * Create New Organisation post dependency checks (used in main add new user form)
				 */
				var createOrganisation = function(event) {
					event.stopPropagation();
					$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
					$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
					$( '#elemental-email-status' ).addClass( 'elemental-email-available' );
					$( '#elemental-email-status' ).html( 'Creating Account' );
					var membership = $( '#pageinfo' ).attr( 'data-membership' );
					console.log( membership );

					var email       = $( '#elemental-inbound-email' ).val(),
						first_name  = $( '#first_name' ).val(),
						step_window = $( '#elemental-adduser-target' ),
						form_data   = new FormData();

					form_data.append( 'action', 'elemental_onboardadmin_ajax' );
					form_data.append( 'action_taken', 'create_org' );
					form_data.append( 'email', email );
					form_data.append( 'first_name', first_name );
					form_data.append( 'membership', membership );
					form_data.append( 'security', elemental_onboardadmin_ajax.security );

					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_onboardadmin_ajax.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function(response) {
								var state_response = JSON.parse( response );
								console.log( state_response.feedback );
								if (state_response.feedback == true) {
									if (state_response.table) {
										mainvideo_parent = step_window.parent().attr( 'id' );
										parent_element   = $( '#' + mainvideo_parent );
										step_window.remove();
										step_window.parent().empty();
										parent_element.html( state_response.table );
									}
									change_to_step2();

									$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
									$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
									$( '#elemental-email-status' ).removeClass( 'elemental-email-taken' );
									$( '#elemental-email-status' ).addClass( 'elemental-email-available' );
									$( '#elemental-email-status' ).html( 'Account Created' );
									$( '#submit' ).prop( 'value', 'Account Created' );
									$( '#submit' ).prop( 'disabled', true );
									$( '#first_name' ).prop( 'value', '' );
									$( '#elemental-inbound-email' ).prop( 'value', '' );
									$( '#elemental-email-status' ).attr( 'data-status', '' );
									$( '#first-name-icon' ).hide();
									$( '#last-name-icon' ).hide();
									setTimeout( function() { $( '#wcfm_membership_register_button' ).click(); }, 500 );

								}
							},
							error: function(response) {
								console.log( 'Error Create Organisation' );
							}
						}
					);
				}

				/**
				 * Change Screen to Step 2.
				 */
				function change_to_step2() {
					$( '#stepnumber2' ).removeClass( 'elemental-hide' );
					$( '#stepname2' ).removeClass( 'elemental-hide' );
					$( '#stepimage2' ).removeClass( 'elemental-hide' );
					$( '#stepheader2' ).removeClass( 'elemental-hide' );
					$( '#stepheader1' ).addClass( 'elemental-hide' );
					$( '#stepnumber1' ).addClass( 'elemental-hide' );
					$( '#stepname1' ).addClass( 'elemental-hide' );
					$( '#stepimage1' ).addClass( 'elemental-hide' );

					$( '.elemental-onboard-header' ).hide();
					document.title = "Step 2 - Confirm Details";
					$( '.wcfm_registration_form_heading ' ).html( 'Confirm Registration E-mail and Organisation Web Address' );
					$( '.store_name' ).html( '<strong>Organisation URL Name</strong> (Must be Unique)' );
					let urlexample = $( '.description' ).html();
					urlexample     = urlexample.replace( 'http://','' );
					urloutput2     = urlexample.replace( 'http://','' );
					urloutput      = urloutput2.replace( 'your_store','your-organisation' );
					$( '.description' ).html( '<strong>' + urloutput + '</strong>' );
				}

				/**
				 * Individual Functions
				 */

				/**
				 * Check Individual Dependencies
				 */
				function checkShowindv() {
					var first_name          = $( '#first_name' ).val().length,
						last_name           = $( '#last_name' ).val().length,
						status              = $( '#elemental-email-status' ).data( 'status' ),
						country             = $( '#select2-country-container' ).attr( 'title' ),
						first_name_approved = false,
						last_name_approved  = false,
						country_check       = false;
					// First Name Checks.
					if (first_name >= 3) {
						$( '#first-name-icon' ).fadeIn( 1000 );
						first_name_approved = true;
					} else {
						$( '#first-name-icon' ).fadeOut( 1000 );
						first_name_approved = false;
					}
					// Last Name Checks.
					if (last_name >= 3) {
						$( '#last-name-icon' ).fadeIn( 1000 );
						last_name_approved = true;
					} else {
						$( '#last-name-icon' ).fadeOut( 1000 );
						last_name_approved = false;
					}
					// Country Check.
					if (country === '-Select a location-') {
						country_check = false;
					} else {
						country_check = true;
					}

					if (status === 'checked' && first_name_approved === true && last_name_approved === true && country_check === true) {
						$( '#submit' ).fadeIn( 1500 );
						$( '#submit' ).prop( 'disabled', false );
						return true;
					} else {
						$( '#submit' ).fadeOut( 1000 );
						$( '#submit' ).prop( 'disabled', true );
						return false;
					}
				}

				/**
				 * Create New Individual post checks (used in main add new user form)
				 */
				var createIndividual = function(event) {
					event.stopPropagation();
					if (checkShowindv() === false) {
						console.log( 'false return' );
						return false;
					}
					$( '#elemental-email-status' ).removeClass( 'elemental-checking' );
					$( '#elemental-email-status' ).removeClass( 'elemental-invalid' );
					$( '#elemental-email-status' ).addClass( 'elemental-email-available' );
					$( '#elemental-email-status' ).html( 'Creating Account' );
					var membership = $( '#pageinfo' ).attr( 'data-membership' );
					console.log( membership );

					var email       = $( '#elemental-inbound-email' ).val(),
						first_name  = $( '#first_name' ).val(),
						last_name   = $( '#last_name' ).val(),
						country     = $( '#select2-country-container' ).attr( 'title' ),
						company     = $( '#company' ).val(),
						city        = $( '#city' ).val(),
						step_window = $( '#elemental-adduser-target' ),
						form_data   = new FormData();

					form_data.append( 'action', 'elemental_onboardadmin_ajax' );
					form_data.append( 'action_taken', 'create_user' );
					form_data.append( 'email', email );
					form_data.append( 'first_name', first_name );
					form_data.append( 'last_name', last_name );
					form_data.append( 'country', country );
					form_data.append( 'company', company );
					form_data.append( 'city', city );
					form_data.append( 'membership', membership );
					form_data.append( 'security', elemental_onboardadmin_ajax.security );

					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_onboardadmin_ajax.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function(response) {
								var state_response = JSON.parse( response );
								console.log( state_response.feedback );
								if (state_response.feedback == true) {
									if (state_response.redirect) {
										window.location = state_response.redirect;
									}
								}
							},
							error: function(response) {
								console.log( 'Error Create Individual Subscription' );
							}
						}
					);
				}
				init();
			}
		);
	}
);
