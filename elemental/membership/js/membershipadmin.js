window.addEventListener("load", function(){
	jQuery(function($) {
		function init(){
			
			$('#mvr-login-form').show();	
			$('#add-new-button').click(function(e){
				e.stopPropagation();
				e.stopImmediatePropagation();
				e.preventDefault();
				$('#elemental-adduser-frame').slideToggle();
				
			});
				$('#submit').hide();
				$('#submit').prop('disabled', true );
				$('.elemental-membership-control').on('change', dbUpload);
				$('#elemental-inbound-email').on('keyup', chkEmail);
				$('#first_name').on('keyup', checkShow);
				$('#last_name').on('keyup', checkShow);
			
				$('#submit').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					createUser(e);
					$(this).prop('value', 'Creating Account');		
				});	

				$('#elemental-inbound-email').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					$('#submit').prop('value', 'Add User');
				});	
				$('#first_name').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					$('#submit').prop('value', 'Add User');
				});	
				$('#last_name').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					$('#submit').prop('value', 'Add User');
				});	
				$('.elemental-delete-user-account').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					var user_id = $( this ).attr( 'data-userid' ),
					nonce       = $( this ).attr( 'data-nonce' );
					deleteUser(e, user_id, nonce);
				});	
				$('#mvr-main-button-cancel').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					$('#elemental-membership-table').show();
					$('#elemental-notification-frame').empty();
				});	
				$('.mvr-main-button-enabled').click(function(e){
					e.stopPropagation();
					e.preventDefault();
					user_id       = $( this ).attr( 'data-record-id' );
					nonce       = $( this ).attr( 'data-auth-nonce' );
					deleteUser(e, user_id, nonce, true);
				});	
				
				
				
		}

	var dbUpload = function (event){
		event.stopPropagation();
			var file = event.target.files,
			level    = event.target.dataset.level,
			value    = event.target.value,
			form_data = new FormData();
				
				form_data.append('action','elemental_membershipadmin_ajax');
					

						form_data.append('action_taken', 'update_db' );
						form_data.append('level', level );
						form_data.append('value', value );
						form_data.append('security', elemental_membershipadmin_ajax.security );
						$.ajax(
							{
								type: 'post',
								dataType: 'html',
								url: elemental_membershipadmin_ajax.ajax_url,
								contentType: false,
								processData: false,
								data: form_data,
								success: function (response) {
									var state_response = JSON.parse( response );
									console.log( state_response.feedback );
									$('#confirmation_' + level ).html('Saved');

								},
								error: function ( response ){
									console.log('Error Uploading');
								}
							}
						);
		}

		var chkEmail = function (event){
			event.stopPropagation();
			
				$('#elemental-email-status').removeClass('elemental-checking');
				$('#elemental-email-status').addClass('elemental-invalid');
				var email     = event.target.value,
				valid_email   =  validateEmail(email);

				if ( ! valid_email ){
					$('#elemental-email-status').removeClass('elemental-checking');
					$('#elemental-email-status').removeClass('elemental-email-available');
					$('#elemental-email-status').removeClass('elemental-email-taken');
					$('#elemental-email-status').html('Invalid Address');
					$('#elemental-email-status').addClass('elemental-invalid');
					return false;
				} else {
					$('#elemental-email-status').removeClass('elemental-invalid');
					$('#elemental-email-status').addClass('elemental-checking');
					$('#elemental-email-status').html('Checking is Free');
				}

					var form_data = new FormData();
					
					form_data.append('action','elemental_membershipadmin_ajax');
						

							form_data.append('action_taken', 'check_email' );
							form_data.append('email', email );
							form_data.append('security', elemental_membershipadmin_ajax.security );
							$.ajax(
								{
									type: 'post',
									dataType: 'html',
									url: elemental_membershipadmin_ajax.ajax_url,
									contentType: false,
									processData: false,
									data: form_data,
									success: function (response) {
										var state_response = JSON.parse( response );
										console.log( state_response.available );
										if (state_response.available === false ){
											$('#elemental-email-status').removeClass('elemental-checking');
											$('#elemental-email-status').removeClass('elemental-invalid');
											$('#elemental-email-status').addClass('elemental-email-taken');
											$('#elemental-email-status').html('Email Taken');
										} else {
											$('#elemental-email-status').removeClass('elemental-checking');
											$('#elemental-email-status').removeClass('elemental-invalid');
											$('#elemental-email-status').addClass('elemental-email-available');
											$('#elemental-email-status').html('Email Available');
											$('#elemental-email-status').attr('data-status','checked');
											checkShow();
										}

									},
									error: function ( response ){
										console.log('Error Uploading');
									}
								}
							);
			}
		
		var createUser = function (event){
			event.stopPropagation();
				$('#elemental-email-status').removeClass('elemental-checking');
				$('#elemental-email-status').removeClass('elemental-invalid');
				$('#elemental-email-status').addClass('elemental-email-available');
				$('#elemental-email-status').html('Creating Account');
				
				var email      = $('#elemental-inbound-email').val(),
				first_name     = $('#first_name').val(),
				last_name      = $('#last_name').val(),
				account_window = $('#elemental-membership-table'),
				counter_window = $('#elemental-remaining-counter');

					var form_data = new FormData();
					
					form_data.append('action','elemental_membershipadmin_ajax');
					form_data.append('action_taken', 'create_user' );
					form_data.append('email', email );
					form_data.append('last_name', last_name );
					form_data.append('first_name', first_name );
					form_data.append('security', elemental_membershipadmin_ajax.security );

						$.ajax(
							{
								type: 'post',
								dataType: 'html',
								url: elemental_membershipadmin_ajax.ajax_url,
								contentType: false,
								processData: false,
								data: form_data,
								success: function (response) {
									var state_response = JSON.parse( response );
									console.log( state_response.feedback );
									if ( state_response.feedback == true ){
										if ( state_response.table ) {
											account_window.html( state_response.table );
										}
										if ( state_response.counter ) {
											mainvideo_parent = counter_window.parent().attr( 'id' );
											parent_element = $('#'+mainvideo_parent);
											counter_window.remove();
											counter_window.parent().empty();
											parent_element.html(state_response.counter);
											
										}
										$('#elemental-email-status').removeClass('elemental-checking');
										$('#elemental-email-status').removeClass('elemental-invalid');
										$('#elemental-email-status').removeClass('elemental-email-taken');
										$('#elemental-email-status').addClass('elemental-email-available');
										$('#elemental-email-status').html('Account Created');
										$('#submit').prop('value', 'Account Created');	
										$('#submit').prop('disabled', true );
										$('#first_name').prop('value', '');
										$('#last_name').prop('value', '');
										$('#elemental-inbound-email').prop('value', '');
										$('#elemental-email-status').attr('data-status','');
										$('#first-name-icon').hide();
										$('#last-name-icon').hide();
									} else {

									}

								},
								error: function ( response ){
									console.log('Error Uploading');
								}
							}
						);
			}

		var deleteUser = function (event, user_id, nonce, final ){
			event.stopPropagation();

				

				counter_window = $('#elemental-remaining-counter');
	
					var form_data  = new FormData(),
					notification   = $('#elemental-notification-frame'),
					account_window = $('#elemental-membership-table'),
					counter_window = $('#elemental-remaining-counter');
					
					form_data.append('action','elemental_membershipadmin_ajax');
					if ( final ) {
						form_data.append('action_taken', 'delete_final' );
					} else {
						form_data.append('action_taken', 'delete_user' );
					}
					

					form_data.append('userid', user_id );
					form_data.append('nonce', nonce );
					form_data.append('security', elemental_membershipadmin_ajax.security );
	
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_membershipadmin_ajax.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse( response );

							if ( state_response.confirmation ) {
								notification.html( state_response.confirmation );
								$('#elemental-membership-table').hide();
								init();
							}
							if ( state_response.feedback ) {
								console.log( state_response.feedback );
							}

							if ( state_response.table ) {
								account_window.html( state_response.table );
							}
							if ( state_response.counter ) {
								mainvideo_parent = counter_window.parent().attr( 'id' );
								parent_element = $('#'+mainvideo_parent);
								counter_window.remove();
								counter_window.parent().empty();
								parent_element.html(state_response.counter);
								$('#mvr-main-button-cancel').click();
								init();
							}

							},
							error: function ( response ){
								console.log('Error Uploading');
							}
						}
					);
			}

		function checkShow( status ){
			var first_name = $('#first_name').val().length,
			last_name = $('#last_name').val().length,
			status = $('#elemental-email-status').data('status');

				if (first_name >= 3) {
					$('#first-name-icon').show();
				} else {
					$('#first-name-icon').hide();
				}
				if (last_name >= 3) {
					$('#last-name-icon').show();
				} else {
					$('#last-name-icon').hide();
				}
					
				if ( status ==='checked' && first_name >=3 && last_name >=3 ) {
					$('#submit').show();
					$('#submit').prop('disabled', false );
				} else {
					return false;
				}
				
		}

		function validateEmail(email) {
				var re = /\S+@\S+\.\S+/;
				return re.test(email);
			}

	function deleteMe () {

			var form_data = new FormData();
			form_data.append('action','elemental_membershipadmin_ajax');
					
				console.log('Picture Delete');
				var room_name  = $( '#roominfo' ).data( 'roomName' );
				display_name   = $( '#vid-name' ).val(),
				form_data.append('room_name', room_name );
				form_data.append('display_name', display_name );

				form_data.append('action_taken', 'delete_me' );
				form_data.append('security', elemental_membershipadmin_ajax.security );
				$.ajax(
					{
						type: 'post',
						dataType: 'html',
						url: elemental_membershipadmin_ajax.ajax_url,
						contentType: false,
						processData: false,
						data: form_data,
						success: function (response) {
							var state_response = JSON.parse( response );
							if (state_response.errormessage){
								console.log(state_response.errormessage);
							}
							$('.mvr-forget-me').hide();
							setTimeout( () => {  window.location.reload (); }, 1500 );
							;
						},
						error: function ( response ){
							console.log('Error Deleting');
						}
					}
				);
			
		document.getElementById("mvr-top-notification").innerHTML += '<br><div><strong>Your Records have been deleted</strong></div>';

	}
	
	init();
	});

});
