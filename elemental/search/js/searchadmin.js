/**
 * Ajax control and Rendering JS for Search.
 *
 * @package elemental/search/js/searchadmin.js
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


                    $('.elemental-membership-control').on('change', dbUpload);
                    $('.elemental-membership-template').on('change', templateUpload);
                    $('#elemental-inbound-email').on('keyup', chkEmail);
                    $('#first_name').on('keyup', checkShow);
                    $('#last_name').on('keyup', checkShow);



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

                    $('#last_name').click(
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


                /**
                 * Search Stores and Spaces.
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
                    form_data.append('action', 'elemental_searchadmin_ajax');
                    form_data.append('action_taken', 'check_email');
                    form_data.append('email', email);
                    form_data.append('security', elemental_searchadmin_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin_ajax.ajax_url,
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
                                checkShow();
                            }

                        },
                        error: function(response) {
                            console.log('Error Uploading');
                        }
                    });
                }

                init();
            }
        );
    }
);