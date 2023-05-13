/**
 * Ajax control and Rendering JS for Membership Sponsored Accounts.
 *
 * @package membership/js/membershipadmin.js
 */
window.addEventListener('load', function () {
  jQuery(function ($) {
    /**
     * Initialise Functions on Load
     */
    function init () {
      $('#elemental-login-form').show()
      $('#add-new-button').click(function (e) {
        e.stopPropagation()
        e.stopImmediatePropagation()
        e.preventDefault()
        $('#elemental-email-status').show()
        $('#elemental-email-status').removeClass()
        $('#elemental-email-status').empty()
        $('#elemental-adduser-frame').slideToggle()
      })
      $('#submit').hide()
      $('#submit').prop('disabled', true)
      $('#submitemail').hide()
      $('#submitemail').prop('disabled', true)
      $('.elemental-membership-control').on('change', dbUpload)
      $('.elemental-membership-template').on('change', templateUpload)
      $('.elemental-membership-landing-template').on(
        'change',
        landingTemplateUpload
      )
      $('#elemental-inbound-email').on('keyup', chkEmail)
	  $('#elemental-display-name-input').on('keyup', checkDisplayOnly)
	  $('#elemental-password-input').on('keyup', checkPasswordOnly)
	  
      $('#first_name').on('keyup', checkShow)
      $('#last_name').on('keyup', checkShow)
	  
      $('#elemental-email-status').hide()
      $('#submit').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        createUser(e)
        $(this).prop('value', 'Creating Account')
      })
      $('#submitemail').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
		updateEmail(e)
        $(this).prop('value', 'Updating Email')
      })
	  
	  $('#submitdisplay').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
		updateDisplayName(e)
        $(this).prop('value', 'Updating Display Name')
      })
	  $('#submitpassword').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
		updatePassword(e)
        $(this).prop('value', 'Updating Password')
      })

      hideButtons()
      $('#elemental-inbound-email').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        $('#submit').prop('value', 'Add User')
      })

      $('#first_name').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        $('#submit').prop('value', 'Add User')
      })

      $('#last_name').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        $('#submit').prop('value', 'Add User')
      })

      $('.elemental-delete-user-account').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        var user_id = $(this).attr('data-userid'),
          nonce = $(this).attr('data-nonce')
        deleteUser(e, user_id, nonce)
      })
      $('.elemental-file-manager').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        var user_id = $(this).attr('data-userid'),
          nonce = $(this).attr('data-nonce')
        manageFile(e, user_id, nonce)
      })
      $('.elemental-user-manager').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        var user_id = $(this).attr('data-userid'),
          nonce = $(this).attr('data-nonce')
        manageUser(e, user_id, nonce)
      })

      $('#elemental-main-button-cancel').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        $('#elemental-membership-table').show()
        $('#elemental-notification-frame').empty()
      })

      $('.elemental-main-button-enabled').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        user_id = $(this).attr('data-record-id')
        nonce = $(this).attr('data-auth-nonce')
        console.log(this)
        deleteUser(e, user_id, nonce, true)
      })
    }

    /**
     * Update Account Limits on Database by Subscription Level (used in backend admin page)
     */
    var dbUpload = function (event) {
      event.stopPropagation()
      var level = event.target.dataset.level,
        value = event.target.value,
        form_data = new FormData()

      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'update_db')
      form_data.append('level', level)
      form_data.append('value', value)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          $('#confirmation_' + level).html(state_response.feedback)
        },
        error: function (response) {
          console.log('Error Uploading Level')
        }
      })
    }

    /**
     * Hide Buttons
     */
    var hideButtons = function () {
      $('#submit').hide()
      $('#submit').prop('disabled', true)
      $('#submitemail').hide()
      $('#submitemail').prop('disabled', true)
    }

    /**
     * Update Account Limits on Database by Subscription Level (used in backend admin page)
     */
    var templateUpload = function (event) {
      event.stopPropagation()
      var level = event.target.dataset.level,
        value = event.target.value,
        form_data = new FormData()

      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'update_template')
      form_data.append('level', level)
      form_data.append('value', value)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          $('#confirmation_template_' + level).html(state_response.feedback)
        },
        error: function (response) {
          console.log('Error Uploading Template')
        }
      })
    }

    /**
     * Update Landing Template on Database by Subscription Level (used in backend admin page)
     */
    var landingTemplateUpload = function (event) {
      event.stopPropagation()
      var level = event.target.dataset.level,
        value = event.target.value,
        form_data = new FormData()
      console.log(level + value)
      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'update_landing_template')
      form_data.append('level', level)
      form_data.append('value', value)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          $('#confirmation_template_' + level).html(state_response.feedback)
        },
        error: function (response) {
          console.log('Error Uploading Landing Template')
        }
      })
    }

    /**
     * Check email exists (used in main add new user form)
     */
    var chkEmail = function (event) {
      event.stopPropagation()
      $('#elemental-email-status').show()
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').addClass('elemental-invalid')
      var email = event.target.value,
        valid_email = validateEmail(email)

      if (!valid_email) {
        $('#elemental-email-status').removeClass('elemental-checking')
        $('#elemental-email-status').removeClass('elemental-email-available')
        $('#elemental-email-status').removeClass('elemental-email-taken')
        $('#elemental-email-status').html('Invalid Address')
        $('#elemental-email-status').addClass('elemental-invalid')
        hideButtons()
        return false
      } else {
        $('#elemental-email-status').removeClass('elemental-invalid')
        $('#elemental-email-status').addClass('elemental-checking')
        $('#elemental-email-status').html('Checking is Free')
      }
      var form_data = new FormData()
      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'check_email')
      form_data.append('email', email)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.available)
          if (state_response.available === false) {
            $('#elemental-email-status').removeClass('elemental-checking')
            $('#elemental-email-status').removeClass(
              'elemental-email-available'
            )
            $('#elemental-email-status').removeClass('elemental-invalid')
            $('#elemental-email-status').addClass('elemental-email-taken')
            $('#elemental-email-status').html('Email Unavailable')
            hideButtons()
          } else {
            $('#elemental-email-status').removeClass('elemental-checking')
            $('#elemental-email-status').removeClass('elemental-invalid')
            $('#elemental-email-status').removeClass('elemental-email-taken')
            $('#elemental-email-status').addClass('elemental-email-available')
            $('#elemental-email-status').html('Email Available')
            $('#elemental-email-status').attr('data-status', 'checked')

            var $checkFrame = $('#elemental-user-manager-frame-id')
            if ($checkFrame.length) {
              checkMailOnly()
            } else {
              checkShow()
            }
          }
        },
        error: function (response) {
          console.log('Error Uploading')
        }
      })
    }

    /**
     * Create New User post checks (used in main add new user form)
     */
    var createUser = function (event) {
      event.stopPropagation()
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').removeClass('elemental-invalid')
      $('#elemental-email-status').addClass('elemental-email-available')
      $('#elemental-email-status').html('Creating Account')

      var email = $('#elemental-inbound-email').val(),
        first_name = $('#first_name').val(),
        last_name = $('#last_name').val(),
        type = $('#user-add-form').attr('data-type'),
        account_window = $('#elemental-membership-table'),
        counter_window = $('#elemental-remaining-counter'),
        form_data = new FormData()

      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'create_user')
      form_data.append('email', email)
      form_data.append('type', type)
      form_data.append('last_name', last_name)
      form_data.append('first_name', first_name)
      form_data.append('security', elemental_membershipadmin_ajax.security)

      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          if (state_response.status == true) {
            if (state_response.table) {
              account_window.html(state_response.table)
            }
            if (state_response.counter) {
              mainvideo_parent = counter_window.parent().attr('id')
              parent_element = $('#' + mainvideo_parent)
              counter_window.remove()
              counter_window.parent().empty()
              parent_element.html(state_response.counter)
            }
            console.log('stat' + state_response.status)
            if (state_response.status == true) {
              $('#elemental-email-status').removeClass('elemental-checking')
              $('#elemental-email-status').removeClass('elemental-invalid')
              $('#elemental-email-status').removeClass('elemental-email-taken')
              $('#elemental-email-status').addClass('elemental-email-available')
              $('#elemental-email-status').html('Account Created')
              $('#submit').prop('value', 'Account Created')
              $('#submit').prop('disabled', true)
              $('#first_name').prop('value', '')
              $('#last_name').prop('value', '')
              $('#elemental-inbound-email').prop('value', '')
              $('#elemental-email-status').attr('data-status', '')
              $('#first-name-icon').hide()
              $('#last-name-icon').hide()
              $('#elemental-adduser-frame').slideToggle()
              init()
            }
          } else {
            $('#elemental-email-status').removeClass(
              'elemental-email-available'
            )
            $('#elemental-email-status').addClass('elemental-invalid')
            $('#elemental-email-status').html(state_response.feedback)
          }
        },
        error: function (response) {
          console.log('Error Uploading')
        }
      })
    }

    /**
     * Delete User (used in main form)
     */
    var deleteUser = function (event, user_id, nonce, final) {
      event.stopPropagation()
      counter_window = $('#elemental-remaining-counter')
      var form_data = new FormData(),
        notification = $('#elemental-notification-frame'),
        account_window = $('#elemental-membership-table'),
        type = $('#user-add-form').attr('data-type'),
        counter_window = $('#elemental-remaining-counter')

      form_data.append('action', 'elemental_membershipadmin_ajax')
      if (final) {
        form_data.append('action_taken', 'delete_final')
      } else {
        form_data.append('action_taken', 'delete_user')
      }
      form_data.append('userid', user_id)
      form_data.append('nonce', nonce)
      form_data.append('type', type)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)

          if (state_response.confirmation) {
            notification.html(state_response.confirmation)
            $('#elemental-membership-table').hide()
            init()
          }
          if (state_response.feedback) {
            console.log(state_response.feedback)
          }

          if (state_response.table) {
            account_window.html(state_response.table)
          }
          if (state_response.counter) {
            mainvideo_parent = counter_window.parent().attr('id')
            parent_element = $('#' + mainvideo_parent)
            counter_window.remove()
            counter_window.parent().empty()
            parent_element.html(state_response.counter)
            $('#elemental-main-button-cancel').click()
            init()
          }
        },
        error: function (response) {
          console.log('Error in server')
        }
      })
    }

    /**
     * Manage Files - Refresh Page
     */
    var manageFile = function (event, user_id, nonce) {
      event.stopPropagation()
      var form_data = new FormData(),
        notification = $('#elemental-notification-frame'),
        account_window = $('#elemental-membership-table'),
        type = $('#user-add-form').attr('data-type')
      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'file_manage_start')
      form_data.append('userid', user_id)
      form_data.append('nonce', nonce)
      form_data.append('type', type)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)

          if (state_response.confirmation) {
            notification.html(state_response.confirmation)
          }
          if (state_response.feedback) {
            console.log(state_response.feedback)
          }

          if (state_response.table) {
            account_window.html(state_response.table)
          }
          init()
          window.elemental_stream_init()
        },
        error: function (response) {
          console.log('Error in server')
        }
      })
    }
    /**
     * Update Email
     */
    var updateEmail = function (event) {
		event.stopPropagation()
      var form_data = new FormData(),
        notification = $('#elemental-notification-frame'),
        account_window = $('#elemental-membership-table'),
		checksum = $('#elemental-welcome-page').data('checksum'),
		email = $('#elemental-inbound-email').val()

      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'update_email')
	  form_data.append('email', email )
      form_data.append('checksum', checksum)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)

          if (state_response.confirmation) {
            notification.html(state_response.confirmation)
          }
          if (state_response.feedback) {
            console.log(state_response.feedback)
          }

          if (state_response.table) {
            account_window.html(state_response.table)
          }
          init()
          window.elemental_stream_init()
        },
        error: function () {
          console.log('Error in server')
        }
      })
    }
	 /**
     * Update Email
     */
		var updateDisplayName = function (event) {
			event.stopPropagation()
		  var form_data = new FormData(),
			notification = $('#elemental-notification-frame'),
			account_window = $('#elemental-membership-table'),
			checksum     = $('#elemental-welcome-page').data('checksum'),
			display_name = $('#elemental-display-name-input').val()
	
		  form_data.append('action', 'elemental_membershipadmin_ajax')
		  form_data.append('action_taken', 'update_display_name')
		  form_data.append('display_name', display_name )
		  form_data.append('checksum', checksum)
		  form_data.append('security', elemental_membershipadmin_ajax.security)
		  $.ajax({
			type: 'post',
			dataType: 'html',
			url: elemental_membershipadmin_ajax.ajax_url,
			contentType: false,
			processData: false,
			data: form_data,
			success: function (response) {
			  var state_response = JSON.parse(response)
	
			  if (state_response.confirmation) {
				notification.html(state_response.confirmation)
			  }
			  if (state_response.feedback) {
				console.log(state_response.feedback)
			  }
	
			  if (state_response.table) {
				account_window.html(state_response.table)
			  }
			  init()
			  window.elemental_stream_init()
			},
			error: function () {
			  console.log('Error in server')
			}
		  })
		}
    /**
     * Update Password
     */
		var updatePassword = function (event) {

			event.stopPropagation()
		  var form_data = new FormData(),
			notification = $('#elemental-notification-frame'),
			account_window = $('#elemental-membership-table'),
			checksum     = $('#elemental-welcome-page').data('checksum'),
			password     = $('#elemental-password-input').val()
	
		  form_data.append('action', 'elemental_membershipadmin_ajax')
		  form_data.append('action_taken', 'update_password')
		  form_data.append('password', password )
		  form_data.append('checksum', checksum)
		  form_data.append('security', elemental_membershipadmin_ajax.security)
		  $.ajax({
			type: 'post',
			dataType: 'html',
			url: elemental_membershipadmin_ajax.ajax_url,
			contentType: false,
			processData: false,
			data: form_data,
			success: function (response) {
			  var state_response = JSON.parse(response)
	
			  if (state_response.confirmation) {
				notification.html(state_response.confirmation)
			  }
			  if (state_response.feedback) {
				console.log(state_response.feedback)
			  }
	
			  if (state_response.table) {
				account_window.html(state_response.table)
			  }
			  init()
			  window.elemental_stream_init()
			},
			error: function () {
			  console.log('Error in server')
			}
		  })
		}
	/**
     * Manage Users
     */
    var manageUser = function (event, user_id, nonce) {
      event.stopPropagation()
      var form_data = new FormData(),
        notification = $('#elemental-notification-frame'),
        account_window = $('#elemental-membership-table'),
        type = $('#user-add-form').attr('data-type')
      form_data.append('action', 'elemental_membershipadmin_ajax')
      form_data.append('action_taken', 'user_manage_start')
      form_data.append('userid', user_id)
      form_data.append('nonce', nonce)
      form_data.append('type', type)
      form_data.append('security', elemental_membershipadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_membershipadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)

          if (state_response.confirmation) {
            notification.html(state_response.confirmation)
          }
          if (state_response.feedback) {
            console.log(state_response.feedback)
          }

          if (state_response.table) {
            account_window.html(state_response.table)
          }
          init()
          window.elemental_stream_init()
		  window.elemental_screenprotect_init()
        },
        error: function (response) {
          console.log('Error in server')
        }
      })
    }
    /**
     * Check if Name and Email conditions are met in main form
     */
    function checkShow (status) {
      if ($('#first_name').length) {
        var first_name = $('#first_name').val().length
      }
      if ($('#last_name').length) {
        var last_name = $('#last_name').val().length
      }

      var status = $('#elemental-email-status').data('status')

      if (first_name >= 3) {
        $('#first-name-icon').show()
      } else {
        $('#first-name-icon').hide()
      }
      if (last_name >= 3) {
        $('#last-name-icon').show()
      } else {
        $('#last-name-icon').hide()
      }

      if (status === 'checked' && first_name >= 3 && last_name >= 3) {
        $('#submit').show()
        $('#submit').prop('disabled', false)
      } else {
        return false
      }
    }

    /**
     * Check if Name and Email conditions are met in main form
     */
    function checkMailOnly () {
      var status = $('#elemental-email-status').data('status')

      if (status === 'checked') {
        $('#submitemail').show()
        $('#submitemail').prop('disabled', false)
      } else {
        return false
      }
    }
    /**
     * Check if Name and Email conditions are met in main form
     */
    function checkDisplayOnly () {
  
		if ($('#elemental-display-name-input').length) {
			var display_name = $('#elemental-display-name-input').val().length
		  }
		
		  if (display_name >= 8) {
			$('#submitdisplay').show()
			$('#submitdisplay').prop('disabled', false)
		  } else {
			$('#submitdisplay').hide()
			$('#submitdisplay').prop('disabled', true)
		  }


	  }
	    /**
     * Check if Name and Email conditions are met in main form
     */
		function checkPasswordOnly () {
			hideButtons()
			if ($('#elemental-password-input').length) {
				var display_name = $('#elemental-password-input').val().length
			  }
			
			  if (display_name >= 8) {
				$('#submitpassword').show()
				$('#submitpassword').prop('disabled', false)
			  } else {
				$('#submitpassword').hide()
				$('#submitpassword').prop('disabled', true)
			  }
	
	
		  }
    /**
     * Validate email format JS (pre check)
     */
    function validateEmail (email) {
      var re = /\S+@\S+\.\S+/
      return re.test(email)
    }
    window.elemental_membership_init = init;
	init()
  })
})
