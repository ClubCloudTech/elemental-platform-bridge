/**
 * Ajax control for Admin pages.
 *
 * @package Elementalplugin/admin/js/elementaladminajax.js
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

					// Refresh Room Templates and Receptions.
					$( '.elemental-maintenance-setting' ).click(
						function(e) {
							e.stopPropagation();
							e.stopImmediatePropagation();
							$( '#elemental_refresh_layout' ).fadeIn();

						}
					);
					// Refresh Room Templates and Receptions.
					$( '.elemental-maintenance-checkbox-setting' ).click(
						function(e) {
							e.stopPropagation();
							e.stopImmediatePropagation();
							$( '#elemental_refresh_layout' ).fadeIn();

						}
					);

					// Save Maintenance All Settings.
					$( '#elemental_refresh_layout' ).click(
						function(e) {
							e.stopPropagation();
							e.stopImmediatePropagation();
							e.preventDefault();
							$( '#elemental_refresh_layout' ).fadeIn();
							syncSettings();
						}
					);

					$( '#user-profile-input' ).on( 'keyup', checkform );
					$( '#group-profile-input' ).on( 'keyup', checkgroupform );

					$( '#save-user-tab' ).on( 'click', updateUsertab );
					$( '#save-group-tab' ).on( 'click', updateGrouptab );

				}
				/**
				 * Update Group Display Tab in BuddyPress
				 */
				var syncSettings = function(templateonly) {

						console.log( 'initsync settings' );
						var form_data    = new FormData();
						tab_user_profile = $( '#group-profile-input' ).val();
						form_data.append( 'action', 'elemental_admin_ajax' );
						form_data.append( 'action_taken', 'save_maintenance_settings' );
						form_data.append( 'security', elemental_admin_ajax.security );
					if (templateonly) {
						form_data.append( 'template_update', true );
					} else {
						form_data.append( 'group_tab_name', tab_user_profile );
						$( '.elemental-maintenance-setting' ).each(
							function(index) {
								form_data.append( $( this ).attr( 'id' ), $( this ).val() );
							}
						);
						$( '.elemental-maintenance-checkbox-setting' ).each(
							function(index) {
								form_data.append( $( this ).attr( 'id' ), $( this ).is(":checked") );
							}
						);
					}

						$.ajax(
							{
								type: 'post',
								dataType: 'html',
								url: elemental_admin_ajax.ajax_url,
								contentType: false,
								processData: false,
								data: form_data,
								success: function(response) {
									var state_response = JSON.parse( response );
									if (state_response.updated) {
										$( '#elemental-last-sync-time' ).html( state_response.updated );
										$( '#elemental_refresh_layout' ).fadeOut();
									}
									if (state_response.feedback) {
										$( '#notification-update-result' ).html( state_response.feedback );
										setTimeout(
											function() {
												$( '#elemental_refresh_layout' ).fadeOut();
											},
											4000
										);
										setTimeout(
											function() {
												$( '#notification-update-result' ).fadeOut();
											},
											10000
										);

									}
									init();
								},
								error: function(response) {
									console.log( 'Error Sync Settings' );
								}
							}
						);
				}
							

				

				/**
				 * BuddyPress User and Group Ajax Tab Functions
				 * Used to update Group Tab Names, and User Video Tab Names from BuddyPress module.
				 */

				/**
				 * Update User Display Name Tab in BuddyPress
				 */
				var updateUsertab = function() {
					var form_data    = new FormData();
					tab_user_profile = $( '#user-profile-input' ).val();
					form_data.append( 'action', 'elemental_admin_ajax' );
					form_data.append( 'action_taken', 'update_user_tab_name' );
					form_data.append( 'user_tab_name', tab_user_profile );
					form_data.append( 'security', elemental_admin_ajax.security );
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_admin_ajax.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function(response) {
								var state_response = JSON.parse( response );
								$( '#save-user-tab' ).prop( 'value', state_response.feedback );

							},
							error: function(response) {
								console.log( 'Error Uploading' );
							}
						}
					);
				}

				/**
				 * Update Group Display Tab in BuddyPress
				 */
				var updateGrouptab = function() {
						var form_data    = new FormData();
						tab_user_profile = $( '#group-profile-input' ).val();
						form_data.append( 'action', 'elemental_admin_ajax' );
						form_data.append( 'action_taken', 'update_group_tab_name' );
						form_data.append( 'group_tab_name', tab_user_profile );
						form_data.append( 'security', elemental_admin_ajax.security );
						$.ajax(
							{
								type: 'post',
								dataType: 'html',
								url: elemental_admin_ajax.ajax_url,
								contentType: false,
								processData: false,
								data: form_data,
								success: function(response) {
									var state_response = JSON.parse( response );
									$( '#save-group-tab' ).prop( 'value', state_response.feedback );

								},
								error: function(response) {
									console.log( 'Error Uploading' );
								}
							}
						);
				}
					/**
					 * Check if Length Conditions Met for Submit Users
					 */
				function checkform() {
					var input_check = $( '#user-profile-input' ).val().length;
					$( '#save-user-tab' ).prop( 'value', 'Save' );
					if (input_check >= 5) {
						$( '#save-user-tab' ).show();
						$( '#save-user-tab' ).prop( 'disabled', false );
					} else {
						$( '#save-user-tab' ).hide();
					}
					if (input_check < 5) {
						return false;
					}
				}
				/**
				 * Check if Length Conditions Met for Submit Groups
				 */
				function checkgroupform() {
					var input_check = $( '#group-profile-input' ).val().length;
					$( '#save-group-tab' ).prop( 'value', 'Save' );
					if (input_check >= 5) {
						$( '#save-group-tab' ).show();
						$( '#save-group-tab' ).prop( 'disabled', false );
					} else {
						$( '#save-group-tab' ).hide();
					}
					if (input_check < 5) {
						return false;
					}
				}

				init();
			}
		);
	}
);
