<?php
/**
 * Search Handling Class for Cross Site Search Handler.
 *
 * @package elemental/search/library/class-wcfmsearch.php
 */

 // phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.


namespace ElementalPlugin\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\WCFM\Library\WCFMHelpers;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Class Site Search
 */
class SiteSearch {

	const SEARCH_ORG_TAB     = 'elemental-organisation-tab';
	const SEARCH_PRODUCT_TAB = 'elemental-main-products';
	const SEARCH_CONTENT_TAB = 'elemental-main-content';

	/**
	 * Install the shortcode
	 */
	public function init() {
		add_shortcode( 'elemental_search', array( $this, 'render_sitesearch_shortcode' ) );
		$this->add_search_tabs();
	}

	/**
	 * Render Sitesearch shortcode.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sitesearch_shortcode( $attributes = array() ): ?string {

		$header_template  = $attributes['header'] ?? null;
		$search_template  = $attributes['search'] ?? null;
		$product_template = $attributes['product'] ?? null;

		return $this->sitesearch_shortcode_handler( $header_template, $search_template, $product_template );
	}

	/**
	 * Handle Site Search Shortcode Generation.
	 *
	 * @param string $header_template - Header Template.
	 * @param string $search_template - Search Template.
	 * @param string $product_template - Product Template.
	 */
	public function sitesearch_shortcode_handler( string $header_template = null, string $search_template = null, string $product_template = null ) {
		$this->enqueue_wcfm_dependencies();
		\wp_enqueue_script( 'elemental-search-js' );
		$header       = \do_shortcode( '[elementor-template id="' . \esc_attr( $header_template ) . '"]' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = apply_filters( 'elemental_search_template_render', $tabs, $search_template, $product_template );

		$render = include __DIR__ . '/../views/maintemplate.php';
		return $render( $header, $html_library, $tabs, $search_template );

	}

	/**
	 * Get the list of Subscription Levels
	 *
	 * @param array $input   - the inbound menu.
	 * @return array
	 */
	public function get_wcfm_tabs( array $input = null ) :array {

			$admin_menu = new MenuTabDisplay(
				\esc_html__( 'Organisations', 'myvideoroom' ),
				\esc_html__( 'Organisations', 'myvideoroom' ),
				fn() => $this->render_wcfm_organisations(),
				'elemental-org-result'
			);
			\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Ajax Handler for Organisation Search Response.
	 *
	 * @param array  $response - the inbound response object.
	 * @param string $search_term - the term searched for.
	 * @return array
	 */
	public function organisation_search_response( array $response, string $search_term ): array {
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_ORG_TAB );
		if ( self::SEARCH_ORG_TAB === $action_taken ) {
			$response['organisation'] = $this->render_wcfm_organisations( $search_term );
			$response['orgtarget']    = self::SEARCH_ORG_TAB;
		}
		return $response;
	}

	/**
	 * Render WCFM Window.
	 *
	 * @param string $search_term -Whether to search on a given term.
	 * @return array
	 */
	public function render_wcfm_organisations( string $search_term = null ) :string {
		$tab_name = self::SEARCH_ORG_TAB;
		if ( $search_term ) {
			$premium_display = null;
			$main_display    = \do_shortcode( '[wcfm_stores search_term="' . $search_term . '" theme="simple" has_map="no" has_orderby="yes" ]' );
		} else {
			$premium_display = \do_shortcode( '[wcfm_stores_carousel theme="simple" include_membership="' . get_option( WCFMHelpers::SETTING_WCFM_PREMIUM_MEMBERSHIPS ) . '" ]' );
			$main_display    = \do_shortcode( '[wcfm_stores theme="simple" has_map="no" has_orderby="yes" ]' );
		}

		$render = include __DIR__ . '/../views/wcfm-orgs.php';
		return $render( $premium_display, $main_display, $tab_name );
	}

	/**
	 * Add Search Tabs
	 *
	 * @return void
	 */
	public function add_search_tabs() {
		// Add Search Tab.
		add_filter( 'elemental_search_template_render', array( $this, 'render_search_result_tab' ), 15, 3 );
		add_filter( 'elemental_search_ajax_response', array( $this, 'content_search_response' ), 10, 2 );
		// Partners Organisation Tab.
		add_filter( 'elemental_search_template_render', array( $this, 'get_wcfm_tabs' ), 5, 3 );
		add_filter( 'elemental_search_ajax_response', array( $this, 'organisation_search_response' ), 10, 2 );

		// Products Organisation Tab.
		add_filter( 'elemental_search_template_render', array( $this, 'render_product_result_tab' ), 5, 3 );
		add_filter( 'elemental_search_ajax_response', array( $this, 'product_search_response' ), 10, 2 );

	}
	/**
	 * Render SiteVideo Welcome Tab.
	 *
	 * @param array  $input   - the inbound menu.
	 * @param string $search_template  - the search template.
	 * @param string $product_template - Product template not used.
	 *
	 * @return array - outbound menu.
	 */
	public function render_search_result_tab( array $input, string $search_template = null, string $product_template = null ): array {

		$host_menu = new MenuTabDisplay(
			\esc_html__( 'Content', 'myvideoroom' ),
			'elemental-content',
			fn() => $this->render_search_template( $search_template ),
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
	public function render_search_template( string $search_template = null ) :string {
		$tab       = self::SEARCH_CONTENT_TAB;
		$shortcode = \do_shortcode( '[elementor-template id="' . \esc_textarea( $search_template ) . '"]' );
		$render    = include __DIR__ . '/../views/search-render.php';
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
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_CONTENT_TAB );
		if ( self::SEARCH_CONTENT_TAB === $action_taken ) {
			$page                      = intval( Factory::get_instance( Ajax::class )->get_string_parameter( 'page' ) );
			$content_return            = $this->search_terms( $search_term, $page );
			$response['content']       = $content_return['screen'];
			$response['contentcount']  = $content_return['count'];
			$response['contenttarget'] = self::SEARCH_CONTENT_TAB;
		}
		return $response;
	}

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
	 * Render Product Template
	 *
	 * @param string $product_template  - the product template ID.
	 * @return array
	 */
	public function render_product_template( string $product_template = null ) :?string {
		$tab       = self::SEARCH_PRODUCT_TAB;
		$shortcode = \do_shortcode( '[elementor-template id="' . \esc_textarea( $product_template ) . '"]' );
		$render    = include __DIR__ . '/../views/products-render.php';
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
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_PRODUCT_TAB );
		if ( self::SEARCH_PRODUCT_TAB === $action_taken ) {
			$product_return            = $this->search_products( $search_term );
			$response['product']       = $product_return['screen'];
			$response['productcount']  = $product_return['count'];
			$response['producttarget'] = self::SEARCH_PRODUCT_TAB;
		}
		return $response;
	}

	private function enqueue_wcfm_dependencies(): void {
			global $WCFM, $WCFMmp;
			$WCFM->library->load_select2_lib();
			wp_enqueue_script( 'wc-country-select' );
			$WCFMmp->library->load_map_lib();
			$enable_store_radius = isset( $WCFMmp->wcfmmp_marketplace_options['enable_wcfm_storelist_radius'] ) ? $WCFMmp->wcfmmp_marketplace_options['enable_wcfm_storelist_radius'] : 'no';
			wp_enqueue_script( 'wcfmmp_store_list_js', $WCFMmp->library->js_lib_url_min . 'store-lists/wcfmmp-script-store-lists.js', array( 'jquery' ), $WCFMmp->version, true );
			wp_localize_script(
				'wcfmmp_store_list_js',
				'wcfmmp_store_list_messages',
				array(
					'choose_category' => __( 'Choose Category', 'wc-multivendor-marketplace' ),
					'choose_location' => __( 'Choose Location', 'wc-multivendor-marketplace' ),
					'choose_state'    => __(
						'Choose State',
						'wc-multivendor-marketplace'
					),
				)
			);
			wp_localize_script(
				'wcfmmp_store_list_js',
				'wcfmmp_store_list_options',
				array(
					'search_location'      => __( 'Insert your address ..', 'wc-multivendor-marketplace' ),
					'is_geolocate'         => apply_filters( 'wcfmmp_is_allow_store_list_by_user_location', true ),
					'max_radius'           => apply_filters( 'wcfmmp_radius_filter_max_distance', $max_radius_to_search ),
					'radius_unit'          => ucfirst( $radius_unit ),
					'start_radius'         => apply_filters( 'wcfmmp_radius_filter_start_distance', 10 ),
					'default_lat'          => $default_lat,
					'default_lng'          => $default_lng,
					'default_zoom'         => absint( $default_zoom ),
					'icon_width'           => apply_filters( 'wcfmmp_map_icon_width', 40 ),
					'icon_height'          => apply_filters( 'wcfmmp_map_icon_height', 57 ),
					'is_poi'               => apply_filters( 'wcfmmp_is_allow_map_poi', true ),
					'is_allow_scroll_zoom' => apply_filters( 'wcfmmp_is_allow_map_scroll_zoom', true ),
					'is_cluster'           => apply_filters( 'wcfmmp_is_allow_map_pointer_cluster', true ),
					'cluster_image'        => apply_filters( 'wcfmmp_is_cluster_image', 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m' ),
					'is_rtl'               => is_rtl(),
				)
			);

	}

	/**
	 * Search Products
	 *
	 * @param string $search_term - the product search term.
	 * @return array
	 */
	private function search_products( string $search_term ): array {
		// Get the terms IDs for the current product related to 'collane' custom taxonomy.

		$query    = new \WP_Query(
			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				's'                   => $search_term,
			)
		);
		ob_start();
		?>
			<div class="woocommerce">
				<ul id="elemental-product-grid" class="products elementor-grid oceanwp-row clr grid tablet-col tablet-3-col" style="display: initial;">
					<?php
					// The WP_Query loop.
					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							wc_get_template_part( 'content', 'product' );
					endwhile;
						wp_reset_postdata();
					endif;
					?>
				</ul>
			</div>
			<?php
			$return_array           = array();
			$return_array['screen'] = ob_get_clean();
			$return_array['count']  = $query->found_posts;
			return $return_array;
	}

	/**
	 * Search Terms
	 *
	 * @param string $search_term - the product search term.
	 * @param int    $page_number - the return page number.
	 * @return array
	 */
	private function search_terms( string $search_term, int $page_number = null ): array {
		// Get the terms IDs for the current product related to 'collane' custom taxonomy.
		global $wp_query;
		$pagenum = $page_number ?? 1;
		// phpcs:ignore -- WordPress.WP.GlobalVariablesOverride.Prohibited - intercepting main site search function by design.
		$wp_query = new \WP_Query(
			array(
				'post_status'    => 'publish',
				'posts_per_page' => 5,
				'paged'          => $pagenum,
				's'              => $search_term,
			)
		);

		$search_template        = Factory::get_instance( Ajax::class )->get_integer_parameter( 'searchid' );
		$render                 = include __DIR__ . '/../views/content-render.php';
		$tab                    = self::SEARCH_CONTENT_TAB;
		$content                = $this->render_search_template( $search_template );
		$return_array           = array();
		$return_array['screen'] = $render( $content, $tab );
		$return_array['count']  = $wp_query->found_posts;

		return $return_array;
	}

}
