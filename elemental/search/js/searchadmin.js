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

                    $("#elemental-search").on(
                        'keyup',
                        function(event) {
                            if (event.keyCode === 13) {
                                var input = $('#elemental-search').val();
                                notification.html('Searching');
                                checkorgs(input);
                            }
                        }
                    );
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

                    // Search Pagination
                    $('.page-numbers').click(
                        function(e) {
                            console.log('click');
                            e.stopPropagation();
                            e.preventDefault();
                            notification.html('Getting Next Page');
                            var input = $('#elemental-search').val();
                            let page = $(this).attr('href');
                            let pagenumber = page.match(/\d+/g).pop();
                            searchonly(input, pagenumber);

                        }
                    );

                }

                /**
                 * Search Organisations and Spaces.
                 */
                var checkorgs = function(search) {
                    notification = $('#searchnotification');
                    var form_data = new FormData();
                    searchid = $('#elemental-pageinfo').data("searchid");

                    $('.elemental-label-trigger').each(
                        function() {
                            form_data.append($(this).attr("id"), $(this).attr("id"));
                        }
                    );

                    form_data.append('action', 'elemental_searchadmin_ajax');
                    // form_data.append('action_taken', 'search_org');
                    form_data.append('search_term', search);
                    form_data.append('searchid', searchid);
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

                            notification.html('Search Complete');
                            if (state_response.organisation && state_response.orgtarget) {
                                $('#' + state_response.orgtarget).html(state_response.organisation);
                                let orgcount = $('.woocommerce-result-count').text();
                                let number = orgcount.match(/\d+/g).pop();
                                $('#elemental-org-result').html('Organisations (' + number + ')');
                            }
                            if (state_response.product && state_response.producttarget) {
                                $('#' + state_response.producttarget).html(state_response.product);

                                // Rewrite First Product Class due to OWP bug.
                                let destination = $('#elemental-product-grid').children('li').eq(0),
                                    source = $('#elemental-product-grid').children('li').eq(1);

                                $(destination).attr("class", $(source).attr("class"));
                                if (state_response.productcount) {
                                    $('#elemental-product-result').html('Products (' + state_response.productcount + ')');
                                } else {
                                    $('#elemental-product-result').html('Products (0)');
                                }
                            }
                            if (state_response.content && state_response.contenttarget) {
                                $('#' + state_response.contenttarget).html(state_response.content);

                                if (state_response.contentcount) {
                                    $('#elemental-search-result').html('Content (' + state_response.contentcount + ')');
                                }
                            }
                            init();

                        },
                        error: function(response) {

                            notification.html('Search Error');
                            console.log('Error in Search Content');
                        }
                    });
                }

                /**
                 * Search Organisations and Spaces.
                 */
                var searchonly = function(search, page) {
                    notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid");
                    console.log(searchid + 'test');
                    var form_data = new FormData();
                    form_data.append('searchid', searchid);
                    form_data.append('elemental-main-content', 'elemental-main-content');
                    form_data.append('page', page);
                    form_data.append('action', 'elemental_searchadmin_ajax');
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

                            if (state_response.content && state_response.contenttarget) {
                                $('#' + state_response.contenttarget).html(state_response.content);

                                if (state_response.contentcount) {
                                    $('#elemental-search-result').html('Content (' + state_response.contentcount + ')');
                                }
                            }
                            init();

                        },
                        error: function(response) {
                            console.log('Error in Search Content');
                        }
                    });
                }

                init();
            }
        );
    }
);