<?php
/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\HTML;

/**
 * Class WCFM Search
 */
class WCFMSearch {

	/**
	 * Install the shortcode
	 */
	public function init() {
		add_shortcode( 'wcfmdisplay', array( $this, 'render_wcfmdisplay_shortcode' ) );
		$this->add_search_tabs();
	}

	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_wcfmdisplay_shortcode( $attributes = array() ): ?string {
		$categories = $attributes['categories'] ?? null;
		return $this->wcfmdisplay_shortcode_handler( $categories );
	}

	/**
	 * Handle WCFM Store Shortcode Generation
	 *
	 * @param array $params List of shortcode params.
	 */
	public function wcfmdisplay_shortcode_handler( $params = array() ) {
		global $WCFMmp;
		$header       = \do_shortcode( '[elementor-template id="27398"]' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = apply_filters( 'elemental_search_template_render', $tabs );
		wp_enqueue_style( 'wcfmmp_store_list_css', $WCFMmp->library->css_lib_url_min . 'store-lists/wcfmmp-style-stores-list.css', array(), $WCFMmp->version );
		$render = include __DIR__ . '/views/maintemplate.php';
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
		$output  = '<h1>' . \esc_html__( 'Featured Partnerships', 'myvideoroom ' ) . '</h1>';
		$output .= \do_shortcode( '[wcfm_stores_carousel theme="simple" include_membership="' . WCFMTools::PREMIUM_LIST . '" has_search=”no” ]' );
		$output .= '<h1>' . \esc_html__( 'All of our Partners', 'myvideoroom ' ) . '</h1>';
		$output .= \do_shortcode( '[wcfm_stores theme="simple" has_search=”no” has_map=”no” has_orderby=”yes” ]' );

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
					1
				);
				// Partners Organisation Tab.
				add_filter(
					'elemental_search_template_render',
					array(
						$this,
						'get_wcfm_tabs',
					),
					5,
					1
				);
					// Products Organisation Tab.
					add_filter(
						'elemental_search_template_render',
						array(
							$this,
							'render_product_result_tab',
						),
						5,
						1
					);

	}
	/**
	 * Render SiteVideo Welcome Tab.
	 *
	 * @param array $input   - the inbound menu.
	 *
	 * @return array - outbound menu.
	 */
	public function render_search_result_tab( array $input ): array {

		$host_menu = new MenuTabDisplay(
			\esc_html__( 'Content', 'myvideoroom' ),
			'elemental-content',
			fn() => $this->render_search_template(),
			'elemental-search-result'
		);

		array_push( $input, $host_menu );

		return $input;
	}

	/**
	 * Render Search Template
	 *
	 * @return array
	 */
	public function render_search_template() :string {
		$output = \do_shortcode( '[elementor-template id="27419"]' );
		return $output;
	}

	/**
	 * Render SiteVideo Welcome Tab.
	 *
	 * @param array $input   - the inbound menu.
	 *
	 * @return array - outbound menu.
	 */
	public function render_product_result_tab( array $input ): array {

		$host_menu = new MenuTabDisplay(
			\esc_html__( 'Product', 'myvideoroom' ),
			'elemental-product',
			fn() => $this->render_product_template(),
			'elemental-search-result'
		);

		array_push( $input, $host_menu );

		return $input;
	}

	/**
	 * Render Search Template
	 *
	 * @return array
	 */
	public function render_product_template() :?string {
		$output = \do_shortcode( '[elementor-template id="27425"]' );
		return $output;
	}

}
