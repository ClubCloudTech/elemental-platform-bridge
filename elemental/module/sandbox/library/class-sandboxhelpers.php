<?php
/**
 * Helper Functions for Sandboxes.
 *
 * @package module/sandbox/dao/class-sandboxhelpers.php
 */

namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;
use ElementalPlugin\Module\Sandbox\Entity\SandboxEntity;
use ElementalPlugin\Module\Sandbox\Encrypt\SandboxEncrypt;

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
	 * @param SandboxEntity $sandbox_object - the Object.
	 * @return ?string
	 */
	public function create_sandbox_iframe( SandboxEntity $sandbox_object = null ) :?string {

		if ( ! $sandbox_object->is_enabled() ) {
			return \esc_html__( 'Disabled in Database', 'elementalplugin' );
		}
		$current_user = wp_get_current_user();

		$user_email = $current_user->user_email;


		$base_url      = $sandbox_object->get_destination_url();
		$api_path      = $sandbox_object->get_user_name_prepend();
		$custom_field1 = $sandbox_object->get_customfield1();
		$custom_field2 = $sandbox_object->get_customfield2();
		$public_hash   = $sandbox_object->get_private_key();
		$email_hash = null;

		if ( $custom_field1 ) {
			$field_1 = $custom_field1;
		}
		if ( $custom_field2 ) {
			$field_2 = '?' . $custom_field2;
		}

		$encrypted_user =  Factory::get_instance(SandboxEncrypt::class)->rsaEncrypt($user_email, $public_hash);
		$encoded_url = "$base_url" . $api_path . "?userid=" . urlencode($encrypted_user) . $field_1 . $field_2;
		return '<iframe style="width:100%;height:700px;" src="' . $encoded_url . '"></iframe>';
	}

	/**
	 * Construct All Tabs for Sandbox.
	 *
	 * @return array - outbound menu.
	 */
	public function render_all_tabs(): array {
		$tab_objects = array();
		// Get all Tabs
		$all_record_ids = Factory::get_instance( SandBoxDao::class )->get_all_entities();

		// Send each Tab item to get Menu item from it.

		foreach ( $all_record_ids as $id ) {

			$object    = Factory::get_instance( SandBoxDao::class )->get_by_id( $id );
			$menu_item = $this->prepare_sandbox_tab( $object );
			\array_push( $tab_objects, $menu_item );
		}

		// Return the Array.

		return $tab_objects;
	}


	/**
	 * Render Content Search Results Tab.
	 *
	 * @param SandboxEntity $sandbox_object - the Sandbox Object to Convert to Menu Item.
	 *
	 * @return array - outbound menu.
	 */
	private function prepare_sandbox_tab( SandboxEntity $sandbox_object ): MenuTabDisplay {

		$slug      = preg_replace( '/[^a-zA-Z0-9]+/', '', $sandbox_object->get_tab_name() );
		$host_menu = new MenuTabDisplay(
			$sandbox_object->get_tab_name(),
			$slug . '-' . $sandbox_object->get_record_id(),
			fn() => $this->create_sandbox_iframe( $sandbox_object ),
			$slug . '1-' . $sandbox_object->get_record_id()
		);

		return $host_menu;
	}

}
