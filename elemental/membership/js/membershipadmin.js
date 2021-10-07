window.addEventListener("load", function(){
 
function init(){
	jQuery(function($) {
		
	/* Initialise Camera, and Listen to Buttons */
	$('#vid-picture').click(function(e){
		document.getElementById("vid-picture").classList.add('mvr-hide');
		document.getElementById("vid-up").classList.add('mvr-hide');
		document.getElementById("myvideoroom-picturedescription").classList.remove('mvr-hide');
		document.getElementById("mvr-text-description-new").classList.remove('mvr-hide');
		document.getElementById("mvr-text-description-current").classList.remove('mvr-hide');
		
	});

		$('.elemental-membership-control').on('change', dbUpload);
	});

}



var dbUpload = function (event){
	event.stopPropagation();
	jQuery(function($) {

		var file = event.target.files,
		level    = event.target.dataset.level,
		value    = event.target.value;
			var form_data = new FormData();
			
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
