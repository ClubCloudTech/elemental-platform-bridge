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
            $("#update_user1").on("click", edit_companyForm);
              $("#addUser").on("click", edit_companyForm);
    
        }


        /**
         * Update company details on Database by companyID (used in backend admin page)
         */
        var edit_companyForm = function(event) {

            var form_data = new FormData();
            form_data.append("action", "elemental_editcompany_ajax");
            form_data.append("action_taken", "update_editcompany");
            var formDataArray = $("#companyForm").serializeArray();
            $.each(formDataArray, function(key, input) {
                form_data.append(input.name, input.value);
            });
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
                    console.log(state_response);
                    if (state_response.feedback == "company Updated") {
                        alert(state_response.feedback);
                    }
                },
                error: function(response) {
                    console.log("Error Uploading Template "+JSON.stringify(response));
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
            $.each(formDataArray, function(key, input) {
                form_data.append(input.name, input.value);
            });
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
                    console.log(state_response);
                    if (state_response.feedback == "User Created") {
                        alert(state_response.feedback);
                    }else{
                         alert(state_response.feedback);
                    }

                },
                error: function(response) {
                    console.log("Error Uploading Template "+JSON.stringify(response));
                },
            });

        };
      
        init();
    });
       });


