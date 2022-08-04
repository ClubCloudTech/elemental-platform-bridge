/**
 * Ajax and Login handle Control.
 *
 * @package module/membership/js/loginhandler.js
 */
window.addEventListener("load", function() {
    jQuery(function($) {
        /**
         * Initialise Functions on Load
         */
        function init() {;
            $("#admin_login").on("click", admin_login);
            $("#update_user1").on("click", edit_userForm);
            $("#mailSent").on("click", mailSent);
            $("#pass_email").on('input', checkEmail);
        }

        var checkEmail = function(event) {
            console.log("email  : " + $(this).val());
            var form_data = new FormData();
            form_data.append("action", "elemental_edituser_ajax");
            form_data.append("action_taken", "check_email");
            // event.stopPropagation();
            // var formDataArray = $("#registrationForm").serializeArray();
            var formDataArray = $("#forgotEmailForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                console.log(input.name, input.value);
                form_data.append(input.name, input.value);
            });

            console.log(
                elemental_edituser_ajax.ajax_url +
                " update user : " +
                JSON.stringify(event.target)
            );
            form_data.append(
                "security",
                elemental_edituser_ajax.security
            );
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_edituser_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log(state_response);
                    if (state_response.feedback == "Email NotFound") {
                        //alert(state_response.feedback);
                        $(".signCheck").removeClass("fa-envelope");
                        $(".signCheck").addClass("fa-exclamation-triangle");
                        $(".signCheck").css("color", "#dc143c");
                    } else {
                        $(".signCheck").removeClass("fa-exclamation-triangle");
                        $(".signCheck").addClass("fa-check");
                        $(".signCheck").css("color", "#09a81d");

                        //  $("#pass_email").append('<i class = "fa fa-check" style = "color:green;" aria-hidden = "true" ></i>');

                    }
                    // $("#confirmation_template_" + level).html(state_response.feedback);
                },
                error: function(response) {
                    console.log("Error Uploading Template");
                },
            });

        };
        /**
         * Update user details on Database by userID (used in backend admin page)
         */
        var edit_userForm = function(event) {

            var form_data = new FormData();
            form_data.append("action", "elemental_edituser_ajax");
            form_data.append("action_taken", "update_edituser");
            // event.stopPropagation();
            // var formDataArray = $("#registrationForm").serializeArray();
            var formDataArray = $("#registrationForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                console.log(input.name, input.value);
                form_data.append(input.name, input.value);
            });

            console.log(
                elemental_edituser_ajax.security +
                " update user : "
            );
            form_data.append("security", elemental_edituser_ajax.security);
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_edituser_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log(state_response);
                    if (state_response.feedback == "User Updated") {
                        alert(state_response.feedback);
                    }
                    // $("#confirmation_template_" + level).html(state_response.feedback);
                },
                error: function(response) {
                    console.log("Error Uploading Template");
                },
            });

        };

        var mailSent = function(event) {
            console.log("mail sent click ");
            var form_data = new FormData();
            form_data.append("action", "elemental_edituser_ajax");
            form_data.append("action_taken", "reset_mail");
            // event.stopPropagation();

            // var formDataArray = $("#registrationForm").serializeArray();
            var formDataArray = $("#forgotEmailForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                console.log(input.name, input.value);
                form_data.append(input.name, input.value);
            });

            console.log(
                elemental_edituser_ajax.ajax_url +
                " update user : " +
                JSON.stringify(event.target)
            );
            form_data.append(
                "security",
                elemental_edituser_ajax.security
            );
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_edituser_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log("smtp", state_response);
                    if (state_response.feedback == "User Updated") {
                        alert(state_response.feedback);
                    }
                    // $("#confirmation_template_" + level).html(state_response.feedback);
                },
                error: function(response) {
                    console.log("Error Uploading Template");
                },
            });
        };

        var edit_userForm = function(event) {
            var form_data = new FormData();
            form_data.append("action", "elemental_edituser_ajax");
            form_data.append("action_taken", "update_edituser");
            // event.stopPropagation();
            // var formDataArray = $("#registrationForm").serializeArray();
            var formDataArray = $("#registrationForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                console.log(input.name, input.value);
                form_data.append(input.name, input.value);
            });

            console.log(elemental_edituser_ajax.security + " update user : ");
            form_data.append("security", elemental_edituser_ajax.security);
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_edituser_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log(state_response);
                    if (state_response.feedback == "User Updated") {
                        alert(state_response.feedback);
                    }
                    // $("#confirmation_template_" + level).html(state_response.feedback);
                },
                error: function(response) {
                    console.log("Error Uploading Template");
                },
            });
        };

        var admin_login = function(event) {
            console.log("login admin click ");
            var form_data = new FormData();
            form_data.append("action", "elemental_edituser_ajax");
            form_data.append("action_taken", "admin_login");
            // event.stopPropagation();

            // var formDataArray = $("#registrationForm").serializeArray();
            var formDataArray = $("#adminLoginForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                console.log(input.name, input.value);
                form_data.append(input.name, input.value);
            });

            form_data.append("security", elemental_edituser_ajax.security);
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_edituser_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    if (state_response.feedback == "User LoggedIn") {
                        alert(state_response.feedback);
                    }
                    // $("#confirmation_template_" + level).html(state_response.feedback);
                },
                error: function(response) {
                    console.log("Error Uploading Template");
                },
            });
        };

        init();
    });
});