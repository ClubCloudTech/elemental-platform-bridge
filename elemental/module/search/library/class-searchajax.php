<?php
/**
 * Ajax for Search Handling.
 *
 * @package elemental/search/library/class-searchajax.php
 */

namespace ElementalPlugin\Module\Search\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Library\Ajax;

/**
 * Ajax for Search Handling.
 */
class SearchAjax {

	/**
	 * Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 * @return mixed
	 */
	public function search_ajax_handler() {
		$response = array();

		check_ajax_referer( 'elemental_membership', 'security', false );
		$search_term = Factory::get_instance( Ajax::class )->get_string_parameter( 'search_term' );

		$response = \apply_filters( 'elemental_search_ajax_response', $response, $search_term );

		return \wp_send_json( $response );
		die();
	}

	/**
	 * Enqueue_ajax_scripts - enqueues and localises scripts for Ajax.
	 *
	 * @return void
	 */
	public function enqueue_ajax_scripts() {
		// Enqueue Script Ajax Handling.

		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		\wp_register_script(
			'elemental-search-js',
			\plugins_url( '/../js/searchadmin.js', \realpath( __FILE__ ) ),
			array( 'jquery' ),
			$plugin_version . \wp_rand( 40, 30000 ),
			true
		);
		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_search' ),

		);

		wp_localize_script(
			'elemental-search-js',
			'elemental_searchadmin_ajax',
			$script_data_array
		);
	}
}
