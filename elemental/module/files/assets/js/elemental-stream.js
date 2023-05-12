window.addEventListener("load", function() {

    function init() {
        jQuery(function($) {

            $( '.elemental-delete-file' ).click(
                function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    var usrcheck = $( this ).attr( 'data-usrcheck' ),
                    file_check = $( this ).attr( 'data-filecheck' ),
                        nonce   = $( this ).attr( 'data-nonce' );
                    deleteFile( e, usrcheck, file_check, nonce );
                }
            );

            $( '.elemental-main-button-enabled' ).click(
                function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    user_id = $( '#elemental-welcome-page' ).attr( 'data-checksum' );
                    nonce   = $( this ).attr( 'data-auth-nonce' );
                    input   = $( this ).attr( 'data-input-type' );
                    deleteFile( e, user_id, input, nonce, true );
                }
            );

            $( '#elemental-main-button-cancel' ).click(
                function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    $( '#elemental-membership-table' ).show();
                    $( '#elemental-top-notification' ).empty();
                }
            );

            $( '.elemental-close-window' ).click(
                function(e) {
                    window.location.reload();
                }
            );
            

            
            /* Initialise Camera, and Listen to Buttons */
            $('#vid-picture').click(function(e) {
                document.getElementById("vid-picture").classList.add('elemental-hide');
                document.getElementById("vid-up").classList.add('elemental-hide');
                document.getElementById("elemental-picturedescription").classList.remove('elemental-hide');
                document.getElementById("elemental-text-description-current").classList.remove('elemental-hide');
                startcamera();
            });

            $('.elemental-button-login').click(function(e) {
                e.preventDefault();
                document.getElementById("elemental-picture").classList.add('elemental-hide');
                if (loginActive.length > 0) {
                    document.getElementById("elemental-login-form").classList.remove('elemental-hide');
                }
                document.getElementById("elemental-checksound").classList.add('elemental-hide');
                document.getElementById("elemental-meeting-name").classList.add('elemental-hide');
                $('#elemental-login-form').slideToggle();
            });

            $('.elemental-photo-image').click(function(e) {
                e.preventDefault();
                document.getElementById("elemental-picture").classList.remove('elemental-hide');
                document.getElementById("vid-picture").classList.remove('elemental-hide');


                $('#elemental-picture').slideToggle();
            });

            $('.elemental-name-user').click(function(e) {
                e.preventDefault();
                skipwindow();
                $('#elemental-meeting-name').slideToggle();

            });

            $('.elemental-check-sound').click(function(e) {
                e.preventDefault();
                document.getElementById("elemental-picture").classList.add('elemental-hide');
                if (loginActive.length > 0) {
                    document.getElementById("elemental-login-form").classList.add('elemental-hide');
                }
                document.getElementById("elemental-meeting-name").classList.add('elemental-hide');

                $('#elemental-checksound').slideToggle();

                document.getElementById("elemental-checksound").classList.remove('elemental-hide');
            });


            $('#chk-sound').click(function(e) {
                e.preventDefault();
                document.getElementById("elemental-checksound").classList.remove('elemental-center');
                checksound();
            });

            $('#stop-chk-sound').click(function(e) {
                window.location.reload();
            });
            $('#room-name-update').click(function(e) {
                e.preventDefault();
                updateName();
            });
            $('#vid-name').keydown(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    updateName();
                }
            });

            $('.elemental-forget-me').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                deleteMe();
            });

            $('.elemental-clipboard-copy').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                textToClipboard = $(this).parent().attr('data-id');
                navigator.clipboard.writeText(textToClipboard);
                alert("Copied: " + textToClipboard + ' to clipboard.');
            });


            if ($('#elemental-welcome-setup').length) {
                var existsvalue = document.getElementById('elemental-welcome-setup').innerHTML;
                if (existsvalue.length > 1) {
                    $('.elemental-name-user').click();
                    $('.elemental-app').hide();
                    $('.elemental-forget-me').hide();
                    $('#elemental-above-article-notification').html('<br><strong>The Video Room will be available when you complete your welcome</strong>');
                }
            }
            $('#elemental-picture-input').on('change', imageUpload);
            $('#elemental-file-input').on('change', fileUpload);
            
        });


    }

    function imageUpload(event) {
        event.stopPropagation();
        jQuery(function($) {
            document.getElementById("upload-picture").classList.remove('elemental-hide');
            var file = event.target.files;
            var form_data = new FormData();
            $.each(file, function(key, value) {
                form_data.append("upimage", value);
            });

            form_data.append('action', 'elemental_base_ajax');

            var room_name = $('#roominfo').data('roomName');
            form_data.append('room_name', room_name);
            form_data.append('action_taken', 'update_picture');
            form_data.append('security', elemental_base_ajax.security);
            $.ajax({
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log(state_response.message);
                    if (state_response.errormessage) {
                        console.log(state_response.errormessage);
                    }
                    let notify = document.getElementById("elemental-top-notification");
                    if (typeof notify !== null) {
                        notify.innerHTML += '<br><h3>' + state_response.message + '</h3><br>';
                    }
                    $('#vid-up').prop('value', 'Saved !');
                },
                error: function(response) {
                    console.log('Error Uploading');
                }
            });

            refreshWelcome();
        });

    }

    function fileUpload(event) {
        event.stopPropagation();
        jQuery(function($) {
            var file = event.target.files;
            var form_data = new FormData(),
            checksum = $('#elemental-welcome-page').data('checksum');
            $.each(file, function(key, value) {
                form_data.append("upfile", value);
            });
            form_data.append('checksum', checksum );
            form_data.append('action', 'elemental_base_ajax');
            form_data.append('action_taken', 'upload_file');
            form_data.append('security', elemental_base_ajax.security);
            $.ajax({
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    console.log(state_response.message);
                    if (state_response.errormessage) {
                        console.log(state_response.errormessage);
                    }
                    let notify = document.getElementById("elemental-top-notification");
                    if (typeof notify !== null) {
                        notify.innerHTML += '<br><h3>' + state_response.feedback + '</h3><br>';
                    }
                    if (state_response.table) {
                        $( '#elemental-membership-table' ).html(state_response.table);
                    }
                    init();
                  },
                error: function() {
                    console.log('Error Uploading');
                }
            });

           // refreshWelcome();
        });

    }

    /**
     * Delete User (used in main form)
     */
    var deleteFile = function(event, user_id, filecheck, nonce, final) {
    jQuery(function($) {
        event.stopPropagation();
        var form_data      = new FormData();
        var notification   = $('#elemental-top-notification' ),
            type           = $( '#user-add-form' ).attr('data-type');

            form_data.append( 'action', 'elemental_base_ajax' );
        if (final) {
            form_data.append( 'action_taken', 'delete_file_final' );
        } else {
            form_data.append( 'action_taken', 'delete_file' );
        }
        
        form_data.append( 'userid', user_id );
        form_data.append( 'filecheck', filecheck );
        form_data.append( 'nonce', nonce );
        form_data.append( 'type', type );
        form_data.append( 'security', elemental_base_ajax.security );
        $.ajax(
            {
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse( response );

                    if (state_response.confirmation) {
                        notification.html( state_response.confirmation );
                    }
                    if (state_response.feedback) {
                        console.log( state_response.feedback );
                    }

                    if (state_response.table) {
                        $( '#elemental-membership-table' ).html(state_response.table);
                    }
                    init();
                },
                error: function(response) {
                    console.log( 'Error in server' );
                }
            }
        );
    });
    }

    function skipwindow() {
        document.getElementById("elemental-picture").classList.add('elemental-hide');
        document.getElementById("elemental-meeting-name").classList.remove('elemental-hide');
        document.getElementById("elemental-checksound").classList.add('elemental-hide');
        document.getElementById("elemental-meeting-name").classList.add('elemental-center');
    }


    function startcamera() {

        document.getElementById("vid-take").classList.remove('elemental-hide');
        navigator.mediaDevices.getUserMedia({

            video: {
                width: { min: 213, ideal: 300, max: 1080 },
                height: { min: 213, ideal: 300, max: 1080 }
            }
        })


        .then(function(stream) {

            var video = document.getElementById("vid-live");
            video.srcObject = stream;
            video.play();

            document.getElementById("vid-take").onclick = vidtake;
            document.getElementById("vid-up").onclick = vidup;
            document.getElementById("vid-retake").onclick = retakevideo;

        })

        // Handle Error.
        .catch(function(err) {
            alert(err + " Please enable access and attach a webcam");
        });
        setTimeout(function() {
            cameratimeout();
        }, 30000);
    }

    function vidtake() {
        /* Create Canvas */
        var video = document.getElementById("vid-live"),
            canvas = document.createElement("canvas"),
            context2D = canvas.getContext("2d");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context2D.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
        var wrap = document.getElementById("vid-result");
        wrap.innerHTML = "";
        wrap.appendChild(canvas);

        /* Arrange Buttons for Retake, or Accept Image */
        jQuery(function($) {
            $('#vid-up').prop('value', 'Use This');
        });
        document.getElementById("vid-result").classList.remove('elemental-hide');
        document.getElementById("vid-retake").classList.remove('elemental-hide');
        document.getElementById("vid-up").classList.remove('elemental-hide');
        document.getElementById("vid-live").classList.add('elemental-hide');
        document.getElementById("vid-take").classList.add('elemental-hide');

        stopcamera();
    }

    function stopcamera() {
        navigator.mediaDevices.getUserMedia({
                // Resolution
                video: {
                    width: { min: 213, ideal: 300, max: 1080 },
                    height: { min: 213, ideal: 300, max: 1080 }
                }
            })
            .then(function(stream) {

                var video = document.getElementById("vid-live");
                video.srcObject = stream;

                stream.getTracks().forEach(track => track.stop())
            })

    }

    function cameratimeout() {
        navigator.mediaDevices.getUserMedia({
                // Resolution
                video: {
                    width: { min: 213, ideal: 300, max: 1080 },
                    height: { min: 213, ideal: 300, max: 1080 }
                }
            })
            .then(function(stream) {

                var video = document.getElementById("vid-live");
                video.srcObject = stream;

                stream.getTracks().forEach(track => track.stop())
            })

        console.log('Stopcamera Command Sent');
        document.getElementById("vid-retake").classList.remove('elemental-hide');
        document.getElementById("vid-take").classList.add('elemental-hide');
        document.getElementById("vid-live").classList.add('elemental-hide');
    }

    function retakevideo() {
        document.getElementById("elemental-picturedescription").classList.remove('elemental-hide');
        stopcamera();
        /* Reset Buttons for Retake */
        document.getElementById("vid-result").innerHTML = "";
        document.getElementById("vid-live").classList.remove('elemental-hide');
        document.getElementById("vid-result").classList.add('elemental-hide');
        document.getElementById("vid-up").classList.add('elemental-hide');
        document.getElementById("vid-take").classList.remove('elemental-hide');
        document.getElementById("vid-retake").classList.add('elemental-hide');
        document.getElementById("vid-up").value = "Use This";

        startcamera();

    }


    function vidup() {

        canvas = document.querySelector('canvas');
        context2D = canvas.getContext("2d");
        canvas.toBlob(function(blob) {

            // Prepare Form.
            var form_data = new FormData();
            form_data.append('upimage', blob);
            form_data.append('action', 'elemental_base_ajax');

            jQuery(function($) {
                var room_name = $('#roominfo').data('roomName');
                form_data.append('room_name', room_name);
                form_data.append('action_taken', 'update_picture');
                form_data.append('security', elemental_base_ajax.security);
                $.ajax({
                    type: 'post',
                    dataType: 'html',
                    url: elemental_base_ajax.ajax_url,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function(response) {
                        var state_response = JSON.parse(response);
                        if (state_response.errormessage) {
                            console.log(state_response.errormessage);
                        }
                        console.log(state_response.message);
                        document.getElementById("elemental-top-notification").innerHTML += '<br>';
                        $('#vid-up').prop('value', 'Saved !');
                        setTimeout(() => { window.location.reload(); }, 1500);
                    },
                    error: function(response) {
                        console.log('Error Uploading');
                    }
                });
            });

        });
        refreshWelcome();
    }

    function updateName() {
        var textvalue = document.getElementById('vid-name').value;

        if (textvalue.length < 1) {
            alert('You can not enter a blank Display Name');
            return false;
        }

        // Prepare Form.
        var form_data = new FormData();
        form_data.append('action', 'elemental_base_ajax');

        jQuery(function($) {
            console.log('Start Name Update');
            notification = $('#elemental-above-article-notification');
            var room_name = $('#roominfo').data('roomName'),
                status_message = $('#elemental-postbutton-notification'),
                display_name = $('#vid-name').val();
            form_data.append('room_name', room_name);
            form_data.append('display_name', display_name);

            form_data.append('action_taken', 'update_display_name');
            form_data.append('security', elemental_base_ajax.security);
            $.ajax({
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    notification.empty();
                    if (state_response.feedback) {
                        status_message.html(state_response.feedback);
                        setTimeout(function() {
                            status_message.fadeOut();
                        }, 6000);
                        setTimeout(function() {
                            status_message.empty();
                            $(status_message).removeAttr('style');
                        }, 8000);
                    }
                    refreshWelcome();
                    if (state_response.errormessage) {
                        console.log(state_response.errormessage);
                    }
                    $('.elemental-forget-me').show();;
                },
                error: function(response) {
                    console.log('Error Uploading');
                }
            });
        });

    }

    function deleteMe() {

        // Prepare Form.
        var form_data = new FormData();
        form_data.append('action', 'elemental_base_ajax');

        jQuery(function($) {
            console.log('Picture Delete');
            var room_name = $('#roominfo').data('roomName'),
            checksum = $('#elemental-welcome-page').data('checksum'),
            display_name = $('#vid-name').val();
            form_data.append('room_name', room_name);
            form_data.append('display_name', display_name);
            form_data.append('action_taken', 'delete_me');
            form_data.append('checksum', checksum );
            form_data.append('security', elemental_base_ajax.security);
            $.ajax({
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var state_response = JSON.parse(response);
                    if (state_response.errormessage) {
                        console.log(state_response.errormessage);
                    }
                    $('.elemental-forget-me').hide();
                    setTimeout(() => { window.location.reload(); }, 1500);
                },
                error: function(response) {
                    console.log('Error Deleting');
                }
            });
        });

        document.getElementById("elemental-top-notification").innerHTML += '<br><div><strong>Your Records have been deleted</strong></div>';

    }


    function refreshWelcome() {
        setTimeout(() => { window.location.reload(); }, 500);

    }

    function checksound() {
        console.log('Check sound starting');
        document.getElementById("stop-chk-sound").classList.remove('elemental-hide');
        // Prepare Form.
        var form_data = new FormData();
        form_data.append('action', 'elemental_base_ajax');

        jQuery(function($) {
            container = $('.elemental-app');
            notification = $('#elemental-above-article-notification');
            form_data.append('security', elemental_base_ajax.security);
            form_data.append('action_taken', 'check_sound');
            $.ajax({
                type: 'post',
                dataType: 'html',
                url: elemental_base_ajax.ajax_url,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {

                    var state_response = JSON.parse(response);
                    if (state_response.errormessage) {
                        console.log(state_response.errormessage);
                    }
                    if (state_response.mainvideo) {
                        refreshTarget(container, state_response.mainvideo);
                    }
                    if (state_response.message) {
                        notification.html(state_response.message);
                    }
                    init();

                    if (window.elemental_tabbed_init) {
                        window.elemental_tabbed_init(container);
                    }

                    if (window.elemental_app_init) {
                        window.elemental_app_init(container[0]);
                    }

                    if (window.elemental_app_load) {
                        window.elemental_app_load();
                    }

                    if (window.elemental_shoppingbasket_init) {
                        window.elemental_shoppingbasket_init();
                    }

                    $('#vid-up').prop('value', 'Saved !');
                },
                error: function(response) {
                    console.log('Error Uploading');
                }
            });
        });
    }

    let vidnamecheck = document.getElementById("vid-name");
    if (vidnamecheck) {
        document.getElementById("vid-name").onkeyup = function() {
            document.getElementById("vid-name").innerHTML = '';
            document.getElementById("vid-down").disabled = false;
        };
    }


    init();

    window.elemental_stream_init = init;
});

