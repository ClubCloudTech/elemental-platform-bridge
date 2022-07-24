<?php
/**
 * Search Handling Class for Cross Site Search Handler.
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\Module\Search;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Search\Library\SearchAjax;
use ElementalPlugin\Module\Search\Library\SiteSearch;

/**
 * Class WCFM Connect
 */
class Search {

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {

		Factory::get_instance( SiteSearch::class )->init();

		// Ajax Listeners.
		\add_action( 'wp_ajax_elemental_searchadmin_ajax', array( Factory::get_instance( SearchAjax::class ), 'search_ajax_handler' ), 10, 2 );
		\add_action( 'wp_ajax_nopriv_elemental_searchadmin_ajax', array( Factory::get_instance( SearchAjax::class ), 'search_ajax_handler' ), 10, 2 );
		// Ajax Script Enqueue.
		Factory::get_instance( SearchAjax::class )->enqueue_ajax_scripts();
	}

	/**
	 * Activate Functions.
	 */
	public function activate() {

	}

	/**
	 * De-Activate Functions.
	 */
	public function de_activate() {

	}

}
