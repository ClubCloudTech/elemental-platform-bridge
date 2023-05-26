/**
 * Ajax control and Rendering JS for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Module\Membership\js\MembershipAdmin.js
 */
window.addEventListener('load', function () {
  jQuery(function ($) {
    /**
     * Initialise Functions on Load
     */
    function init () {
      $('#submit').hide()
      form_type = $('#pageinfo').attr('data-formtype')
      registration_flag = $('.wcfm_registration_form_heading ').html()
      if (registration_flag && registration_flag.includes('Registration')) {
        change_to_step2()
      }
      chkPwd()
      chkCompany()
      /**
       * Basics
       */
      $('#submit').prop('disabled', true)
      // Email.
      $('#elemental-inbound-email').on('keyup', chkEmail)
      $('#elemental-inbound-email').on('focusout', chkEmail)
      // Company Check.
      $('#company').on('keyup', chkCompany)
      $('#company').on('focusout', chkCompany)

      // Submit.
      $('#elemental-inbound-email').click(function (e) {
        e.stopPropagation()
        e.preventDefault()
        $('#submit').prop('value', 'Activate Account')
      })

      /**
       * Organisation Case
       */
      if (form_type === 'org') {
        $('#first_name').on('keyup', checkShowOrg)
        $('#first_name').click(function (e) {
          e.stopPropagation()
          e.preventDefault()
          $('#submit').prop('value', 'Activate Account')
        })

        $('#elemental-main-button-cancel').click(function (e) {
          e.stopPropagation()
          e.preventDefault()
          $('#elemental-membership-table').show()
          $('#elemental-notification-frame').empty()
        })
        $('#submit').click(function (e) {
          e.stopPropagation()
          e.preventDefault()
          createOrganisation(e)
        })
      }
      /**
       * Initialise Free Tenant (no subscription) Case.
       */
      if (form_type === 'free-tenant') {
        chkEmail()
        checkShowfree()

        // First Name.
        $('#first_name').keyup(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        $('#first_name').focusout(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        // Last Name.
        $('#last_name').keyup(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        $('#last_name').focusout(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        // Company Name.
        $('#company').keyup(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        $('#company').focusout(function (e) {
          e.stopPropagation()
          checkShowfree()
        })
        // Country Change.
        $('#select2-country-container').mouseout(function (e) {
          checkShowfree()
        })
        $('#select2-country-container').mouseover(function (e) {
          checkShowfree()
        })
        $('#select2-country-container').change(function (e) {
          checkShowfree()
        })
        $('#city').mouseover(function (e) {
          checkShowfree()
        })
        $('#submit').click(function (e) {
          e.stopPropagation()
          e.preventDefault()
          createFreeTenant(e)
        })
      }

      /**
       * Initialise Individual Case.
       */
      if (form_type === 'individual') {
        chkEmail()
        checkShowindv()

        // First Name.
        $('#first_name').keyup(function (e) {
          e.stopPropagation()
          checkShowindv()
        })
        $('#first_name').focusout(function (e) {
          e.stopPropagation()
          checkShowindv()
        })
        // Last Name.
        $('#last_name').keyup(function (e) {
          e.stopPropagation()
          checkShowindv()
        })
        $('#last_name').focusout(function (e) {
          e.stopPropagation()
          checkShowindv()
        })
        // Country Change.
        $('#select2-country-container').mouseout(function (e) {
          checkShowindv()
        })
        $('#select2-country-container').mouseover(function (e) {
          checkShowindv()
        })
        $('#select2-country-container').change(function (e) {
          checkShowindv()
        })
        $('#city').mouseover(function (e) {
          checkShowindv()
        })
        $('#submit').click(function (e) {
          e.stopPropagation()
          e.preventDefault()
          createIndividual(e)
        })
      }
    }

    /**
       * Create New Organisation post dependency checks (used in main add new user form)
       */
    var createFreeTenant = function (event) {
      event.stopPropagation()
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').removeClass('elemental-invalid')
      $('#elemental-email-status').addClass('elemental-email-available')
      $('#elemental-email-status').html('Creating Account')
      var membership = $('#pageinfo').attr('data-membership'),
        email = $('#elemental-inbound-email').val(),
        first_name = $('#first_name').val(),
        last_name = $('#last_name').val(),
        company = $('#company').val(),
        country = $('#select2-country-container').val(),
        password = $('#password').val(),

      form_data = new FormData()
      form_data.append('action', 'elemental_onboardadmin_ajax')
      form_data.append('action_taken', 'create_free_tenant')
      form_data.append('email', email)
      form_data.append('first_name', first_name)
      form_data.append('last_name', last_name)
      form_data.append('company', company)
      form_data.append('country', country)
      form_data.append('password', password)
      form_data.append('membership', membership)
      form_data.append('security', elemental_onboardadmin_ajax.security)

      $.ajax({


        type: 'post',
        dataType: 'html',
        url: elemental_onboardadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          
          if (state_response.feedback) {
            user_window = $('elemental-adduser-frame')
            user_window.empty()
            user_window.html(state_response.feedback)
          }
          console.log(state_response.redirect)
            setTimeout(function () {
              window.location=state_response.redirect
            }, 1500)
          
        },
        error: function (response) {
          console.log('Error Create Organisation')
        }
      })
    }
    /**
     * Check Free Tenant Dependencies
     */
    function checkShowfree () {
      var first_name = $('#first_name').val().length,
        last_name = $('#last_name').val().length,
        company_status = $('#elemental-company-status').data('status'),
        email_status = $('#elemental-email-status').data('status'),
        pwdstatus = $('#pageinfo').attr('data-pwdstatus'),
        country = $('#select2-country-container').attr('title'),
        first_name_approved = false,
        last_name_approved = false,
        country_check = false
      // First Name Checks.
      if (first_name >= 3) {
        $('#first-name-icon').fadeIn(200)
        first_name_approved = true
      } else {
        $('#first-name-icon').fadeOut(200)
        first_name_approved = false
      }
      // Last Name Checks.
      if (last_name >= 3) {
        $('#last-name-icon').fadeIn(200)
        last_name_approved = true
      } else {
        $('#last-name-icon').fadeOut(200)
        last_name_approved = false
      }

      // Country Check.
      if (country === '-Select a location-') {
        $('#country-icon').fadeOut(200)
        country_check = false
      } else {
        country_check = true
        $('#country-icon').fadeIn(200)
      }
      if (
        email_status === 'checked' &&
        company_status === 'checked' &&
        pwdstatus === 'checked' &&
        first_name_approved === true &&
        last_name_approved === true &&
        country_check === true
      ) {
        $('#submit').fadeIn(500)
        $('#submit').prop('disabled', false)

        return true
      } else {
        $('#submit').fadeOut(200)
        $('#submit').prop('disabled', true)
        $('#elemental-email-status').fadeIn(500)

        return false
      }
    }


    /**
     * Organisation Functions
     */

    /**
     * Check Organisation Dependencies
     */
    function checkShowOrg () {
      var first_name = $('#first_name').val().length,
        status = $('#elemental-email-status').data('status')

      if (first_name >= 6) {
        $('#first-name-icon').show()
      } else {
        $('#first-name-icon').hide()
      }

      if (status === 'checked' && first_name >= 6) {
        $('#submit').show()
        $('#submit').prop('disabled', false)
      } else {
        return false
      }
    }
    /**
     * Create New Organisation post dependency checks (used in main add new user form)
     */
    var createOrganisation = function (event) {
      event.stopPropagation()
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').removeClass('elemental-invalid')
      $('#elemental-email-status').addClass('elemental-email-available')
      $('#elemental-email-status').html('Creating Account')
      var membership = $('#pageinfo').attr('data-membership')
      console.log(membership)

      var email = $('#elemental-inbound-email').val(),
        first_name = $('#first_name').val(),
        step_window = $('#elemental-adduser-target'),
        form_data = new FormData()

      form_data.append('action', 'elemental_onboardadmin_ajax')
      form_data.append('action_taken', 'create_org')
      form_data.append('email', email)
      form_data.append('first_name', first_name)
      form_data.append('membership', membership)
      form_data.append('security', elemental_onboardadmin_ajax.security)

      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_onboardadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          if (state_response.feedback == true) {
            if (state_response.table) {
              mainvideo_parent = step_window.parent().attr('id')
              parent_element = $('#' + mainvideo_parent)
              step_window.remove()
              step_window.parent().empty()
              parent_element.html(state_response.table)
            }
            change_to_step2()

            $('#elemental-email-status').removeClass('elemental-checking')
            $('#elemental-email-status').removeClass('elemental-invalid')
            $('#elemental-email-status').removeClass('elemental-email-taken')
            $('#elemental-email-status').addClass('elemental-email-available')
            $('#elemental-email-status').html('Account Created')
            $('#submit').prop('value', 'Account Created')
            $('#submit').prop('disabled', true)
            $('#first_name').prop('value', '')
            $('#elemental-inbound-email').prop('value', '')
            $('#elemental-email-status').attr('data-status', '')
            $('#first-name-icon').hide()
            $('#last-name-icon').hide()
            setTimeout(function () {
              $('#wcfm_membership_register_button').click()
            }, 500)
          }
        },
        error: function (response) {
          console.log('Error Create Organisation')
        }
      })
    }

    /**
     * Individual Functions
     */

    /**
     * Check Individual Dependencies
     */
    function checkShowindv () {
      var first_name = $('#first_name').val().length,
        last_name = $('#last_name').val().length,
        status = $('#elemental-email-status').data('status'),
        country = $('#select2-country-container').attr('title'),
        first_name_approved = false,
        last_name_approved = false,
        country_check = false
      // First Name Checks.
      if (first_name >= 4) {
        $('#first-name-icon').fadeIn(200)
        first_name_approved = true
      } else {
        $('#first-name-icon').fadeOut(200)
        first_name_approved = false
      }
      // Last Name Checks.
      if (last_name >= 3) {
        $('#last-name-icon').fadeIn(200)
        last_name_approved = true
      } else {
        $('#last-name-icon').fadeOut(200)
        last_name_approved = false
      }
      // Country Check.
      if (country === '-Select a location-') {
        $('#country-icon').fadeOut(200)
        country_check = false
      } else {
        country_check = true
        $('#country-icon').fadeIn(200)
      }

      if (
        status === 'checked' &&
        first_name_approved === true &&
        last_name_approved === true &&
        country_check === true
      ) {
        $('#submit').fadeIn(500)
        $('#submit').prop('disabled', false)
        return true
      } else {
        $('#submit').fadeOut(200)
        $('#submit').prop('disabled', true)
        return false
      }
    }

    /**
     * Create New Individual post checks (used in main add new user form)
     */
    var createIndividual = function (event) {
      event.stopPropagation()
      if (checkShowindv() === false) {
        console.log('false return')
        return false
      }
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').removeClass('elemental-invalid')
      $('#elemental-email-status').addClass('elemental-email-available')
      $('#elemental-email-status').html('Creating Account')
      var membership = $('#pageinfo').attr('data-membership')
      console.log(membership)

      var email = $('#elemental-inbound-email').val(),
        first_name = $('#first_name').val(),
        last_name = $('#last_name').val(),
        country = $('#select2-country-container').attr('title'),
        company = $('#company').val(),
        city = $('#city').val(),
        form_data = new FormData()

      form_data.append('action', 'elemental_onboardadmin_ajax')
      form_data.append('action_taken', 'create_tenant')
      form_data.append('email', email)
      form_data.append('first_name', first_name)
      form_data.append('last_name', last_name)
      form_data.append('country', country)
      form_data.append('company', company)
      form_data.append('city', city)
      form_data.append('membership', membership)
      form_data.append('security', elemental_onboardadmin_ajax.security)

      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_onboardadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.feedback)
          if (state_response.feedback == true) {
            if (state_response.redirect) {
              window.location = state_response.redirect
            }
          }
        },
        error: function (response) {
          console.log('Error Creating Tenant User')
        }
      })
    }
    
    /**
     * Shared Functions
     */

    /**
     * Check email exists (used in main add new user form)
     */
    var chkEmail = function () {
      $('#elemental-email-status').removeClass('elemental-checking')
      $('#elemental-email-status').addClass('elemental-invalid')
      var email = $('#elemental-inbound-email').val(),
        valid_email = validateEmail(email),
        form_type = $('#pageinfo').attr('data-formtype')
      if ( email.length >= 1 ) {
        console.log('Email Check');
      } else {
        $('#elemental-email-status').removeClass('elemental-invalid')
        return;
      }

      if (!valid_email) {
        $('#elemental-email-status').removeClass('elemental-checking')
        $('#elemental-email-status').removeClass('elemental-email-available')
        $('#elemental-email-status').removeClass('elemental-email-taken')
        $('#elemental-email-status').html('Invalid Address')
        $('#elemental-email-status').addClass('elemental-invalid')
        $('#email-icon').fadeOut(200)
        $('#elemental-email-status').fadeIn(500)
        $('#submit').hide()
        return false
      } else {
        $('#elemental-email-status').removeClass('elemental-invalid')
        $('#elemental-email-status').removeClass('elemental-email-available')
        $('#elemental-email-status').removeClass('elemental-email-taken')
        $('#elemental-email-status').addClass('elemental-checking')
        $('#elemental-email-status').html('Checking is Free')
        //$('#elemental-email-status').fadeOut(500)
      }
      var form_data = new FormData()
      form_data.append('action', 'elemental_onboardadmin_ajax')
      form_data.append('action_taken', 'check_email')
      form_data.append('email', email)
      form_data.append('security', elemental_onboardadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_onboardadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.available)
          if (state_response.available === false) {
            $('#elemental-email-status').removeClass('elemental-checking')
            $('#elemental-email-status').removeClass('elemental-email-available')
            $('#elemental-email-status').removeClass('elemental-invalid')
            $('#elemental-email-status').addClass('elemental-email-taken')
            $('#email-icon').hide()
            $('#elemental-email-status').html('Email Taken')
            return;
          } else {
            $('#elemental-email-status').removeClass('elemental-checking')
            $('#elemental-email-status').removeClass('elemental-invalid')
            $('#elemental-email-status').removeClass('elemental-email-taken')
            $('#elemental-email-status').addClass('elemental-email-available')
            $('#email-icon').fadeIn(200)
            $('#elemental-email-status').html('Email Available')
            $('#elemental-email-status').attr('data-status', 'checked')
            if (form_type === "free-tenant") {
              checkShowfree()
            } else if (form_type === "org") {
              checkShowOrg()
            } else {
              checkShowindv()
            }


          }
        },
        error: function (response) {
          console.log('Error Uploading')
        }
      })
    }
    /**
    * Check company exists (prevent duplicates)
    */
    var chkCompany = function () {
      $('#elemental-company-status').removeClass('elemental-email-taken')
      $('#elemental-company-status').removeClass('elemental-checking')
      $('#elemental-company-status').removeClass('elemental-email-available')
      $('#elemental-company-status').html('Invalid Company')
      $('#elemental-company-status').addClass('elemental-invalid')
      var form_type = $('#pageinfo').attr('data-formtype'),
        company = $('#company').val()
      // Last Name Checks.
      if (company.length < 5) {
        $('#company-icon').fadeOut(200)
        return false

      }

      var form_data = new FormData()
      form_data.append('action', 'elemental_onboardadmin_ajax')
      form_data.append('action_taken', 'check_company')
      form_data.append('company', company)
      form_data.append('security', elemental_onboardadmin_ajax.security)
      $.ajax({
        type: 'post',
        dataType: 'html',
        url: elemental_onboardadmin_ajax.ajax_url,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
          var state_response = JSON.parse(response)
          console.log(state_response.available)
          if (state_response.available === false) {
            $('#elemental-company-status').removeClass('elemental-checking')
            $('#elemental-company-status').removeClass('elemental-invalid')
            $('#elemental-company-status').addClass('elemental-email-taken')
            $('#elemental-company-status').html('Company Name Taken')
            $('#company-icon').fadeOut(200)

          } else {
            $('#elemental-company-status').removeClass('elemental-checking')
            $('#elemental-company-status').removeClass('elemental-invalid')
            $('#elemental-company-status').addClass('elemental-email-available')
            $('#elemental-company-status').html('Company Available')
            $('#elemental-company-status').attr('data-status', 'checked')
            $('#company-icon').fadeIn(200)
            if (form_type === "free-tenant") {
              checkShowfree()
            } else if (form_type === "org") {
              checkShowOrg()
            } else {
              checkShowindv()
            }
          }
        },
        error: function (response) {
          console.log('Error Uploading')
        }
      })
    }
    
    
    /**
    * Change Screen to Step 2.
    */
    function change_to_step2 () {
      $('#stepnumber2').removeClass('elemental-hide')
      $('#stepname2').removeClass('elemental-hide')
      $('#stepimage2').removeClass('elemental-hide')
      $('#stepheader2').removeClass('elemental-hide')
      $('#stepheader1').addClass('elemental-hide')
      $('#stepnumber1').addClass('elemental-hide')
      $('#stepname1').addClass('elemental-hide')
      $('#stepimage1').addClass('elemental-hide')

      $('.elemental-onboard-header').hide()
      document.title = 'Step 2 - Confirm Details'
      $('.wcfm_registration_form_heading ').html(
        'Confirm Registration E-mail and Organisation Web Address'
      )
      $('.store_name').html(
        '<strong>Organisation URL Name</strong> (Must be Unique)'
      )
      let urlexample = $('.description').html()
      urlexample = urlexample.replace('http://', '')
      urloutput2 = urlexample.replace('http://', '')
      urloutput = urloutput2.replace('your_store', 'your-organisation')
      $('.description').html('<strong>' + urloutput + '</strong>')
    }

    /**
    * Validate email format (pre check)
    */
    function validateEmail (email) {
      var re = /\S+@\S+\.\S+/
      return re.test(email)
    }

    /**
    * Password Dialog Box
    */
    var chkPwd = function () {
      var myInput = document.getElementById("password");
      var letter = document.getElementById("letter");
      var capital = document.getElementById("capital");
      var number = document.getElementById("number");
      var length = document.getElementById("length");
      
      // When the user clicks on the password field, show the message box
      myInput.onfocus = function () {
        document.getElementById("message").style.display = "block";
      }

      // When the user clicks outside of the password field, hide the message box
      myInput.onblur = function () {
        document.getElementById("message").style.display = "none";
      }

      // When the user starts to type something inside the password field
      myInput.onkeyup = function () {

        var length_check, lowercase_check, uppercase_check, numbers_check

        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
          letter.classList.remove("invalid");
          letter.classList.add("valid");
          lowercase_check = true;
        } else {
          letter.classList.remove("valid");
          letter.classList.add("invalid");
          lowercase_check = false;
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
          capital.classList.remove("invalid");
          capital.classList.add("valid");
          uppercase_check = true;
        } else {
          capital.classList.remove("valid");
          capital.classList.add("invalid");
          uppercase_check = false;
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
          number.classList.remove("invalid");
          number.classList.add("valid");
          numbers_check = true;
        } else {
          number.classList.remove("valid");
          number.classList.add("invalid");
          numbers_check = false;
        }

        // Validate length
        if (myInput.value.length >= 8) {
          length.classList.remove("invalid");
          length.classList.add("valid");
          length_check = true;

        } else {
          length.classList.remove("valid");
          length.classList.add("invalid");
          length_check = false;
        }

        var password = $('#password').val();
        var strength = 0;
        var tips = "";

        // Check password length
        if (password.length < 8) {
        } else {
          strength += 1;
        }

        // Check for mixed case
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
          strength += 1;
        } else {
        }

        // Check for numbers
        if (password.match(/\d/)) {
          strength += 1;
        } else {
        }

        // Check for special characters
        if (password.match(/[^a-zA-Z\d]/)) {
          strength += 1;
        } else {
        }

        // Update the text and color based on the password strength
        var passwordStrengthElement = $('#password-strength');
        if (strength < 2) {
          passwordStrengthElement.text("Password Too Easy " + tips);
          //passwordStrengthElement.css('color', 'red');
          $('#password-strength').removeClass('elemental-checking')
          $('#password-strength').removeClass('elemental-email-available')
          $('#password-strength').removeClass('elemental-invalid')
          $('#password-strength').addClass('elemental-email-taken')
          $('#password-strength').removeClass('elemental-invalid')
        } else if (strength === 2) {
          passwordStrengthElement.text("Password Medium ");
          $('#password-strength').removeClass('elemental-checking')
          $('#password-strength').removeClass('elemental-email-available')
          $('#password-strength').removeClass('elemental-email-taken')
          $('#password-strength').addClass('elemental-invalid')
        } else if (strength === 3) {
          passwordStrengthElement.text("Pasword Strong " + tips);
          $('#password-strength').addClass('elemental-checking')
          $('#password-strength').removeClass('elemental-email-available')
          $('#password-strength').removeClass('elemental-email-taken')
          $('#password-strength').removeClass('elemental-invalid')
        } else {
          passwordStrengthElement.text("Password Very Strong " + tips);
          $('#password-strength').removeClass('elemental-checking')
          $('#password-strength').addClass('elemental-email-available')
          $('#password-strength').removeClass('elemental-email-taken')
          $('#password-strength').removeClass('elemental-invalid')
        }



        if (lowercase_check && uppercase_check && numbers_check && length_check) {
          $('#pageinfo').attr('data-pwdstatus', 'checked')
          $('#password-icon').fadeIn(200)
          document.getElementById("message").style.display = "none";
        } else {
          $('#pageinfo').attr('data-pwdstatus', '')
          $('#password-icon').fadeOut(200)
        }
        if (form_type === "free-tenant") {
          checkShowfree()
        } else if (form_type === "org") {
          checkShowOrg()
        } else {
          checkShowindv()
        }


      }
    }

    
    init()
  })
})
