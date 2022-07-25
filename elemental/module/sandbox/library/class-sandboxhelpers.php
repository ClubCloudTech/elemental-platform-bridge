<?php
/**
 * Helper Functions for Sandboxes.
 *
 * @package module/sandbox/dao/class-sandboxhelpers.php
 */

namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;
use ElementalPlugin\Module\Sandbox\Entity\SandboxEntity;

/**
 * Class Sandbox Helpers
 * Assistance functions to build sandboxes
 */
class SandBoxHelpers {

	/**
	 * Get the list of Sandbox Rooms
	 *
	 * @param int $record_id - the record ID.
	 * @return array
	 */
	public function get_sandbox_rooms( int $record_id = null ) :array {

		if ( $record_id ) {
			$sandbox_record = array();
			\array_push( $sandbox_record, $record_id );
		} else {
			$sandbox_record = Factory::get_instance( SandBoxDao::class )->get_all_entities();
		}

		$return_array = array();
		foreach ( $sandbox_record as $value ) {

				$record_data                       = Factory::get_instance( SandBoxDao::class )->get_by_id( intval( $value ) );
				$record_array                      = array();
				$record_array['record_id']         = $value;
				$record_array['tab_name']          = $record_data->get_tab_name();
				$record_array['user_name_prepend'] = $record_data->get_user_name_prepend();
				$record_array['destination_url']   = $record_data->get_destination_url();
				$record_array['customfield1']      = $record_data->get_customfield1();
				$record_array['customfield2']      = $record_data->get_customfield2();
				$record_array['enabled']           = $record_data->is_enabled();
				$record_array['private_key']       = $record_data->get_private_key();
				\array_push( $return_array, $record_array );
		}
		return $return_array;
	}

	/**
	 * Get the list of Sandbox Rooms
	 *
	 * @param SandboxEntity $record_id - the record ID.
	 * @return ?string
	 */
	public function create_sandbox_url( SandboxEntity $sandbox_object ) :?string {


	}

}
