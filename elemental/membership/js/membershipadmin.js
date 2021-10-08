window.addEventListener("load", function(){
 
function init(){
	jQuery(function($) {
		
	$('#add-new-button').click(function(e){
		e.stopPropagation();
		e.preventDefault();
		$('#elemental-adduser-frame').slideToggle();
		
	});
		$('#submit').hide();
		$('.elemental-membership-control').on('change', dbUpload);
		$('#elemental-inbound-email').on('keyup', chkEmail);
		$('#first_name').on('keyup', checkShow);
		$('#last_name').on('keyup', checkShow);
		
	});

}



var dbUpload = function (event){
	event.stopPropagation();
	jQuery(function($) {
		
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
	});

}

var chkEmail = function (event){
	event.stopPropagation();
	jQuery(function($) {
		$('#elemental-email-status').removeClass('elemental-checking');
		$('#elemental-email-status').addClass('elemental-invalid');
		var email     = event.target.value,
		valid_email   =  validateEmail(email);
//		console.log(email);

		if ( ! valid_email ){
			$('#elemental-email-status').html('Invalid Address');
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
	});

}

function checkShow( status ){
	jQuery(function($) {
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
} else {
	return false;
}
});
	
}
function validateEmail(email) 
    {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

	  function updateName () {
		var textvalue = document.getElementById('vid-name').value;
	
		if ( textvalue.length < 1 ){
			alert( 'You can not enter a blank Display Name');
			return false;
		}

			// Prepare Form.
			var form_data = new FormData();
			form_data.append('action','elemental_membershipadmin_ajax');
					
			jQuery(function($) {
				console.log('start update');
				var room_name = $( '#roominfo' ).data( 'roomName' ),
				status_message     = $( '#mvr-postbutton-notification' ),
				display_name  = $( '#vid-name' ).val();
				form_data.append('room_name', room_name );
				form_data.append('display_name', display_name );

				form_data.append('action_taken', 'update_display_name' );
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
							if ( state_response.feedback ) {
								status_message.html( state_response.feedback );
								setTimeout( function() { 
									status_message.fadeOut(); 
								}, 6000 );
								setTimeout( function() { 
									status_message.empty();
									$(status_message).removeAttr('style');
								}, 8000 );
							}
							refreshWelcome();
							if (state_response.errormessage){
								console.log(state_response.errormessage);
							}
							$('.mvr-forget-me').show();
							;
						},
						error: function ( response ){
							console.log('Error Uploading');
						}
					}
				);
			});
	 
  }

  function deleteMe () {

		// Prepare Form.
		var form_data = new FormData();
		form_data.append('action','elemental_membershipadmin_ajax');
				
		jQuery(function($) {
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
		});
		
	document.getElementById("mvr-top-notification").innerHTML += '<br><div><strong>Your Records have been deleted</strong></div>';

}

	  
	 

	 let vidnamecheck = document.getElementById("vid-name");
	 if ( vidnamecheck ) {
		document.getElementById("vid-name").onkeyup = function() {
			document.getElementById("vid-name").innerHTML='';
			document.getElementById("vid-down").disabled = false;
		};
	 }
	  

init();


});
