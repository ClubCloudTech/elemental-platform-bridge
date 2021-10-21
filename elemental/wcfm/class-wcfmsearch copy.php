<?php
/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 *
 * @package ElementalPlugin\WoocommerceBookings
 */

namespace ElementalPlugin\WCFM;

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
		add_action( 'after_setup_theme', array( Factory::get_instance( WCFMTools::class ), 'elemental_get_wcfm_memberships' ) );
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
		$tabs         = $this->get_wcfm_tabs();
		wp_enqueue_style( 'wcfmmp_store_list_css', $WCFMmp->library->css_lib_url_min . 'store-lists/wcfmmp-style-stores-list.css', array(), $WCFMmp->version );
		$render = include __DIR__ . '/views/maintemplate.php';
		return $render( $header, $html_library, $tabs );

	}

	/**
	 * Get the list of Subscription Levels
	 *
	 * @return array
	 */
	public function get_wcfm_tabs() :array {

		$membership_levels = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships();
		//echo \var_dump( $membership_levels );
		$return_array = array();

			$admin_menu = new MenuTabDisplay(
				'Partners',
				'Stores-wcfm',
				fn() => \do_shortcode( '[wcfm_stores exclude_membership="11" has_map=”no” has_orderby=”yes” theme="simple"]' )
			);
			\array_push( $return_array, $admin_menu );

		return $return_array;
	}
}
