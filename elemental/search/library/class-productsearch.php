<?php
/**
 * Handling Product Search.
 *
 * @package search/library/class-productsearch.php
 */

namespace ElementalPlugin\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Product Search Functions
 */
class ProductSearch {

	const SEARCH_PRODUCT_TAB = 'elemental-main-products';

	/**
	 * Render Product Result Tab
	 *
	 * @param array  $input   - the inbound menu.
	 * @param string $search_template  - the search template not used.
	 * @param string $product_template - Product template .
	 * @return array - outbound menu.
	 */
	public function render_product_result_tab( array $input, string $search_template = null, string $product_template = null ): array {

		$host_menu = new MenuTabDisplay(
			\esc_html__( 'Products', 'myvideoroom' ),
			'elemental-product',
			fn() => $this->render_product_template( $product_template ),
			'elemental-product-result'
		);

		array_push( $input, $host_menu );

		return $input;
	}

	/**
	 * Render Inital Display Product Template
	 *
	 * @param string $product_template  - the product template ID.
	 * @return array
	 */
	public function render_product_template( string $product_template = null ) :?string {
		$tab       = self::SEARCH_PRODUCT_TAB;
		$shortcode = \do_shortcode( '[elementor-template id="' . \esc_textarea( $product_template ) . '"]' );
		$render    = include __DIR__ . '/../views/productsearch/products-initial-render.php';
		return $render( $shortcode, $tab );
	}

	/**
	 * Ajax Handler for Product Search Response.
	 *
	 * @param array  $response - the inbound response object.
	 * @param string $search_term - the term searched for.
	 * @return array
	 */
	public function product_search_response( array $response, string $search_term ): array {
		$action_taken     = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_PRODUCT_TAB );
		$refresh_tabs     = Factory::get_instance( Ajax::class )->get_string_parameter( 'refresh_tabs' );
		$product_template = Factory::get_instance( Ajax::class )->get_string_parameter( 'productid' );

		if ( 'refresh_tabs' === $refresh_tabs ) {
			$screen              = $this->render_product_template( $product_template );
			$response['product'] = $screen;
		}

		if ( self::SEARCH_PRODUCT_TAB === $action_taken ) {
			$page                     = Factory::get_instance( Ajax::class )->get_integer_parameter( 'page' );
			$product_return           = $this->search_products( $search_term, $page );
			$response['product']      = $product_return['screen'];
			$response['productcount'] = $product_return['count'];
		}
		$response['producttarget'] = self::SEARCH_PRODUCT_TAB;
		return $response;
	}

	/**
	 * Search Products - Execute Search.
	 *
	 * @param string $search_term - the product search term.
	 * @param int    $page - the page ID from the search for pagination.
	 * @return array
	 */
	private function search_products( string $search_term, int $page = null ): array {
		// Get the terms IDs for the current product related to 'collane' custom taxonomy.

		$query                      = new \WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 12,
				'paged'          => $page,
				'post_status'    => 'publish',
				's'              => $search_term,
			)
		);
			$render                 = include __DIR__ . '/../views/productsearch/product-search-render.php';
			$tab                    = self::SEARCH_PRODUCT_TAB;
			$return_array           = array();
			$return_array['screen'] = $render( $query, $tab, $page );
			$return_array['count']  = $query->found_posts;

			return $return_array;
	}

}
