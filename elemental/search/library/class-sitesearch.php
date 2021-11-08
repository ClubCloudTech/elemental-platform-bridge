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

/**
 * Class Site Search
 */
class SiteSearch {

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
		global $WCFMmp;
		$header       = \do_shortcode( '[elementor-template id="' . \esc_attr( $header_template ) . '"]' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = apply_filters( 'elemental_search_template_render', $tabs, $search_template, $product_template );
		\wp_enqueue_style( 'wcfmmp_store_list_css', $WCFMmp->library->css_lib_url_min . 'store-lists/wcfmmp-style-stores-list.css', array(), $WCFMmp->version );
		\wp_enqueue_script( 'elemental-search-js' );
		$render = include __DIR__ . '/../views/maintemplate.php';
		return $render( $header, $html_library, $tabs );

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
				fn() => $this->render_wcfm_order()
			);
			\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Render WCFM Order
	 *
	 * @return array
	 */
	public function render_wcfm_order() :string {
		$output  = '<h1 class="elemental-login-button">' . \esc_html__( 'Featured Partnerships', 'myvideoroom ' ) . '</h1>';
		$output .= \do_shortcode( '[wcfm_stores_carousel theme="simple" include_membership="' . get_option( WCFMHelpers::SETTING_WCFM_PREMIUM_MEMBERSHIPS ) . '" has_search="no" ]' );
		$output .= '<h1 class="elemental-login-button">' . \esc_html__( 'All of UAM Organisations', 'myvideoroom ' ) . '</h1>';
		$output .= \do_shortcode( '[wcfm_stores theme="simple" has_search="no" has_map="no" has_orderby="yes" ]' );

		return $output;
	}

	/**
	 * Add Search Tabs
	 *
	 * @return void
	 */
	public function add_search_tabs() {
				// Add Search Tab.
				add_filter(
					'elemental_search_template_render',
					array(
						$this,
						'render_search_result_tab',
					),
					15,
					3
				);
				// Partners Organisation Tab.
				add_filter(
					'elemental_search_template_render',
					array(
						$this,
						'get_wcfm_tabs',
					),
					5,
					3
				);
					// Products Organisation Tab.
					add_filter(
						'elemental_search_template_render',
						array(
							$this,
							'render_product_result_tab',
						),
						5,
						3
					);

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
	 * Render Search Template
	 *
	 * @param string $search_template  - the search template.
	 * @return array
	 */
	public function render_search_template( string $search_template = null ) :string {
		$output = \do_shortcode( '[elementor-template id="' . \esc_textarea( $search_template ) . '"]' );
		return $output;
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
			\esc_html__( 'Product', 'myvideoroom' ),
			'elemental-product',
			fn() => $this->render_product_template( $product_template ),
			'elemental-search-result'
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
		$output = \do_shortcode( '[elementor-template id="' . \esc_textarea( $product_template ) . '"]' );
		return $output;
	}

}
