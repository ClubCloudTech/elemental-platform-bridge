<?php
/**
 * Display Functions PHP legacy
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\WCFM\WCFMTools;

/**
 * Class SectionTemplate
 */
class ElementalFunctions {

	/**
	 * Initialise Module
	 *
	 * @return void
	 */
	public function init() {
		// Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( $id_only ) ;
		// $this->alextest();
		add_filter( 'wcfm_login_redirect', array( $this, 'custom_wcfm_login_redirect' ), 10, 2 );
		// add_filter( 'wcfm_login_redirect', array ( Factory::get_instance( testclass::class), 'custom_wcfm_login_redirect' ), 10, 2 );
	}

	private function alextest() {
		echo 'Alex';
	}

	public function custom_wcfm_login_redirect( $redirect_to, $user ) {
		$redirect_to = $site_url;
		return $redirect_to . '/control-centre';
	}


}

