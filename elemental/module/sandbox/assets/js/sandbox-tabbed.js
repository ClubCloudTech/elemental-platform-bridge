/**
 * Add dynamic tabs to Elemental admin pages
 *
 * @package Elemental Plugin assets/js/tabbed.js
 */

(function ($) {

	/**
	 * Hide all non active pages
	 */
		 var hide_all_non_active = function ($nav_section) {
			var $tabs = $( 'a.nav-tab:not(.nav-tab-active)', $nav_section );

			$tabs.each(
				function () {
					var target = $( this ).attr( 'href' );
					$( target ).hide();
				}
			);
		 };

	/**
	 * Initialise the plugin
	 *
	 * @param {JQuery} $parent
	 */
	var init = function ($parent) {

		var $tabbed_sections = $( '.elemental-nav-tab-wrapper', $parent );

		$tabbed_sections.each(
			function () {
				var $nav_section = $( this );
				hide_all_non_active( $nav_section );

				var $tabs = $( 'a.nav-tab', $nav_section );
				$tabs.each(
					function () {
						var $tab = $( this );
						$tab.on(
							'click',
							function (event) {
								$tab.trigger( 'focus' );
								id = $tab.attr( 'data-object-id' );
								$tabs.removeClass( 'nav-tab-active' );
								$tabs.css( "background-color", "white" );
								var $header_section = $( '.elemental-table-info-header' );
								var $headers        = $( 'th.elemental-table-header-info', $header_section );

								$headers.each(
									function () {
										$header_single = $( this );
										$header_single.css( "color", "#323064" );
									}
								);
								hide_all_non_active( $nav_section );
								$tab.addClass( 'nav-tab-active' );
								color = $tab.attr( 'data-color' );
								$( '#name_label_' + id ).css( "color", color );

								$tab.css( "background-color", color );

								$( $tab.attr( 'href' ) ).show();

								event.preventDefault();
								return false;
							}
						);
						$( '.elemental-notification-button' ).on(
							'click',
							function (event) {

								$tab.trigger( 'focus' );

								$tabs.removeClass( 'nav-tab-active' );
								$tabs.css( "background-color", "white" );
								hide_all_non_active( $nav_section );

								$tab.addClass( 'nav-tab-active' );
								$( $tab.attr( 'href' ) ).show();

								event.preventDefault();
								return false;
							}
						);
					}
				);
			}
		);

	};
	init( $( document ) );
})( jQuery );
