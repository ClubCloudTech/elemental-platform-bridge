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
        function init() {

            $("#update_user1").on("click", edit_userForm);


        }

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
                elemental_edituser_ajax.ajax_url +
                " update user : " +
                JSON.stringify(event.target)
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

        };;

        init();
    });
});