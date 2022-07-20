<?php
/**
 * Handling Content Search.
 *
 * @package search/library/class-contentsearch.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

namespace ElementalPlugin\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Content Search Functions
 */
class ContentSearch {

	const SEARCH_CONTENT_TAB = 'elemental-main-content';

	/**
	 * Render Content Search Results Tab.
	 *
	 * @param array  $input   - the inbound menu.
	 * @param string $search_template  - the search template.
	 * @param string $product_template - Product template not used.
	 *
	 * @return array - outbound menu.
	 */
	public function render_content_search_result_tab( array $input, string $search_template = null, string $product_template = null ): array {

		$host_menu = new MenuTabDisplay(
			\esc_html__( 'News', 'myvideoroom' ),
			'content',
			fn() => $this->render_content_search_template( $search_template ),
			'elemental-search-result'
		);

		array_push( $input, $host_menu );

		return $input;
	}

	/**
	 * Render Content Search Template
	 *
	 * @param string $search_template  - the search template.
	 * @return array
	 */
	public function render_content_search_template( string $search_template = null ) :string {
		$tab       = self::SEARCH_CONTENT_TAB;
		$shortcode = \do_shortcode( '[elementor-template id="' . \esc_textarea( $search_template ) . '"]' );
		$render    = include __DIR__ . '/../views/contentsearch/search-render.php';
		return $render( $shortcode, $tab );

	}

	/**
	 * Ajax Handler for Content Search Response.
	 *
	 * @param array  $response - the inbound response object.
	 * @param string $search_term - the term searched for.
	 * @return array
	 */
	public function content_search_response( array $response, string $search_term ): array {
		$action_taken    = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_CONTENT_TAB );
		$refresh_tabs    = Factory::get_instance( Ajax::class )->get_string_parameter( 'refresh_tabs' );
		$search_template = Factory::get_instance( Ajax::class )->get_string_parameter( 'searchid' );

		if ( 'refresh_tabs' === $refresh_tabs ) {
			$screen              = $this->render_content_search_template( $search_template );
			$response['content'] = $screen;
		}

		if ( self::SEARCH_CONTENT_TAB === $action_taken ) {
			$page                     = Factory::get_instance( Ajax::class )->get_integer_parameter( 'page' );
			$content_return           = $this->process_content_search( $search_term, $search_template, $page );
			$response['content']      = $content_return['screen'];
			$response['contentcount'] = $content_return['count'];
		}
		$response['contenttarget'] = self::SEARCH_CONTENT_TAB;
		return $response;
	}

	/**
	 * Content Search Query Processing Terms
	 *
	 * @param string $search_term - the product search term.
	 * @param int    $search_template - the ID of the search template in shortcode.
	 * @param int    $page_number - the return page number.
	 * @return array
	 */
	private function process_content_search( string $search_term, int $search_template, int $page_number = null ): array {
		// Get the terms IDs for the current product related to 'collane' custom taxonomy.
		global $wp_query;
		$pagenum = $page_number ?? 1;
		// phpcs:ignore -- WordPress.WP.GlobalVariablesOverride.Prohibited - intercepting main site search function by design.
		$wp_query = new \WP_Query(
			array(
				'post_status'    => 'publish',
				'posts_per_page' => 20,
				'paged'          => $pagenum,
				's'              => $search_term,
			)
		);

		$search_template        = Factory::get_instance( Ajax::class )->get_integer_parameter( 'searchid' );
		$render                 = include __DIR__ . '/../views/contentsearch/content-render.php';
		$tab                    = self::SEARCH_CONTENT_TAB;
		$content                = $this->render_content_search_template( $search_template );
		$return_array           = array();
		$return_array['screen'] = $render( $content, $tab );
		$return_array['count']  = $wp_query->found_posts;

		return $return_array;
	}

}
