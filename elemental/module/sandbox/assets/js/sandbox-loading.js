/**
 * Add dynamic tabs to Elemental admin pages
 *
 * @package Elemental Plugin module/sandbox/assets/js/sandbox-loading.js
 */

function onReady(callback) {
	var intervalID = window.setInterval( checkReady, 1500 );

	function checkReady() {
		if (document.getElementsByTagName( 'body' )[0] !== undefined) {
			window.clearInterval( intervalID );
			callback.call( this );
		}
	}
}

function show(id, value) {
	document.getElementById( id ).style.display = value ? 'block' : 'none';
}

onReady(
	function() {
		show( 'elemental-sandbox-base', true );
		show( 'elemental-loading', false );
	}
);

let seller   = document.querySelectorAll( 'i[data-type="seller-copy"]' );
let buyer    = document.querySelectorAll( 'i[data-type="buyer-copy"]' );
let property = document.querySelectorAll( 'i[data-type="property-copy"]' );
seller.forEach(
	function(button) {
		button.addEventListener(
			"click",
			function() {
				let seller = this.parentNode.parentNode.querySelector(
					'td[data-type="seller"]'
				).innerText;
				navigator.clipboard.writeText( seller );
				console.log( `${seller} copied.` );
			}
		);
	}
);
buyer.forEach(
	function(button) {
		button.addEventListener(
			"click",
			function() {
				let buyer = this.parentNode.parentNode.querySelector(
					'td[data-type="buyer"]'
				).innerText;

				navigator.clipboard.writeText( buyer );
				console.log( `${buyer} copied.` );
			}
		);
	}
);
property.forEach(
	function(button) {
		button.addEventListener(
			"click",
			function() {
				let property  = this.parentNode.parentNode.querySelector(
					'td[data-type="property"]'
				).innerText;
				var splitName = property.split( "," );
				var length    = splitName.length - 1;
				navigator.clipboard.writeText( splitName[length] );
				console.log( `${splitName[length]} copied.` );
			}
		);
	}
);
