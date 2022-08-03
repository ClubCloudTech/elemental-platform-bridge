/**
 * Ajax and Advanced Tab Control.
 *
 * @package module/sandbox/assets/js/tabmanage.js
 */
window.addEventListener(
	"load",
	function () {
		jQuery(
			function ($) {

				/**
				 * Initialise Functions on Load
				 */
				function init () {

					$('.elemental-sandbox-add').click(
						function (e) {
							e.stopPropagation();
							e.stopImmediatePropagation();
							e.preventDefault();
							$('.elemental-add-new-sandbox').slideToggle(600);
						}
					);

					$('.elemental-sandbox-control').on('change', sandboxUpload);
					$('.elemental-membership-template').on('change', templateUpload);
					$('.elemental-membership-landing-template').on('change', landingTemplateUpload);


					$(document).on("dblclick", ".nav-tab", function (e) {

						logintest(e);
						e.stopPropagation();
						e.preventDefault();

					})
				}

				$(function () {
					var tabs = $("#tabs").tabs();
					var previouslyFocused = false;

					tabs.find(".ui-tabs-nav").sortable({
						axis: "x",

						// Sortable removes focus, so we need to restore it if the tab was focused
						// prior to sorting
						start: function (event, ui) {
							previouslyFocused = document.activeElement === ui.item[0];
						},
						stop: function (event, ui) {
							tabs.tabs("refresh");
							tabpriority();
							if (previouslyFocused) {
								ui.item.trigger("focus");
							}
						}
					});
				});


				/**
				 * Update Account Limits on Database by Subscription Level (used in backend admin page)
				 */
				var sandboxUpload = function (event) {
					event.stopPropagation();
					var level = event.target.dataset.level,
						field = event.target.dataset.field,
						value = event.target.value,
						checkbox = $('#' + event.target.name + '_' + level).prop('checked');
					form_data = new FormData();
					form_data.append('action', 'elemental_sandbox_script_object');
					form_data.append('action_taken', 'update_sandbox');
					form_data.append('field', field);
					form_data.append('checkbox', checkbox);
					form_data.append('level', level);
					form_data.append('value', value);
					form_data.append('security', elemental_sandbox_script_object.security);
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_sandbox_script_object.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse(response);
								console.log(state_response.feedback);
								$('#confirmation_' + level).html(state_response.feedback).fadeOut(2000);

							},
							error: function (response) {
								console.log('Error Uploading Level');
							}
						}
					);
				}

				/**
				 * Update Account Limits on Database by Subscription Level (used in backend admin page)
				 */
				var logintest = function (event) {
					event.stopPropagation();
					var level = event.target.dataset.level,
						field = event.target.dataset.field,
						value = event.target.value,
						checkbox = $('#' + event.target.name + '_' + level).prop('checked');
					form_data = new FormData();
					form_data.append('action', 'elemental_sandbox_script_object');
					form_data.append('action_taken', 'login');
					form_data.append('field', field);
					form_data.append('checkbox', checkbox);
					form_data.append('level', level);
					form_data.append('value', value);
					form_data.append('security', elemental_sandbox_script_object.security);
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_sandbox_script_object.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse(response);
								console.log(state_response.feedback);
								$('#confirmation_' + level).html(state_response.feedback).fadeOut(2000);

							},
							error: function (response) {
								console.log('Error Uploading Level');
							}
						}
					);
				}

				/**
				 * Update Account Limits on Database by Subscription Level (used in backend admin page)
				 */
				var tabsort = function (tab_array) {

					var user = $('#elemental-sandbox-base').attr('data-user'),
						form_data = new FormData();
					form_data.append('action', 'elemental_sandbox_script_object');
					form_data.append('action_taken', 'tab_sort');
					form_data.append('levels', tab_array);
					form_data.append('user', user);
					form_data.append('security', elemental_sandbox_script_object.security);
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_sandbox_script_object.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse(response);
								console.log(state_response.feedback);

							},
							error: function (response) {
								console.log('Error Uploading Level');
							}
						}
					);
				}

				/**
				 * Update Account Limits on Database by Subscription Level (used in backend admin page)
				 */
				var templateUpload = function (event) {
					event.stopPropagation();
					var level = event.target.dataset.level,
						value = event.target.value,
						form_data = new FormData();

					form_data.append('action', 'elemental_sandbox_script_object');
					form_data.append('action_taken', 'update_template');
					form_data.append('level', level);
					form_data.append('value', value);
					form_data.append('security', elemental_sandbox_script_object.security);
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_sandbox_script_object.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse(response);
								console.log(state_response.feedback);
								$('#confirmation_template_' + level).html(state_response.feedback);

							},
							error: function (response) {
								console.log('Error Uploading Template');
							}
						}
					);
				}

				/**
				 * Update Landing Template on Database by Subscription Level (used in backend admin page)
				 */
				var landingTemplateUpload = function (event) {
					event.stopPropagation();
					var level = event.target.dataset.level,
						value = event.target.value,
						user = $('#elemental-sandbox-base').attr('data-user'),
						form_data = new FormData();
					console.log(level + value);
					form_data.append('action', 'elemental_sandbox_script_object');
					form_data.append('action_taken', 'update_landing_template');
					form_data.append('level', level);
					form_data.append('user', user);
					form_data.append('value', value);
					form_data.append('security', elemental_sandbox_script_object.security);
					$.ajax(
						{
							type: 'post',
							dataType: 'html',
							url: elemental_sandbox_script_object.ajax_url,
							contentType: false,
							processData: false,
							data: form_data,
							success: function (response) {
								var state_response = JSON.parse(response);
								console.log(state_response.feedback);
								$('#confirmation_template_' + level).html(state_response.feedback);

							},
							error: function (response) {
								console.log('Error Uploading Landing Template');
							}
						}
					);
				}

				var tabpriority = function () {

					var $tabbed_sections = $('.sandbox-menu-header'),
						allListElements = $("li"),
						$section = $($tabbed_sections).find(allListElements);

					var count = 0;
					const taborder = [];
					$.each($section, function () {
						taborder[count] = $(this).attr('data-elementid');
						console.log($(this).attr('data-elementid') + 'position' + count);
						count++;
					});
					tabsort(taborder);
				};

				init();
			}
		);
	}
);
