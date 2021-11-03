/**
 * Ajax control and Rendering JS for Membership Sponsored Accounts.
 *
 * @package ElementalPlugin\Membership\js\MembershipAdmin.js
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
                    $('#submit').hide();
                    form_type = $('#pageinfo').attr('data-formtype');
                    /* Separating Org and Individual Case */
                    if (form_type === 'org') {

                        $('#mvr-login-form').show();
                        $('#submit').prop('disabled', true);
                        $('.elemental-membership-control').on('change', dbUpload);
                        $('#elemental-inbound-email').on('keyup', chkEmail);
                        $('#elemental-inbound-email').on('focusout', chkEmail);
                        $('#first_name').on('keyup', checkShowOrg);

                        $('#submit').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                createOrganisation(e);
                                $(this).prop('value', 'Creating Account');
                            }
                        );

                        $('#elemental-inbound-email').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                $('#submit').prop('value', 'Add User');
                            }
                        );

                        $('#first_name').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                $('#submit').prop('value', 'Add User');
                            }
                        );

                        $('.elemental-delete-user-account').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                var user_id = $(this).attr('data-userid'),
                                    nonce = $(this).attr('data-nonce');
                                deleteUser(e, user_id, nonce);
                            }
                        );

                        $('#mvr-main-button-cancel').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                $('#elemental-membership-table').show();
                                $('#elemental-notification-frame').empty();
                            }
                        );

                        $('.mvr-main-button-enabled').click(
                            function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                user_id = $(this).attr('data-record-id');
                                nonce = $(this).attr('data-auth-nonce');
                                deleteUser(e, user_id, nonce, true);
                            }
                        );
                    }
                    if (form_type === 'individual') {
                        $('#submit').prop('disabled', true);
                        //Email.
                        $('#elemental-inbound-email').on('keyup', chkEmail);
                        $('#elemental-inbound-email').on('focusout', chkEmail);
                        //First Name.
                        $('#first_name').keyup(function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            checkShowindv();
                        });
                        $('#first_name').focusout(function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            checkShowindv();
                        });
                        //Last Name.
                        $('#last_name').keyup(function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            checkShowindv();
                        });
                        $('#last_name').focusout(function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            checkShowindv();
                        });
                        //Country Change.
                        $('#select2-country-container').change(function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            checkShowindv();
                        });
                    }
                }

                /**
                 * Update Account Limits on Database by Subscription Level (used in backend admin page)
                 */
                var dbUpload = function(event) {
                    event.stopPropagation();
                    var file = event.target.files,
                        level = event.target.dataset.level,
                        value = event.target.value,
                        form_data = new FormData();

                    form_data.append('action', 'elemental_onboardadmin_ajax');
                    form_data.append('action_taken', 'update_db');
                    form_data.append('level', level);
                    form_data.append('value', value);
                    form_data.append('security', elemental_onboardadmin_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_onboardadmin_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);
                            console.log(state_response.feedback);
                            $('#confirmation_' + level).html('Saved');

                        },
                        error: function(response) {
                            console.log('Error Uploading');
                        }
                    });
                }

                /**
                 * Check email exists (used in main add new user form)
                 */
                var chkEmail = function(event) {
                    event.stopPropagation();

                    $('#elemental-email-status').removeClass('elemental-checking');
                    $('#elemental-email-status').addClass('elemental-invalid');
                    var email = event.target.value,
                        valid_email = validateEmail(email);

                    if (!valid_email) {
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
                    form_data.append('action', 'elemental_onboardadmin_ajax');
                    form_data.append('action_taken', 'check_email');
                    form_data.append('email', email);
                    form_data.append('security', elemental_onboardadmin_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_onboardadmin_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);
                            console.log(state_response.available);
                            if (state_response.available === false) {
                                $('#elemental-email-status').removeClass('elemental-checking');
                                $('#elemental-email-status').removeClass('elemental-invalid');
                                $('#elemental-email-status').addClass('elemental-email-taken');
                                $('#elemental-email-status').html('Email Taken');
                            } else {
                                $('#elemental-email-status').removeClass('elemental-checking');
                                $('#elemental-email-status').removeClass('elemental-invalid');
                                $('#elemental-email-status').addClass('elemental-email-available');
                                $('#elemental-email-status').html('Email Available');
                                $('#elemental-email-status').attr('data-status', 'checked');
                                checkShowOrg();
                            }

                        },
                        error: function(response) {
                            console.log('Error Uploading');
                        }
                    });
                }

                /**
                 * Create New User post checks (used in main add new user form)
                 */
                var createOrganisation = function(event) {
                    event.stopPropagation();
                    $('#elemental-email-status').removeClass('elemental-checking');
                    $('#elemental-email-status').removeClass('elemental-invalid');
                    $('#elemental-email-status').addClass('elemental-email-available');
                    $('#elemental-email-status').html('Creating Account');
                    var membership = $('#pageinfo').attr('data-membership');
                    console.log(membership);

                    var email = $('#elemental-inbound-email').val(),
                        first_name = $('#first_name').val(),
                        step_window = $('#elemental-adduser-target'),
                        form_data = new FormData();

                    form_data.append('action', 'elemental_onboardadmin_ajax');
                    form_data.append('action_taken', 'create_user');
                    form_data.append('email', email);
                    form_data.append('first_name', first_name);
                    form_data.append('membership', membership);
                    form_data.append('security', elemental_onboardadmin_ajax.security);

                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_onboardadmin_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);
                            console.log(state_response.feedback);
                            if (state_response.feedback == true) {
                                if (state_response.table) {
                                    mainvideo_parent = step_window.parent().attr('id');
                                    parent_element = $('#' + mainvideo_parent);
                                    step_window.remove();
                                    step_window.parent().empty();
                                    parent_element.html(state_response.table);
                                }

                                $('#elemental-email-status').removeClass('elemental-checking');
                                $('#elemental-email-status').removeClass('elemental-invalid');
                                $('#elemental-email-status').removeClass('elemental-email-taken');
                                $('#elemental-email-status').addClass('elemental-email-available');
                                $('#elemental-email-status').html('Account Created');
                                $('#submit').prop('value', 'Account Created');
                                $('#submit').prop('disabled', true);
                                $('#first_name').prop('value', '');
                                $('#elemental-inbound-email').prop('value', '');
                                $('#elemental-email-status').attr('data-status', '');
                                $('#first-name-icon').hide();
                                $('#last-name-icon').hide();
                                window.wcfm_ajax_init();
                            }
                        },
                        error: function(response) {
                            console.log('Error Create Organisation');
                        }
                    });
                }

                /**
                 * Delete User (used in main form)
                 */
                var deleteUser = function(event, user_id, nonce, final) {
                        event.stopPropagation();
                        step_window = $('#elemental-remaining-counter');
                        var form_data = new FormData(),
                            notification = $('#elemental-notification-frame'),
                            account_window = $('#elemental-membership-table'),
                            step_window = $('#elemental-remaining-counter');

                        form_data.append('action', 'elemental_onboardadmin_ajax');
                        if (final) {
                            form_data.append('action_taken', 'delete_final');
                        } else {
                            form_data.append('action_taken', 'delete_user');
                        }
                        form_data.append('userid', user_id);
                        form_data.append('nonce', nonce);
                        form_data.append('security', elemental_onboardadmin_ajax.security);
                        $.ajax({
                            type: 'post',
                            dataType: 'html',
                            url: elemental_onboardadmin_ajax.ajax_url,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(response) {
                                var state_response = JSON.parse(response);

                                if (state_response.confirmation) {
                                    notification.html(state_response.confirmation);
                                    $('#elemental-membership-table').hide();
                                    init();
                                }
                                if (state_response.feedback) {
                                    console.log(state_response.feedback);
                                }

                                if (state_response.table) {
                                    account_window.html(state_response.table);
                                }
                                if (state_response.counter) {
                                    mainvideo_parent = step_window.parent().attr('id');
                                    parent_element = $('#' + mainvideo_parent);
                                    step_window.remove();
                                    step_window.parent().empty();
                                    parent_element.html(state_response.counter);
                                    $('#mvr-main-button-cancel').click();
                                    init();
                                }

                            },
                            error: function(response) {
                                console.log('Error Uploading');
                            }
                        });
                    }
                    /**
                     * Check if Name and Email conditions are met in main form
                     */
                function checkShowindv(status) {
                    var first_name = $('#first_name').val().length,
                        last_name = $('#last_name').val().length,
                        status = $('#elemental-email-status').data('status'),
                        country = $('#select2-country-container').attr('title'),
                        first_name_approved = false,
                        last_name_approved = false,
                        country_check = false;
                    // First Name Checks.
                    if (first_name >= 3) {
                        $('#first-name-icon').fadeIn(1000);
                        first_name_approved = true;
                    } else {
                        $('#first-name-icon').fadeOut(1000);
                        first_name_approved = false;
                    }
                    // Last Name Checks.
                    if (last_name >= 3) {
                        $('#last-name-icon').fadeIn(1000);
                        last_name_approved = true;
                    } else {
                        $('#last-name-icon').fadeOut(1000);
                        last_name_approved = false;
                    }
                    //Country Check.
                    if (country === '-Select a location-') {
                        country_check = false;
                    } else {
                        country_check = true;
                    }

                    if (status === 'checked' && first_name_approved === true && last_name_approved === true && country_check === true) {
                        $('#submit').fadeIn(1500);
                        $('#submit').prop('disabled', false);
                    } else {
                        $('#submit').fadeOut(1000);
                        $('#submit').prop('disabled', true);
                        return false;
                    }
                }
                /**
                 * Check if Name and Email conditions are met in main form
                 */
                function checkShowOrg(status) {
                    var first_name = $('#first_name').val().length,
                        status = $('#elemental-email-status').data('status');

                    if (first_name >= 6) {
                        $('#first-name-icon').show();
                    } else {
                        $('#first-name-icon').hide();
                    }

                    if (status === 'checked' && first_name >= 6) {
                        $('#submit').show();
                        $('#submit').prop('disabled', false);
                    } else {
                        return false;
                    }
                }

                /**
                 * Validate email format JS (pre check)
                 */
                function validateEmail(email) {
                    var re = /\S+@\S+\.\S+/;
                    return re.test(email);
                }
                init();
            }
        );
    }
);