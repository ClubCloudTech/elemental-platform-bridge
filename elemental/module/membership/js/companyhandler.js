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
            $("#update_profile").on("click", edit_companyForm);
            $("#addUser").on("click", edit_companyForm);
            $("#mailSent").on("click", mailSent);
        }

        var checkEmail = function(event) {
            var re = /\S+@\S+\.\S+/;
            if (re.test($("#pass_email").val())) {
                //alert(state_response.feedback);
                $(".signCheck").removeClass("fa-exclamation-triangle");
                $(".signCheck").addClass("fa-check");
                $(".signCheck").css("color", "#09a81d");
                if ($("#pass_email").val() == "") {
                    $("#mailSent").prop("disabled", true);
                } else {
                    $("#mailSent").prop("disabled", false);
                }

            } else {

                $(".signCheck").removeClass("fa-envelope");
                $(".signCheck").addClass("fa-exclamation-triangle");
                $(".signCheck").css("color", "#dc143c");
            }


            return;

        };

        /**
         * Update company details on Database by companyID (used in backend admin page)
         */
        var edit_companyForm = function(event) {

            var form_data = new FormData();
            form_data.append("action", "elemental_editcompany_ajax");
            form_data.append("action_taken", "update_editcompany");
            var formDataArray = $("#companyForm").serializeArray();
            $.each(
                formDataArray,
                function(key, input) {
                    form_data.append(input.name, input.value);
                }
            );
            form_data.append("security", elemental_editcompany_ajax.security);
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_editcompany_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);

                    if (state_response.feedback == "company Updated") {
                        alert(state_response.feedback);
                    }
                },
                error: function(response) {
                    console.log("Error Uploading Template " + JSON.stringify(response));
                },
            });
        };

        /**
         * Add new user details on Database (used in backend admin page)
         */
        var add_user = function(event) {

            var form_data = new FormData();
            form_data.append("action", "elemental_editcompany_ajax");
            form_data.append("action_taken", "add_user");
            var formDataArray = $("#registrationForm").serializeArray();
            $.each(
                formDataArray,
                function(key, input) {
                    form_data.append(input.name, input.value);
                }
            );
            form_data.append("security", elemental_editcompany_ajax.security);
            $.ajax({
                type: "post",
                dataType: "html",
                url: elemental_editcompany_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    if (state_response.feedback == "User Created") {
                        alert(state_response.feedback);
                    } else {
                        alert(state_response.feedback);
                    }

                },
                error: function(response) {
                    console.log("Error Uploading Template " + JSON.stringify(response));
                },
            });

        };


        var mailSent = function(event) {
            var form_data = new FormData();
            form_data.append("action", "elemental_editcompany_ajax");
            form_data.append("action_taken", "forgot_password");
            // event.stopPropagation();
            var formDataArray = $("#forgotEmailForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                form_data.append(input.name, input.value);
            });
            form_data.append(
                "security",
                elemental_editcompany_ajax.security
            );
            grecaptcha.ready(function() {
                grecaptcha
                    .execute("6LcnlF0hAAAAAB2_PyHZ12mP_laQlIvO2AMzkU3I", {
                        action: "submit",
                    })
                    .then(function(token) {
                        var response = document.getElementById("token_response");
                        response.value = token;
                        form_data.append("token_response", response.value);
                        $.ajax({
                            type: "post",
                            dataType: "html",
                            url: elemental_editcompany_ajax.ajax_url,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            success: function(response) {
                                var state_response = JSON.parse(response);
                                if (state_response.feedback == "Email Sent") {
                                    alert("If User exits , Mail has been sent !");
                                    location.reload();
                                }
                            },
                            error: function(response) {
                                console.log("Error Uploading Template");
                            },
                        });

                    });
            });

        };

        init();
    });
});