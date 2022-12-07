<?php
/**
 * Ultimate Membership Pro Helpers
 * Lets Elemental understand the UMP methods.
 *
 * @package ElementalPlugin\Module\Membership
 */

namespace ElementalPlugin\Module\UltimateMembershipPro;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\UltimateMembershipPro\Library\ShortCodesUMP;
use ElementalPlugin\Library\Ajax;

/**
 * Class ElementalUMP
 * Supports Ultimate Membership Pro Functions.
 */
class ElementalUMP {

	const SETTING_UMP_TENANT_ADMIN_SUBSCRIPTION_ID = 'elemental-ump-staff-subscription-id';
	const SETTING_UMP_TENANT_SUBSCRIPTION_ID       = 'elemental-ump-tenant-subscription-id';
	const SETTING_UMP_SPONSORED_SUBSCRIPTION_ID    = 'elemental-ump-sponsored-subscription-id';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		\add_shortcode( 'elemental_ump', array( Factory::get_instance( ShortCodesUMP::class ), 'render_level_name' ) );

		// Option for Staff ID Setting.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'update_ump_staffid_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_ump_staffid_setting' ), 10, 2 );

		// Option for Sponsored Account Setting.
		\add_filter( 'elemental_maintenance_result_listener', array( $this, 'update_ump_sponsored_id_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_ump_sponsored_id_setting' ), 10, 2 );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_ump_membership_page() {

	}

	/**
	 * AddUMP Staff ID Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_ump_staffid_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'UMP Tenant Admin Subscription ID', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="mvr-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::SETTING_UMP_TENANT_ADMIN_SUBSCRIPTION_ID ) . '"
		value="' . get_option( self::SETTING_UMP_TENANT_ADMIN_SUBSCRIPTION_ID ) . '">
			<i class="elemental-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'UMP Subscription ID for the Tenant Admin subscription (used to auto assign it to new Admins)', 'elementalplugin' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. UMP staff ID Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_ump_staffid_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_UMP_TENANT_ADMIN_SUBSCRIPTION_ID );
		\update_option( self::SETTING_UMP_TENANT_ADMIN_SUBSCRIPTION_ID, $field );
		$response['feedback'] = \esc_html__( 'Tenant Admin Subscription ID Saved', 'elementalplugin' );
		return $response;
	}

	/**
	 * AddUMP Staff ID Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_ump_sponsored_id_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'UMP Sponsored (Regular User) Subscription ID', 'elementalplugin' ) . '</span>
		</td>
		<td>
		<input type="number" size="8"
		class="mvr-main-button-enabled elemental-maintenance-setting"
		id="' . esc_attr( self::SETTING_UMP_SPONSORED_SUBSCRIPTION_ID ) . '"
		value="' . get_option( self::SETTING_UMP_SPONSORED_SUBSCRIPTION_ID ) . '">
			<i class="elemental-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Normal User Account Subscription  (used to auto assign it to new users)', 'elementalplugin' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. UMP Sponsored (Normal User) ID Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_ump_sponsored_id_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_UMP_SPONSORED_SUBSCRIPTION_ID );
		\update_option( self::SETTING_UMP_SPONSORED_SUBSCRIPTION_ID, $field );
		$response['feedback'] = \esc_html__( 'Sponsored Account Subscription ID Saved', 'elementalplugin' );
		return $response;
	}
}


