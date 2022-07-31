<?php
/**
 * Search Handling Class for Cross Site Search Handler.
 *
 * @package module/search/library/class-sitesearch.php
 */

 // phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.


namespace ElementalPlugin\Module\Search\Library;

use ElementalPlugin\Module\BBPress\ElementalBBPress;
use ElementalPlugin\Module\BuddyPress\ElementalBP;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Library\HttpGet;
use ElementalPlugin\Module\WCFM\WCFM;
use ElementalPlugin\Library\TabHelper;

/**
 * Class Site Search
 */
class SiteSearch extends TabHelper {

	/**
	 * Install the shortcode
	 */
	public function init() {

		add_shortcode( 'elemental_search', array( $this, 'render_sitesearch_shortcode' ) );
		add_shortcode( 'elemental_show_stores', array( Factory::get_instance( OrganisationSearch::class ), 'elemental_show_stores' ) );
		add_shortcode( 'elemental_show_members', array( Factory::get_instance( MemberSearch::class ), 'elemental_members_shortcode' ) );
		add_shortcode( 'elemental_show_forums', array( Factory::get_instance( ForumSearch::class ), 'elemental_display_search' ) );
		add_shortcode( 'elemental_show_groups', array( Factory::get_instance( GroupSearch::class ), 'elemental_group_shortcode' ) );
	}

	/**
	 * Render Sitesearch shortcode.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sitesearch_shortcode( $attributes = array() ): ?string {
		$this->add_search_tabs();
		$header_template  = $attributes['header'] ?? null;
		$search_template  = $attributes['search'] ?? null;
		$product_template = $attributes['product'] ?? null;
		$tab              = $attributes['tab'] ?? null;
		$only             = $attributes['only'] ?? false;
		if ( ! $tab ) {
			$http_get_library = Factory::get_instance( HttpGet::class );
			$tab              = $http_get_library->get_string_parameter( 'tab' ) ?? null;
		}
		if ( ! $only ) {
			$http_get_library = Factory::get_instance( HttpGet::class );
			$only             = $http_get_library->get_string_parameter( 'only' ) ?? false;
		}
		return $this->sitesearch_shortcode_handler( $header_template, $search_template, $product_template, $tab, $only );
	}

	/**
	 * Handle Site Search Shortcode Generation.
	 *
	 * @param string $header_template - Header Template.
	 * @param string $search_template - Search Template.
	 * @param string $product_template - Product Template.
	 * @param string $tab - starting tab (optional).
	 * @param bool   $only - return a single tab only in case of tab sort (optional).
	 */
	public function sitesearch_shortcode_handler( string $header_template = null, string $search_template = null, string $product_template = null, string $tab = null, bool $only = null ) {
		global $post;
		$this->enqueue_organisation_wcfm_dependencies();
		\wp_enqueue_script( 'elemental-search-js' );
		\wp_enqueue_script( 'elemental-admin-tabs' );
		$header          = \do_shortcode( '[elementor-template id="' . \esc_attr( $header_template ) . '"]' );
		$html_library    = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs            = array();
		$tabs            = apply_filters( 'elemental_search_template_render', $tabs, $search_template, $product_template );
		$tabs            = $this->tab_sort( $tabs, $tab, $only );
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
		$org_search_available   = Factory::get_instance( WCFMTools::class )->is_wcfmmp_available();
		$member_group_available = Factory::get_instance( ElementalBP::class )->is_group_module_available();
		$wcfm_available         = Factory::get_instance( WCFM::class )->is_wcfm_active();
		$bbpress_available      = Factory::get_instance( ElementalBBPress::class )->is_bbpress_active();
		$logged_in              = \is_user_logged_in();

		// Content Search Tab and Handler.
		add_filter( 'elemental_search_template_render', array( Factory::get_instance( ContentSearch::class ), 'render_content_search_result_tab' ), 15, 3 );
		add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( ContentSearch::class ), 'content_search_response' ), 10, 2 );

		if ( $org_search_available ) {
			// Organisation Search Tab and Handler.
			add_filter( 'elemental_search_template_render', array( Factory::get_instance( OrganisationSearch::class ), 'render_organisations_tabs' ), 5, 3 );
			add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( OrganisationSearch::class ), 'organisation_search_response' ), 10, 2 );
		}

		if ( $wcfm_available ) {
			// Products Organisation Tab and Handler.
			add_filter( 'elemental_search_template_render', array( Factory::get_instance( ProductSearch::class ), 'render_product_result_tab' ), 5, 3 );
			add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( ProductSearch::class ), 'product_search_response' ), 10, 2 );
		}

		if ( $member_group_available && $logged_in ) {
			// Member Search Tab and Handler.
			add_filter( 'elemental_search_template_render', array( Factory::get_instance( MemberSearch::class ), 'render_members_tabs' ), 5, 3 );
			add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( MemberSearch::class ), 'member_search_response' ), 10, 2 );
			if ( function_exists( 'bp_is_active' ) || ! bp_is_active( 'groups' ) ) {
				// Group Search Tab and Handler.
				add_filter( 'elemental_search_template_render', array( Factory::get_instance( GroupSearch::class ), 'render_group_tabs' ), 5, 3 );
				add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( GroupSearch::class ), 'group_search_response' ), 10, 2 );
			}
		}
		if ( $bbpress_available && $logged_in ) {
			add_filter( 'elemental_search_template_render', array( Factory::get_instance( ForumSearch::class ), 'render_forum_tabs' ), 5, 3 );
			add_filter( 'elemental_search_ajax_response', array( Factory::get_instance( ForumSearch::class ), 'forum_search_response' ), 10, 2 );
		}
	}
	/**
	 * Dequeue BP Legacy Script
	 * Interferes with Shortcode.
	 *
	 * @return void
	 */
	public function dequeue_bp_legacy() {
		wp_dequeue_script( 'bp-legacy-js' );
	}
	/**
	 * Enqueue WCFM dependencies. Needed to make product and other templates run correctly for org cards.
	 *
	 * @return ?null
	 */
	private function enqueue_organisation_wcfm_dependencies() {
		if ( ! Factory::get_instance( WCFMTools::class )->is_wcfmmp_available() ) {
			return null;
		}
		global $WCFM, $WCFMmp;
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		$WCFM->library->load_select2_lib();
		wp_enqueue_script( 'wc-country-select' );
		$WCFMmp->library->load_map_lib();
		wp_register_script( 'wcfmmp_store_list_js', \plugins_url( '/../js/elemental-script-store-lists.js', \realpath( __FILE__ ) ), array( 'jquery' ), $plugin_version . \wp_rand( 1, 2000 ), true );

		wp_enqueue_script( 'wcfmmp_store_list_js' );

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
