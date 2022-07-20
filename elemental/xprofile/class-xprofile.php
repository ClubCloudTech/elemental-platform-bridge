<?php
/**
 * Connects MyVideoRoom to xprofile
 *
 * @package ElementalPlugin\XProfile
 */

namespace ElementalPlugin\XProfile;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Class XProfile
 */
class XProfile {

	const SETTING_XPROFILE_COUNTRY = 'elemental-xprofile-country';
	const SETTING_XPROFILE_CITY    = 'elemental-xprofile-city';
	const SETTING_XPROFILE_COMPANY = 'elemental-xprofile-company';

	/**
	 * Init Function
	 *
	 * @return void
	 */
	public function init() {
		// Listeners to results Admin Page.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'add_country_filter' ), 10, 2 );
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'add_city_filter' ), 10, 2 );
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'add_company_filter' ), 10, 2 );

		// Add Buttons to Admin Page.
		\add_filter( 'elemental_page_option', array( $this, 'add_country_setting' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_city_setting' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_company_setting' ), 10, 2 );

		// Update Xprofile Listener from Onboarding engine.
		add_action( 'update_individual_subs_registered_fields', array( $this, 'update_bp_settings' ), 10, 2 );
	}

	/**
	 * Process Update Result. Country.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function add_country_filter( array $response ): array {
		$setting_xprofile_field = Factory::get_instance( Ajax::class )->get_string_parameter( Xprofile::SETTING_XPROFILE_COUNTRY );
		\update_option( Xprofile::SETTING_XPROFILE_COUNTRY, intval( $setting_xprofile_field ) );
		$response['feedback'] = \esc_html__( 'Country Saved', 'myvideoroom' );
		return $response;
	}
	/**
	 * Process Update Result. City.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function add_city_filter( array $response ): array {
		$setting_xprofile_field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_XPROFILE_CITY );
		\update_option( self::SETTING_XPROFILE_CITY, intval( $setting_xprofile_field ) );
		$response['feedback'] = \esc_html__( 'City Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Process Update Result. City.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function add_company_filter( array $response ): array {
		$setting_xprofile_field = Factory::get_instance( Ajax::class )->get_string_parameter( Xprofile::SETTING_XPROFILE_COMPANY );
		\update_option( Xprofile::SETTING_XPROFILE_COMPANY, intval( $setting_xprofile_field ) );
		$response['feedback'] = \esc_html__( 'Company Saved', 'myvideoroom' );
		return $response;
	}
	/**
	 * Add Country Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_country_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Xprofile Field Countries', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( Xprofile::SETTING_XPROFILE_COUNTRY ) . '" value="' . get_option( self::SETTING_XPROFILE_COUNTRY ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Field Name for XProfile Countries', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}
	/**
	 * Add City Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_city_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Xprofile Field City', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( Xprofile::SETTING_XPROFILE_CITY ) . '" value="' . get_option( self::SETTING_XPROFILE_CITY ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Field Name for XProfile City', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Add Company Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_company_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Xprofile Field Company', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( self::SETTING_XPROFILE_COMPANY ) . '" value="' . get_option( self::SETTING_XPROFILE_COMPANY ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Field Name for XProfile Company', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Add Country Setting to Plugin Menu
	 *
	 * @param int $user_id - the user ID.
	 * @return void
	 */
	public function update_bp_settings( int $user_id ) {
		$country = Factory::get_instance( Ajax::class )->get_string_parameter( 'country' );
		$city    = Factory::get_instance( Ajax::class )->get_string_parameter( 'city' );
		$company = Factory::get_instance( Ajax::class )->get_string_parameter( 'company' );

		if ( $country ) {
			$country_field = \get_option( self::SETTING_XPROFILE_COUNTRY );
			\xprofile_set_field_data( $country_field, $user_id, $country );
		}
		if ( $city ) {
			$city_field = \get_option( self::SETTING_XPROFILE_CITY );
			\xprofile_set_field_data( $city_field, $user_id, $city );
		}
		if ( $company ) {
			$company_field = \get_option( self::SETTING_XPROFILE_COMPANY );
			\xprofile_set_field_data( $company_field, $user_id, $company );
		}
	}
}
