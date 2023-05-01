<?php
/**
 * Filters to Modify Default WCFM Behaviour.
 *
 * @package elemental/wcfm/library/class-wcfmfilters.php
 */

namespace ElementalPlugin\Module\WCFM\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Filters to Modify Default WCFM Behaviour.
 */
class WCFMStyling {

	const SETTING_WCFM_DASHBOARD_TEMPLATE_ID = 'elemental-wcfm-dashboard-template';
	/**
	 * Run the Filters.
	 */
	public function init() {

		\add_filter( 'elemental_store_header_class', array( $this, 'elemental_header_style_filter' ), 10, 3 );
		\add_shortcode( 'elemental_wcfm_dashboard_switch', array( $this, 'elemental_wcfm_dashboard_switch_worker' ) );

		// Option for WCFM Dashboard Template.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'update_wcfm_dashboard_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_wcfm_dashboard_setting' ), 10, 2 );

	}

	/**
	 * Run the Filters.
	 */
	public function elemental_header_style_filter( $input_class, int $store_id, $object ) {

		if ( Factory::get_instance( WCFMTools::class )->elemental_am_i_premium( $store_id ) ) {
			$input_class .= ' elemental-premium-header ';
		}
		return $input_class;
	}

	/**
	 * Run the Shortcode.
	 *
	 * @return string
	 */
	public function elemental_wcfm_dashboard_switch_worker(): string {
		$protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';
		$url      = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		if ( function_exists( 'get_wcfm_url' ) && get_wcfm_url() !== $url ) {
			return \do_shortcode( '[wc_frontend_manager]' );
		} else {
			$template = \do_shortcode( '[elementor-template id="' . \get_option( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID ) . '"]' );
			if ( strlen( $template ) > 0 ) {
				return $template;
			} else {
				return esc_html__( ' No Valid Template for Control Panel Page Found', 'elemental-plugin' );
			}
		}
	}

	/**
	 * Add WCFM Dashboard Template Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_wcfm_dashboard_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'WCFM Dashboard Page ID', 'elemental' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="elemental-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID ) . '"
		value="' . get_option( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID ) . '">
			<i class="elemental-dashicons elemental-icons dashicons-editor-help" title="' . \esc_html__( ' Post ID of Template to Call for a WCFM Store Dashboard in the main page- this will be used in main page', 'elemental' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. WCFM Dashboard Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_wcfm_dashboard_settings( array $response ): array {
		$current_value = \get_option( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID );
		$field         = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID );
		if ( $field !== $current_value ) {
			\update_option( self::SETTING_WCFM_DASHBOARD_TEMPLATE_ID, $field );
			$response['feedback'] = \esc_html__( 'WCFM Dashboard Saved', 'elemental' );
		}

		return $response;
	}
}
