<?php

add_filter(
	'wcfm_login_redirect',
	function( $redirect_to, $user ) {
		if ( in_array( 'wcfm_vendor', $user->roles ) ) {
			$redirect_to = get_permalink( wc_get_page_id( 'myaccount' ) );
		}
		return $redirect_to;
	},
	50,
	2
);
