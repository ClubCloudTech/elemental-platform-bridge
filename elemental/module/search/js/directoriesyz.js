(function($) {

	'use strict';

	$( document ).ready(
		function() {

			/**
			 * Display Activity tools.
			 */
			$( '#youzify' ).on(
				'click',
				'a.page-numbers',
				function(e) {
					// if ( target.parent().parent().hasClass( 'pagination' )  ) {
					var button_clone = $( this ).clone().html( '<i class="fas fa-spinner fa-spin"></i>' );
					$( this ).hide(
						0,
						function() {
							button_clone.insertAfter( $( this ) );

						}
					);
				}
			);

			$( '#members_search,#groups_search' ).on(
				'click',
				function() {
					$( window ).off( 'resize' );
				}
			);

			// Add Loading Button
			$( '#youzify-groups-list,#youzify-members-list ' ).on(
				'click',
				'a.group-button:not(.membership-requested),.friendship-button:not(.awaiting_response_friend) a',
				function(e) {
					e.preventDefault();
					$( this ).addClass( 'youzify-btn-loading' );
				}
			);

			// Display Search Box.
			$( '#directory-show-search' ).on(
				'click',
				function(e) {
					e.preventDefault();
					$( '.elemental-directory-filter #members-order-select,.elemental-directory-filter #groups-order-select,.elemental-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
					$( '#elemental-directory-search-box' ).fadeToggle();
				}
			);

			// Display Search Box.
			$( '#directory-show-filter' ).on(
				'click',
				function(e) {
					e.preventDefault();
					$( '#elemental-directory-search-box,.elemental-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
					$( '.elemental-directory-filter #members-order-select, .elemental-directory-filter #groups-order-select' ).fadeToggle();
				}
			);

			// Display Search Box.
			$( '#directory-show-menu' ).on(
				'click',
				function(e) {
					e.preventDefault();
					$( '#elemental-directory-search-box,.elemental-directory-filter #members-order-select,.elemental-directory-filter #groups-order-select' ).fadeOut( 1 );
					$( '.elemental-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeToggle();
				}
			);

			// Activate Members Masonry Layout.
			if ($( '#youzify-members-list' )[0]) {

				// Set the container that Masonry will be inside of in a var
				var members_container = document.querySelector( '#youzify-members-list' );

				// Create empty var msnry
				var members_msnry;

				// Initialize Masonry after all images have loaded
				imagesLoaded(
					members_container,
					function() {
						members_msnry = new Masonry(
							members_container,
							{
								itemSelector: '#youzify-members-list li',
							}
						);

					}
				);

			}

			// Activate Groups Masonry Layout.
			if ($( '#youzify-groups-list' )[0]) {

				// Set the container that Masonry will be inside of in a var
				var groups_container = document.querySelector( '#youzify-groups-list' );

				// Create empty var msnry
				var groups_msnry;

				// Initialize Masonry after all images have loaded
				imagesLoaded(
					groups_container,
					function() {
						groups_msnry = new Masonry(
							groups_container,
							{
								itemSelector: '#youzify-groups-list li'
							}
						);
					}
				);

			}

			// Display Search Box.
			$( '#directory-show-search a' ).on(
				'click',
				function(e) {
					e.preventDefault();
					$( '#elemental-directory-search-box' ).fadeToggle();
				}
			);

			// Display Search Box.
			$( '#directory-show-filter a' ).on(
				'click',
				function(e) {
					e.preventDefault();
					$( '.elemental-directory-filter #members-order-select, .elemental-directory-filter #groups-order-select' ).fadeToggle();
				}
			);

			var masonryUpdate = function() {
				setTimeout(
					function() {
						$( '#youzify-members-list' ).masonry();
					},
					0
				);
			}
			// $(document).on('click', masonryUpdate);
			$( document ).ajaxComplete( masonryUpdate );
			masonryUpdate();

		}
	);

})( jQuery );
