<?php
/**
 * Search Handling Class for Cross Site Search Handler.
 *
 * @package elemental/search/library/class-wcfmsearch.php
 */

 // phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.


namespace ElementalPlugin\Search\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\Version;

/**
 * Class Site Search
 */
class SiteSearch {

	/**
	 * Install the shortcode
	 */
	public function init() {
		add_shortcode( 'elemental_search', array( $this, 'render_sitesearch_shortcode' ) );
		add_shortcode( 'elemental_show_stores', array( Factory::get_instance( OrganisationSearch::class ), 'elemental_show_stores' ) );
		add_shortcode( 'elemental_show_members', array( Factory::get_instance( MemberSearch::class ), 'elemental_members_shortcode' ) );

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
		global $post;
		$this->enqueue_organisation_wcfm_dependencies();
		\wp_enqueue_script( 'elemental-search-js' );
		$header       = \do_shortcode( '[elementor-template id="' . \esc_attr( $header_template ) . '"]' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = apply_filters( 'elemental_search_template_render', $tabs, $search_template, $product_template );

		$pagination_base = str_replace( $post->ID, '%#%', esc_url( get_pagenum_link( $post->ID ) ) );

		$render = include __DIR__ . '/../views/maintemplate.php';
		return $render( $header, $pagination_base, $html_library, $tabs, $search_template, $product_template );

	}

	/**
	 * Search Tab Controller.
	 * Search Tabs for Rendering, and Ajax handling are registered here.
	 *
	 * @return void
	 */
	public function add_search_tabs() {
		// Add Content Search Tab and Handler.
		add_filter( 'elemental_search_template_render', array( Factory::get_instance( ContentSearch::class ), 'render_content_search_result_tab' ), 15, 3 );
		add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( ContentSearch::class ), 'content_search_response' ), 10, 2 );

		// Organisation Search Tab and Handler.
		add_filter( 'elemental_search_template_render', array( Factory::get_instance( OrganisationSearch::class ), 'render_organisations_tabs' ), 5, 3 );
		add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( OrganisationSearch::class ), 'organisation_search_response' ), 10, 2 );

		// Products Organisation Tab and Handler.
		add_filter( 'elemental_search_template_render', array( Factory::get_instance( ProductSearch::class ), 'render_product_result_tab' ), 5, 3 );
		add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( ProductSearch::class ), 'product_search_response' ), 10, 2 );

		// Products Organisation Tab and Handler.
		add_filter( 'elemental_search_template_render', array( Factory::get_instance( MemberSearch::class ), 'render_members_tabs' ), 5, 3 );
		add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( MemberSearch::class ), 'member_search_response' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_bp_legacy' ) );
	}

	public function dequeue_bp_legacy() {
		wp_dequeue_script( 'bp-legacy-js' );
	}
	/**
	 * Enqueue WCFM dependencies. Needed to make product and other templates run correctly for org cards.
	 *
	 * @return void
	 */
	private function enqueue_organisation_wcfm_dependencies(): void {
		global $WCFM, $WCFMmp;
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		$WCFM->library->load_select2_lib();
		wp_enqueue_script( 'wc-country-select' );
		$WCFMmp->library->load_map_lib();
		wp_enqueue_script( 'wcfmmp_store_list_js', \plugins_url( '/../js/elemental-script-store-lists.js', \realpath( __FILE__ ) ), array( 'jquery' ), $plugin_version . \wp_rand( 1, 2000 ), true );
		wp_enqueue_style( 'wcfmmp_store_list_css', $WCFMmp->library->css_lib_url_min . 'store-lists/wcfmmp-style-stores-list.css', array(), $WCFMmp->version );
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
				'max_radius'           => apply_filters( 'wcfmmp_radius_filter_max_distance', '' ),
				'start_radius'         => apply_filters( 'wcfmmp_radius_filter_start_distance', 10 ),
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
}
