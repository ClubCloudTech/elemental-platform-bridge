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
                function init(firstrun) {
					console.log('iizhere');
                    window.wcfm_script_init;
                    var notification = $('#searchnotification');
                    // Search Bar Triggers (click and enter key)
                    $('.elemental-search-trigger').click(
                        function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            notification.html('Searching');
                            var input = $('#elemental-search').val();
                            $('#elemental-refresh-search').fadeIn();
                            searchall(input);
                        }
                    );

                    $("#elemental-search").on(
                        'keyup',
                        function(event) {
                            if (event.keyCode === 13) {
                                var input = $('#elemental-search').val();
                                notification.html('Searching');

                                $('#elemental-refresh-search').fadeIn();
                                searchall(input);
                                event.stopPropagation();
                            }
                        }
                    );
                    // Listen to WCFM Ajax box being used.
                    $('#search').keyup(
                        function(e) {

                            e.stopPropagation();
                            e.preventDefault();
                            notification.html('Searching');
                            var premium_div = $('#elemental-premium-wcfm');
                            if (premium_div.length) {
                                premium_div.empty();
                            }
							$('#elemental-load-hide').show();
                        }
                    );
                    // Refresh Button.
                    $('#elemental-refresh-search').click(
                        function(e) {
                            e.preventDefault();
                            refreshall();
							$('#elemental-load-hide').hide();
                        }
                    );
					
					/**
					 * Groups Section
					 */
					//Group Refine Search.
					$('#elemental-group-search-submit').click(
						function(e) {
							e.preventDefault();
							e.stopPropagation();
							var notification = $('#searchnotification');
							notification.html('Refining Search');
							var input = $('#elemental-group-search').val();
							$('#elemental-refresh-search').fadeIn();
							grouponly(input);
							e.stopImmediatePropagation();
						}
					);
					//Click All Group Refresh.
					$('.elemental-refresh-group-search-trigger').click(
						function(e) {
							console.log('click handler');
							e.stopImmediatePropagation();
							e.stopPropagation();
							refreshall('elemental-group-tab');
							e.preventDefault();

						}
					);
					// On Click on Group tab to resize (trigger masonry)
					$('#elemental-group-result').click(
                        function(e) {
                            e.stopPropagation();
                            window.dispatchEvent(new Event('resize'));
                        }
                    );
					// Drop Down for Sort Order Members
                    if (firstrun) {
                        setTimeout(() => {
                            $("#groups-order-by").on(
                                'change',
                                function(e) {
                                    e.stopImmediatePropagation();
                                    e.preventDefault();
                                    e.stopPropagation();
                                    var notification = $('#searchnotification');
                                    notification.html('Searching');
                                    var input = $(this).val();
                                    $('#elemental-refresh-search').fadeIn();
                                    grouponly(null, null, input);
                                    e.stopImmediatePropagation();
                                }
                            );
                        }, 4000);
						$('#elemental-load-hide').hide();
                    } else {
                        $("#groups-order-by").on(
                            'change',
                            function(e) {
                                e.stopImmediatePropagation();
                                e.preventDefault();
                                e.stopPropagation();
                                var notification = $('#searchnotification');
                                notification.html('Searching');
                                var input = $(this).val();
                                $('#elemental-refresh-search').fadeIn();
                                grouponly(null, null, input);
                                e.stopImmediatePropagation();
                            }
                        );
                    }
					/**
					 * Members Section
					 */


                    //Members Refine Search Trigger.
                    $('#members_search_submit').click(
                        function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            var notification = $('#searchnotification');
                            notification.html('Searching');
                            var input = $('#members_search').val();
                            $('#elemental-refresh-search').fadeIn();
                            memberonly(input);
                            e.stopImmediatePropagation();
                        }
                    );

					//Refresh All Trigger Members.
					$('.elemental-refresh-member-search-trigger').click(
						function(e) {
							console.log('click handler');
							e.stopImmediatePropagation();
							e.stopPropagation();
							refreshall('elemental-member-tab');
							e.preventDefault();

						}
					);
                    // On Click on Member tab to resize (trigger masonry)
					$('#elemental-member-result').click(
                        function(e) {
                            e.stopPropagation();
                            window.dispatchEvent(new Event('resize'));
                        }
                    );
					// Drop Down for Sort Order  - Members
					if (firstrun) {
						setTimeout(() => {
							$("#members-order-by").on(
								'change',
								function(e) {
									e.stopImmediatePropagation();
									e.preventDefault();
									e.stopPropagation();
									var notification = $('#searchnotification');
									notification.html('Searching');
									var input = $(this).val();
									$('#elemental-refresh-search').fadeIn();
									memberonly(null, null, input);
									e.stopImmediatePropagation();
								}
							);
						}, 4000);
					} else {
						$("#members-order-by").on(
							'change',
							function(e) {
								e.stopImmediatePropagation();
								e.preventDefault();
								e.stopPropagation();
								var notification = $('#searchnotification');
								notification.html('Searching');
								var input = $(this).val();
								$('#elemental-refresh-search').fadeIn();
								memberonly(null, null, input);
								e.stopImmediatePropagation();
							}
						);
					}

                    // Search Pagination
                    $('.page-numbers').click(
                        function(e) {
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            e.preventDefault();
                            //notification.html('Getting Next Page');
                            var input = $('#elemental-search').val();
                            let page = $(this).attr('href');
                            let check = stringContainsNumber(page) ? "yes" : "no";
                            if (check === 'no') {
                                var pagenumber = 1;
                            } else {
                                var pagenumber = page.match(/\d+/g).pop();
                            }
                            let target = $(this).closest("ul").attr('data-target'),
                                wcfm_target = $(this).closest("ul").attr('class');


                            //Members Search Case.
                            if (page.includes('upage')) {
                                memberonly(input, pagenumber);
                            //Org Search Case.
                            } else if (wcfm_target === 'page-numbers') {
								console.log('org search case');
                                var premium_div = $('#elemental-premium-wcfm');
                                if (premium_div.length) {
                                    premium_div.empty();
                                }
                                if (pagenumber === 1) {
                                    pagenumber = null;
                                }
                                orgonly(input, pagenumber, e);

                                //Product Search Case.
                            } else if (target === 'products') {
                                productonly(input, pagenumber);
                                // News Search Case.
                            } else {
                                contentsearch(input, pagenumber);
                            }
                        }
                    );
                }

                function stringContainsNumber(_string) {
                    return /\d/.test(_string);
                }

                /**
                 * Search All Items.
                 */
                var searchall = function(search) {

                    var notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid"),
                        baseurl = $('#elemental-pageinfo').attr('data-pagination');
                    var form_data = new FormData();

                    $('.elemental-label-trigger').each(
                        function() {
                            form_data.append($(this).attr("id"), $(this).attr("id"));
                        }
                    );

                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    if (baseurl) {
                        form_data.append('base', baseurl);
                    }
                    form_data.append('search_term', search);
                    form_data.append('searchid', searchid),
                        productid = $('#elemental-pageinfo').data("productid");
                    form_data.append('productid', productid);
                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
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
                                    $('#elemental-search-result').html('News (' + state_response.contentcount + ')');
                                }
                            }
                            if (state_response.member && state_response.membertarget) {
                                $('#' + state_response.membertarget).html(state_response.member);
                                let membercount = $('#member-dir-count-bottom').text();
                                console.log(membercount);
								if (membercount > 1 ) {
									let number = membercount.match(/\d+/g).pop();

									if (number) {
										$('#elemental-member-result').html('Platform Users (' + number + ')');
									}
								}
                                
                            }

                            if (state_response.group && state_response.grouptarget) {
                                $('#' + state_response.grouptarget).html(state_response.group);
                                let groupcount = $('#group-dir-count-bottom').text();
								if (groupcount > 1 ) {
									let number = groupcount.match(/\d+/g).pop();

									if (number) {
										$('#elemental-group-result').html('Groups (' + number + ')');
									}
								}
                            }
							if (state_response.forum && state_response.forumtarget) {
                                $('#' + state_response.forumtarget).html(state_response.forum);
                                
								let forumcount = $('#bbpsearchcount').text();
								if (forumcount.length > 1 ) {
									let number = forumcount.match(/\d+/g).pop();

									if (number) {
										$('#elemental-forum-result').html('Expert Forums (' + number + ')');
									}
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
                 * Refresh All Tabs. (or a targetted single)
                 */
                var refreshall = function(target) {
                    console.log('refreshall');
                    notification = $('#searchnotification');
                    var form_data = new FormData();
                    var searchid = $('#elemental-pageinfo').data("searchid"),
                        baseurl = $('#elemental-pageinfo').attr('data-pagination'),
                        productid = $('#elemental-pageinfo').data("productid");
                    if (baseurl) {
                        form_data.append('base', baseurl);
                    }
                    if (target) {
                        form_data.append('refresh_tabs', target);
                    } else {
                        form_data.append('refresh_tabs', 'refresh_tabs');
                    }
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);
                            setTimeout(() => { notification.fadeOut(); }, 5000);
                            notification.html('Search Cleared');
                            $('#elemental-search').val('');
                            $('#elemental-refresh-search').fadeOut();

                            if (state_response.organisation && state_response.orgtarget) {
                                $('#' + state_response.orgtarget).html(state_response.organisation);
                                $('#elemental-org-result').html('Organisations');
                            }
                            if (state_response.product && state_response.producttarget) {
                                $('#' + state_response.producttarget).html(state_response.product);

                                // Rewrite First Product Class due to OWP bug.
                                let destination = $('#elemental-product-grid').children('li').eq(0),
                                    source = $('#elemental-product-grid').children('li').eq(1);

                                $(destination).attr("class", $(source).attr("class"));
                                $('#elemental-product-result').html('Products');

                            }
                            if (state_response.content && state_response.contenttarget) {
                                $('#' + state_response.contenttarget).html(state_response.content);
                                $('#elemental-search-result').html('News');
                            }
                            if (state_response.member && state_response.membertarget) {
                                $('#' + state_response.membertarget).html(state_response.member);
                                $('#elemental-member-result').html('Platform Users');
                            }
                            if (state_response.group && state_response.grouptarget) {
                                $('#' + state_response.grouptarget).html(state_response.group);
                                $('#elemental-group-result').html('Groups');

                            }
                            init();

                        },
                        error: function(response) {

                            notification.html('Refresh All Error');
                            console.log('Error in Refreshing All Content');
                        }
                    });
                }

                /**
                 * Search only Content. (used for pagination)
                 */
                var contentsearch = function(search, page) {
                    console.log('contentsearch');
                    notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid"),
                        productid = $('#elemental-pageinfo').data("productid");

                    var form_data = new FormData();
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('elemental-main-content', 'elemental-main-content');
                    form_data.append('page', page);
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('search_term', search);
                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);

                            if (state_response.content && state_response.contenttarget) {
                                $('#' + state_response.contenttarget).html(state_response.content);

                                if (state_response.contentcount) {
                                    $('#elemental-search-result').html('News (' + state_response.contentcount + ')');
                                }
                            }
                            init();
                        },
                        error: function(response) {
                            console.log('Error in Search Content');
                        }
                    });
                }

                /**
                 * Search Product Only.
                 */
                var productonly = function(search, page) {
                    console.log('productonly');
                    var notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid"),
                        productid = $('#elemental-pageinfo').data("productid");
                    var form_data = new FormData();
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('elemental-main-products', 'elemental-main-products');
                    form_data.append('page', page);
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('search_term', search);
                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);

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
                            init();
                        },
                        error: function(response) {
                            console.log('Error in Search Product');
                        }
                    });
                }

                /**
                 * Search Organisation Only.
                 */
                var orgonly = function(search, page, event) {
                    event.stopImmediatePropagation();
                    var notification = $('#searchnotification'),
                        baseurl = $('#elemental-pageinfo').attr('data-pagination');
                    searchid = $('#elemental-pageinfo').data("searchid"),
                        productid = $('#elemental-pageinfo').data("productid");
                    var form_data = new FormData();
                    if (page) {
                        form_data.append('page', page);
                    }
                    if (baseurl) {
                        form_data.append('base', baseurl);
                    }
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('elemental-organisation-tab', 'elemental-organisation-tab');
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('search_term', search);
                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);
                            if (state_response.organisation && state_response.orgtarget) {
                                $('#' + state_response.orgtarget).html(state_response.organisation);
                                let orgcount = $('#group-dir-count-bottom').text();

                                let number = orgcount.match(/\d+/g).pop();

                                if (number) {
                                    $('#elemental-org-result').html('Organisations (' + number + ')');
                                }
                            }
                            init();
                        },
                        error: function(response) {
                            console.log('Error in Search Organisation');
                        }
                    });
                }

                /**
                 * Search only Members. (used for pagination)
                 */
                var memberonly = function(search, page, type) {
                    console.log('membersearch' + search + page + type);
                    notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid"),
                        productid = $('#elemental-pageinfo').data("productid");

                    var form_data = new FormData();
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('elemental-member-tab', 'elemental-member-tab');

                    if (page) {
                        form_data.append('page', page);
                    }
                    if (type) {
                        form_data.append('type', type);
                    }
                    if (search) {
                        form_data.append('search_term', search);
                    }

                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);

                            if (state_response.member && state_response.membertarget) {
                                $('#' + state_response.membertarget).html(state_response.member);
                                let membercount = $('#member-dir-count-bottom').text();
                                console.log(membercount);
                                let number = membercount.match(/\d+/g).pop();

                                if (number) {
                                    $('#elemental-member-result').html('Platform Users (' + number + ')');
                                }
                            }
                            init();
                        },
                        error: function(response) {
                            console.log('Error in Search Content');
                        }
                    });
                }

                /**
                 * Search only Groups. (used for pagination)
                 */
                var grouponly = function(search, page, type) {
                    console.log('groupsearch' + search + page + type);
                    notification = $('#searchnotification'),
                        searchid = $('#elemental-pageinfo').data("searchid"),
                        productid = $('#elemental-pageinfo').data("productid");

                    var form_data = new FormData();
                    form_data.append('action', 'elemental_searchadmin2_ajax');
                    form_data.append('searchid', searchid);
                    form_data.append('productid', productid);
                    form_data.append('elemental-group-tab', 'elemental-group-tab');

                    if (page) {
                        form_data.append('page', page);
                    }
                    if (type) {
                        form_data.append('type', type);
                    }
                    if (search) {
                        form_data.append('search_term', search);
                    }

                    form_data.append('security', elemental_searchadmin2_ajax.security);
                    $.ajax({
                        type: 'post',
                        dataType: 'html',
                        url: elemental_searchadmin2_ajax.ajax_url,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(response) {
                            var state_response = JSON.parse(response);

                            if (state_response.group && state_response.grouptarget) {
                                $('#' + state_response.grouptarget).html(state_response.group);
                                let groupcount = $('#group-dir-count-bottom').text();
                                console.log(groupcount);
                                let number = groupcount.match(/\d+/g).pop();

                                if (number) {
                                    $('#elemental-group-result').html('Groups (' + number + ')');
                                } else {
                                    $('#elemental-group-result').html('Groups ( 0 )');
                                }
                            }
                            init();
                        },
                        error: function(response) {
                            console.log('Error in Search Content');
                        }
                    });
                }


                init(true);
            }
        );
    }
); //end