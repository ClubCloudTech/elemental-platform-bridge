<?php
/**
 * Handling Organisation Search.
 *
 * @package search/library/class-organisationsearch.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

namespace ElementalPlugin\Module\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMHelpers;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;
use ElementalPlugin\Library\Ajax;

/**
 * Handling Organisation Search.
 */
class OrganisationSearch {

	const SEARCH_ORG_TAB = 'elemental-organisation-tab';
		/**
		 * Organisation Search
		 */

		/**
		 * Render WCFM Organisations Tabs.
		 *
		 * @param array $input   - the inbound menu.
		 * @return array
		 */
	public function render_organisations_tabs( array $input = null ) :array {

		$admin_menu = new MenuTabDisplay(
			\esc_html__( 'Organisations', 'myvideoroom' ),
			'organisations',
			fn() => $this->render_wcfm_organisations(),
			'elemental-org-result'
		);
		\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Render Organisations from WCFM. Initial Page Render Template.
	 *
	 * @param string $search_term -Whether to search on a given term.
	 * @return array
	 */
	private function render_wcfm_organisations( string $search_term = null ) :string {
		$tab_name = self::SEARCH_ORG_TAB;
		$page_num = Factory::get_instance( Ajax::class )->get_string_parameter( 'page' );
		$base_url = Factory::get_instance( Ajax::class )->get_string_parameter( 'base' );

		if ( $page_num ) {
			$pagedinfo = 'paged = ' . $page_num . ' ';
		}
		if ( $base_url ) {
			$baseinfo = 'baseurl = ' . $base_url . ' ';
		}

		if ( $search_term || $page_num ) {
			$premium_display = null;
			$main_display    = \do_shortcode( '[elemental_show_stores ' . $pagedinfo . 'search_term="' . $search_term . '" ' . $baseinfo . ' theme="simple" has_map="no" has_orderby="yes" ]' );
		} else {
			$premium_display = \do_shortcode( '[wcfm_stores_carousel theme="simple" include_membership="' . get_option( WCFMHelpers::SETTING_WCFM_PREMIUM_MEMBERSHIPS ) . '" ]' );
			$main_display    = \do_shortcode( '[elemental_show_stores  ' . $baseinfo . ' paged =1 theme="simple" has_map="no" has_orderby="yes" ]' );
		}

		$render = include __DIR__ . '/../views/orgsearch/wcfm-orgs.php';
		return $render( $main_display, $tab_name, $premium_display );
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
		$refresh_tabs = Factory::get_instance( Ajax::class )->get_string_parameter( 'refresh_tabs' );
		if ( 'refresh_tabs' === $refresh_tabs ) {
			$screen                   = $this->render_wcfm_organisations();
			$response['organisation'] = $screen;
		}

		if ( self::SEARCH_ORG_TAB === $action_taken ) {
			$response['organisation'] = $this->render_wcfm_organisations( $search_term );
		}
		$response['orgtarget'] = self::SEARCH_ORG_TAB;
		return $response;
	}

	/**
	 * WCFM Stores Short Code
	 * Originally by WC Lovers - Modified by ClubCloud.
	 *
	 * @param array $atts - the shortcode attributes.
	 */
	public function elemental_show_stores( $atts ): ?string {
		if ( ! Factory::get_instance( WCFMTools::class )->is_wcfmmp_available() ) {
			return null;
		}

		global $WCFM, $WCFMmp, $includes;
		$WCFM->nocache();
		ob_start();
		$defaults = array(
			'per_page'           => 20,
			'paged'              => '',
			'baseurl'            => '',
			'sidebar'            => 'yes',
			'orderby'            => 'newness_asc',
			'filter'             => 'yes',
			'search'             => 'yes',
			'category'           => 'yes',
			'country'            => 'yes',
			'state'              => 'yes',
			'radius'             => 'yes',
			'map'                => 'yes',
			'map_zoom'           => 5,
			'auto_zoom'          => 'yes',
			'per_row'            => 3,
			'includes'           => '',
			'excludes'           => '',
			'include_membership' => '',
			'exclude_membership' => '',
			'search_term'        => '',
			'search_country'     => '',
			'search_state'       => '',
			'search_city'        => '',
			'search_zip'         => '',
			'search_category'    => '',
			'store_category'     => '',
			'theme'              => 'classic',
			'has_orderby'        => 'yes',
			'has_product'        => 'no',
			'has_sidebar'        => 'yes',
			'has_filter'         => 'yes',
			'has_search'         => 'yes',
			'has_category'       => 'yes',
			'has_country'        => 'yes',
			'has_state'          => 'yes',
			'has_city'           => 'no',
			'has_zip'            => 'no',
			'has_radius'         => 'yes',
			'has_map'            => 'yes',
		);

		$attr     = shortcode_atts( apply_filters( 'wcfmmp_stores_default_args', $defaults ), $atts );
		$paged    = max( 1, $attr['paged'] );
		$base_url = $attr['baseurl'];

		$length = apply_filters( 'wcfmmp_stores_per_page', $attr['per_page'] );
		$offset = ( $paged - 1 ) * absint( $length );

		$search_country = isset( $attr['search_country'] ) ? $attr['search_country'] : '';
		$search_state   = isset( $attr['search_state'] ) ? $attr['search_state'] : '';

		// GEO Locate Support
		if ( apply_filters( 'wcfmmp_is_allow_store_list_by_user_location', true ) ) {
			if ( is_user_logged_in() && ! $search_country ) {
				$user_location = get_user_meta( get_current_user_id(), 'wcfm_user_location', true );
				if ( $user_location ) {
					$search_country = $user_location['country'];
					$search_state   = $user_location['state'];
				}
			}

			if ( apply_filters( 'wcfm_is_allow_wc_geolocate', true ) && class_exists( 'WC_Geolocation' ) && ! $search_country ) {
				$user_location  = \WC_Geolocation::geolocate_ip();
				$search_country = $user_location['country'];
				$search_state   = $user_location['state'];
			}
		}

		$orderby     = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : apply_filters( 'wcfmmp_stores_default_orderby', $attr['orderby'] );
		$search_term = isset( $_GET['wcfmmp_store_search'] ) ? sanitize_text_field( $_GET['wcfmmp_store_search'] ) : $attr['search_term'];

		$search_country = isset( $_GET['wcfmmp_store_country'] ) ? sanitize_text_field( $_GET['wcfmmp_store_country'] ) : '';
		$search_state   = isset( $_GET['wcfmmp_store_state'] ) ? sanitize_text_field( $_GET['wcfmmp_store_state'] ) : '';
		$search_city    = isset( $_GET['wcfmmp_store_city'] ) ? sanitize_text_field( $_GET['wcfmmp_store_city'] ) : $attr['search_city'];
		$search_zip     = isset( $_GET['wcfmmp_store_zip'] ) ? sanitize_text_field( $_GET['wcfmmp_store_zip'] ) : $attr['search_zip'];

		$search_category = isset( $_GET['wcfmmp_store_category'] ) ? sanitize_text_field( $_GET['wcfmmp_store_category'] ) : $attr['search_category'];
		$store_category  = isset( $_GET['wcfmsc_store_categories'] ) ? sanitize_text_field( $_GET['wcfmsc_store_categories'] ) : $attr['store_category'];

		$search_category = apply_filters( 'wcfmmp_stores_default_search_category', $search_category );
		$has_product     = apply_filters( 'wcfmmp_stores_list_has_product', wc_string_to_bool( $attr['has_product'] ) );

		$has_sidebar = isset( $attr['sidebar'] ) ? $attr['sidebar'] : 'yes';
		$has_sidebar = isset( $attr['has_sidebar'] ) ? $attr['has_sidebar'] : $has_sidebar;

		$has_filter = isset( $attr['filter'] ) ? $attr['filter'] : 'yes';
		$has_filter = isset( $attr['has_filter'] ) ? $attr['has_filter'] : $has_filter;

		$has_search = isset( $attr['search'] ) ? $attr['search'] : 'yes';
		$has_search = isset( $attr['has_search'] ) ? $attr['has_search'] : $has_search;

		$has_category = isset( $attr['category'] ) ? $attr['category'] : 'yes';
		$has_category = isset( $attr['has_category'] ) ? $attr['has_category'] : $has_category;

		$has_country = isset( $attr['country'] ) ? $attr['country'] : 'yes';
		$has_country = isset( $attr['has_country'] ) ? $attr['has_country'] : $has_country;

		$has_state = isset( $attr['state'] ) ? $attr['state'] : 'yes';
		$has_state = isset( $attr['has_state'] ) ? $attr['has_state'] : $has_state;

		$has_city = isset( $attr['has_city'] ) ? $attr['has_city'] : 'no';

		$has_zip = isset( $attr['has_zip'] ) ? $attr['has_zip'] : 'no';

		$has_radius = isset( $attr['radius'] ) ? $attr['radius'] : 'yes';
		$has_radius = isset( $attr['has_radius'] ) ? $attr['has_radius'] : $has_radius;

		$has_map = isset( $attr['map'] ) ? $attr['map'] : 'yes';
		$has_map = isset( $attr['has_map'] ) ? $attr['has_map'] : $has_map;

		$search_data = array();
		if ( $store_category ) {
			$search_data['wcfmsc_store_categories'] = $store_category;
		}
		if ( $search_country ) {
			$search_data['wcfmmp_store_country'] = $search_country;
		}
		if ( $search_state ) {
			$search_data['wcfmmp_store_state'] = $search_state;
		}
		if ( $search_city ) {
			$search_data['wcfmmp_store_city'] = $search_city;
		}
		if ( $search_zip ) {
			$search_data['wcfmmp_store_zip'] = $search_zip;
		}
		if ( isset( $_POST['search_data'] ) ) {
			parse_str( $_POST['search_data'], $search_data );
		} elseif ( isset( $_GET['orderby'] ) ) {
			$search_data = wp_unslash( $_GET );
		} else {
			$search_data['orderby'] = $orderby;
		}

		// Exclude Membership.
		$exclude_members    = array();
		$exclude_membership = isset( $attr['exclude_membership'] ) ? sanitize_text_field( $attr['exclude_membership'] ) : '';
		if ( $exclude_membership ) {
			$exclude_membership = explode( ',', $exclude_membership );
		}
		if ( ! empty( $exclude_membership ) && is_array( $exclude_membership ) ) {
			foreach ( $exclude_membership as $wcfm_membership ) {
				$membership_users = (array) get_post_meta( $wcfm_membership, 'membership_users', true );
				$exclude_members  = array_merge( $exclude_members, $membership_users );
			}
		}
		if ( $exclude_members ) {
			$exclude_members = implode( ',', $exclude_members );
		} else {
			$exclude_members = '';
		}

		// Excluded Stores from List.
		$excludes                = ! empty( $attr['excludes'] ) ? sanitize_text_field( $attr['excludes'] ) : $exclude_members;
		$search_data['excludes'] = $excludes;

		// Include Membership.
		$include_members    = array();
		$include_membership = isset( $attr['include_membership'] ) ? sanitize_text_field( $attr['include_membership'] ) : '';
		if ( $include_membership ) {
			$include_membership = explode( ',', $include_membership );
		}
		if ( ! empty( $include_membership ) && is_array( $include_membership ) ) {
			foreach ( $include_membership as $wcfm_membership ) {
				$membership_users = (array) get_post_meta( $wcfm_membership, 'membership_users', true );
				$include_members  = array_merge( $include_members, $membership_users );
			}
		}
		if ( $include_members ) {
			$include_members = implode( ',', $include_members );
		} else {
			$include_members = '';
		}

		// Include Store List.
		$includes = ! empty( $attr['includes'] ) ? sanitize_text_field( $attr['includes'] ) : $include_members;
		if ( $includes ) {
			$includes = explode( ',', $includes );
		} else {
			$includes = array();
		}

		// Radius Search.
		$enable_wcfm_storelist_radius = isset( $WCFMmp->wcfmmp_marketplace_options['enable_wcfm_storelist_radius'] ) ? $WCFMmp->wcfmmp_marketplace_options['enable_wcfm_storelist_radius'] : 'no';
		$has_radius                   = wc_string_to_bool( $has_radius );
		if ( ( 'yes' === $enable_wcfm_storelist_radius ) && $has_radius ) {
			$has_radius = true;
		} else {
			$has_radius = false;
		}
		$api_key      = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_google_map_api'] : '';
		$wcfm_map_lib = isset( $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] ) ? $WCFMmp->wcfmmp_marketplace_options['wcfm_map_lib'] : '';
		if ( ! $wcfm_map_lib && $api_key ) {
			$wcfm_map_lib = 'google';
		} elseif ( ! $wcfm_map_lib && ! $api_key ) {
			$wcfm_map_lib = 'leaftlet'; }
		if ( ( 'google' === $wcfm_map_lib ) && empty( $api_key ) ) {
			$has_radius = false;
		}
		if ( $has_radius ) {
			$has_country = 'no';
			$has_state   = 'no';
			$has_city    = 'no';
			$has_zipcode = 'no';
		}

		$search_data = apply_filters( 'wcfmmp_stores_search_data', $search_data );

		$stores = $WCFMmp->wcfmmp_vendor->wcfmmp_search_vendor_list( true, $offset, $length, $search_term, $search_category, $search_data, $has_product, $includes );

		$template_args = apply_filters(
			'wcfmmp_stores_args',
			array(
				'stores'          => $stores,
				'limit'           => $length,
				'offset'          => $offset,
				'includes'        => $includes,
				'excludes'        => $excludes,
				'paged'           => $paged,
				'baseurl'         => $base_url,
				'image_size'      => 'full',
				'orderby'         => $orderby,
				'search_term'     => $search_term,
				'search_country'  => $search_country,
				'search_state'    => $search_state,
				'search_city'     => $search_city,
				'search_zip'      => $search_zip,
				'search_category' => $search_category,
				'store_category'  => $store_category,
				'search_data'     => $search_data,
				'has_product'     => $has_product,
				'has_orderby'     => wc_string_to_bool( $attr['has_orderby'] ),
				'sidebar'         => wc_string_to_bool( $has_sidebar ),
				'filter'          => wc_string_to_bool( $has_filter ),
				'search'          => wc_string_to_bool( $has_search ),
				'category'        => wc_string_to_bool( $has_category ),
				'country'         => wc_string_to_bool( $has_country ),
				'state'           => wc_string_to_bool( $has_state ),
				'has_city'        => wc_string_to_bool( $has_city ),
				'has_zip'         => wc_string_to_bool( $has_zip ),
				'map'             => wc_string_to_bool( $has_map ),
				'radius'          => $has_radius,
				'map_zoom'        => apply_filters( 'wcfmmp_map_default_zoom_level', $attr['map_zoom'] ),
				'auto_zoom'       => wc_string_to_bool( apply_filters( 'wcfmmp_is_allow_map_auto_zoom', $attr['auto_zoom'] ) ),
				'per_row'         => 4,
				'theme'           => 'compact',
			),
			$attr,
			$search_data
		);
		// global $template_args;
		$WCFMmp->template->get_template( 'store-lists/wcfmmp-view-store-lists.php', $template_args );
		return ob_get_clean();
	}

}
