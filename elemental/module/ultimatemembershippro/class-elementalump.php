<?php
/**
 * Ultimate Membership Pro Helpers
 * Lets Elemental understand the UMP methods.
 *
 * @package ElementalPlugin\Module\Membership
 */

namespace ElementalPlugin\Module\UltimateMembershipPro;

use ElementalPlugin\Factory;
use ElementalPlugin\Module\UltimateMembershipPro\Library\ShortCodesUMP;
use ElementalPlugin\Library\Ajax;

/**
 * Class ElementalUMP
 * Supports Ultimate Membership Pro Functions.
 */
class ElementalUMP {

	const SETTING_UMP_STAFF_SUBSCRIPTION_ID = 'elemental-ump-staff-subscription-id';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		\add_shortcode( 'elemental_ump', array( Factory::get_instance( ShortCodesUMP::class ), 'render_level_name' ) );

		// Option for WCFM Store Template.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_ump_staffid_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_ump_staffid_setting' ), 10, 2 );
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
	 * Add WCFM Archive Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_ump_staffid_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'UMP Staff Subscription ID', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_UMP_STAFF_SUBSCRIPTION_ID ) . '"
		value="' . get_option( self::SETTING_UMP_STAFF_SUBSCRIPTION_ID ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'UMP Subscription ID for the Staff subscription (used to auto assign it to new staff)', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. WCFM Update Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_ump_staffid_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_UMP_STAFF_SUBSCRIPTION_ID );
		\update_option( self::SETTING_UMP_STAFF_SUBSCRIPTION_ID, $field );
		$response['feedback'] = \esc_html__( 'Staff Subscription ID Saved', 'myvideoroom' );
		return $response;
	}
}


