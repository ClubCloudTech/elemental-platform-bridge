<?php
/**
 * Connects MyVideoRoom to xprofile
 *
 * @package ElementalPlugin\XProfile
 */

namespace ElementalPlugin\XProfile;

use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Class XProfile
 */
class XProfile {

	const SETTING_XPROFILE_COUNTRY = 'elemental-xprofile-country';

	/**
	 * Init Function
	 *
	 * @return void
	 */
	public function init() {
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'process_update_filter' ), 10, 2 );
	}

	public function get_countries() {
		$countryfield = \get_option( Xprofile::SETTING_XPROFILE_COUNTRY );
	}

	/**
	 * Process Update Result.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function process_update_filter( array $response ): array {
		$setting_xprofile_field = Factory::get_instance( Ajax::class )->get_string_parameter( Xprofile::SETTING_XPROFILE_COUNTRY );
		\update_option( Xprofile::SETTING_XPROFILE_COUNTRY, intval( $setting_xprofile_field ) );
		$response['feedback'] = \esc_html__( 'Settings Saved', 'myvideoroom' );
		return $response;
	}

}
