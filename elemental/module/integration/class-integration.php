<?php
/**
 * NMP Integration Package
 * Managing helper functions for Integration
 *
 * @package ElementalPlugin\Module\Integration
 */

namespace ElementalPlugin\Module\Integration;

use ElementalPlugin\Module\Integration\Actions;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Class Integration - Main Control Function Class for Integration.
 */
class Integration {

	const SETTING_INTEGRATION_API_KEY		= 'setting-integration-api-key';
	const SETTING_INTEGRATION_API_BASEURL 	= 'setting-integration-api-base-url';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init(): void {
		
		// Apply action hooks
		$actions = Factory::get_instance( Actions::class );
		\add_filter( 'elemental_pre_user_add', array( $actions, 'sync_employee' ), 10, 4 );
		// \add_filter( 'elemental_pre_tenant_add', array( $actions, 'sync_user' ), 10, 3 );
		// \add_filter( 'elemental_pre_company_add', array( $actions, 'sync_company' ), 10, 3 );

		// Add setting handlers
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_integration_api_key' ), 10, 2 );
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_integration_api_baseurl' ), 10, 2 );

		// Add settings to Elemental settings tab
		\add_filter( 'elemental_page_option', array( $this, 'add_integration_api_key_setting' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_integration_api_baseurl_setting' ), 10, 2 );
	}

	/**
	 * Process Update Result. Integration API Key
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_integration_api_key( array $response ): array {
		$value = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_INTEGRATION_API_KEY );
		\update_option( self::SETTING_INTEGRATION_API_KEY, $value );
		$response['feedback'] = \esc_html__( 'Integration API Key Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Process Update Result. Integration API Base URL
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_integration_api_baseurl( array $response ): array {
		$value = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_INTEGRATION_API_BASEURL );
		\update_option( self::SETTING_INTEGRATION_API_BASEURL, $value );
		$response['feedback'] = \esc_html__( 'Integration Base URL Key Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Add Integration API Key Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_integration_api_key_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Integration API Key', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="text" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( self::SETTING_INTEGRATION_API_KEY ) . '" value="' . \get_option( self::SETTING_INTEGRATION_API_KEY ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Integration API Key', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Add Integration API Base URL Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_integration_api_baseurl_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Integration API Base URL', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="text" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( self::SETTING_INTEGRATION_API_BASEURL ) . '" value="' . \get_option( self::SETTING_INTEGRATION_API_BASEURL ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Integration API Base URL', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Activate Functions for Integration Module.
	 */
	public function activate(): void {}

	/**
	 * De-Activate Functions for Integration Module.
	 */
	public function de_activate(): void {}

}