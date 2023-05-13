/**
 * Restricts an input to only alphanumeric characters and space
 *
 * @package Elemental
 */
window.addEventListener('load', function () {
	jQuery(function ($) {
		function init () {
			
			var $inputs = $('.elemental-input-restrict-alphanumeric');
			$inputs.on(
				'keyup keydown',
				function (e) {
					return ! !(/[a-z 0-9]$/i.test(e.key));
				}
			);
		}
		init();
		window.elemental_screenprotect_init = init;
	})


})