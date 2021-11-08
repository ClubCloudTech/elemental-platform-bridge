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
                    var notification = $('#searchnotification');

                    // Search Bar Triggers (click and enter key)
                    $('.elemental-search-trigger').click(
                        function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            notification.html('Searching');
                            var input = $('#elemental-search').val();
                            checkorgs(input);
                        }
                    );

                    $("#elemental-search").on('keyup', function(event) {
                        if (event.keyCode === 13) {
                            var input = $('#elemental-search').val();
                            notification.html('Searching');
                            checkorgs(input);
                        }
                    });
                    // Listen to WCFM Ajax box being used.
                    $('#search').keyup(
                        function(e) {
                            var premium_div = $('#elemental-premium-wcfm');
                            e.stopPropagation();
                            e.preventDefault();
                            notification.html('Searching');
                            if (premium_div.length) {
                                premium_div.empty();
                            }

                        }
                    );
                }

                /**
                 * Search Organisations and Spaces.
                 */
                var checkorgs = function(search) {

                    var form_data = new FormData();
                    $('.elemental-label-trigger').each(function() {
                        //console.log(": " + $(this).attr("id"));
                        form_data.append($(this).attr("id"), $(this).attr("id"));
                    });

                    form_data.append('action', 'elemental_searchadmin_ajax');
                    //form_data.append('action_taken', 'search_org');
                    form_data.append('search_term', search);
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

                            if (state_response.organisation && state_response.target) {
                                $('#' + state_response.target).html(state_response.organisation);
                                let orgcount = $('.woocommerce-result-count').text();
                                //console.log(orgcount + 'orgcount');
                                let number = orgcount.match(/\d+/g).pop();
                                $('#elemental-org-result').html('Organisations (' + number + ')');
                            }
                            if (state_response.product && state_response.producttarget) {
                                $('#' + state_response.producttarget).html(state_response.product);

                                let destination = $('#elemental-product-grid').children('li').eq(0),
                                    source = $('#elemental-product-grid').children('li').eq(1);

                                //Rewrite First Product Class due to OWP bug.
                                $(destination).attr("class", $(source).attr("class"));
                                if (state_response.count) {
                                    $('#elemental-product-result').html('Products (' + state_response.count + ')');
                                }
                            }

                        },
                        error: function(response) {
                            console.log('Error Search Organisations');
                        }
                    });
                }

                init();
            }
        );
    }
);