<?php
/**
 * Rendering Controllers for Sandbox.
 *
 * @package module/sandbox/library/class-sandboxrender.php
 */

 // phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.


namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\HttpGet;
use ElementalPlugin\Library\TabHelper;

/**
 * Class Sandbox Render.
 */
class SandboxRender extends TabHelper {

	/**
	 * Install the shortcode
	 */
	public function init() {
		add_shortcode( 'elemental_sandbox', array( $this, 'render_sandbox_shortcode' ) );
	}

	/**
	 * Render Sandbox shortcode.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sandbox_shortcode( $attributes = array() ): ?string {
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
		return $this->sandbox_shortcode_handler( $header_template, $search_template, $product_template, $tab, $only );
	}

	/**
	 * Handle Sandbox Shortcode Generation.
	 *
	 * @param string $header_template - Header Template.
	 * @param string $search_template - Search Template.
	 * @param string $product_template - Product Template.
	 * @param string $tab - starting tab (optional).
	 * @param bool   $only - return a single tab only in case of tab sort (optional).
	 */
	public function sandbox_shortcode_handler( string $header_template = null, string $search_template = null, string $product_template = null, string $tab = null, bool $only = null ) {

		$header       = \do_shortcode( '[elementor-template id="' . \esc_attr( $header_template ) . '"]' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = Factory::get_instance( SandBoxHelpers::class )->render_all_tabs();
		$tabs         = $this->tab_sort( $tabs, $tab, $only );

		$render = include __DIR__ . '/../views/view-sandbox-main.php';
		return $render( $header, $html_library, $tabs );

	}
}