function refreshTarget(source_element, ajax_response, video_skip) {
    // Hard Delete of Content in Parent Container to Avoid Duplication in replacement.
    if (source_element.length === 0) {
        console.log('Source Element Empty- Exiting');
        return false
    }
    mainvideo_parent = source_element.parent().attr('id');
    source_element.remove();
    source_element.parent().empty();
    let item = document.getElementById(mainvideo_parent);
    if (item && Object.keys(item).length >= 1) {
        item.innerHTML = ajax_response;
    } else {
        console.log('empty parent');
    }

    if (!video_skip) {
        reloadVideo();
    }
}

function reloadVideo() {
    jQuery(function($) {
        // WordPress may add custom headers to the request, this is likely to trigger CORS issues, so we remove them.
        if ($.ajaxSettings && $.ajaxSettings.headers) {
            delete $.ajaxSettings.headers;
        }

        $.ajax({
            url: myVideoRoomAppEndpoint + '/asset-manifest.json',
            dataType: 'json'
        }).then(
            function(data) {
                Object.values(data.files).map(
                    function(file) {
                        var url = myVideoRoomAppEndpoint + '/' + file;

                        if (file.endsWith('.js')) {
                            $.ajax({
                                beforeSend: function() {},
                                url: url,
                                dataType: 'script'
                            });
                        } else if (file.endsWith('.css')) {
                            $('<link rel="stylesheet" type="text/css" />')
                                .attr('href', url)
                                .appendTo('head');
                        }
                    }
                );
            }
        );
        $('#elemental-video').click();
    });
    
}