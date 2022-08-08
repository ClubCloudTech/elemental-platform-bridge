<?php
/**
 * Helper Functions for Sandboxes.
 *
 * @package module/sandbox/dao/class-sandboxhelpers.php
 */

namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\DAO\UserPreferenceDAO;
use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\TabHelper;
use ElementalPlugin\Module\Menus\ElementalMenus;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;
use ElementalPlugin\Module\Sandbox\Entity\SandboxEntity;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

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
				$record_array['column_priority']   = $record_data->get_column_priority();
				$record_array['record_id']         = $value;
				$record_array['tab_name']          = $record_data->get_tab_name();
				$record_array['user_name_prepend'] = $record_data->get_user_name_prepend();
				$record_array['destination_url']   = $record_data->get_destination_url();
				$record_array['customfield1']      = $record_data->get_customfield1();
				$record_array['customfield2']      = $record_data->get_customfield2();
				$record_array['enabled']           = $record_data->is_enabled();
				$record_array['private_key']       = $record_data->get_private_key();
				$record_array['owner_user_id']     = $record_data->get_owner_user_id();
				$record_array['admin_enforced']    = $record_data->is_admin_enforced();
				$record_array['owner_user_name']   = Factory::get_instance( ElementalMenus::class )->render_header_logo_shortcode(
					array(
						'type'    => 'text',
						'user_id' => $record_array['owner_user_id'],
					)
				);
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
			return \esc_html__( 'This Pathway has been disabled in the control panel', 'elementalplugin' );
		}

		$base_url      = $sandbox_object->get_destination_url();
		$api_path      = $sandbox_object->get_user_name_prepend();
		$custom_field1 = $sandbox_object->get_customfield1();
		$custom_field2 = $sandbox_object->get_customfield2();
		$public_hash   = $sandbox_object->get_private_key();
		$email_hash    = null;

		if ( $custom_field1 ) {
			$field_1 = $custom_field1;
		}
		if ( $custom_field2 ) {
			$field_2 = '?' . $custom_field2;
		}

		return '<iframe style="width:100%;height:900px;" src="' . $base_url . '/' . $api_path . '?userid=' . $email_hash . $field_1 . $field_2 . '"></iframe>';

	}

	/**
	 * Construct All Tabs for Sandbox. Used in display in frontend.
	 *
	 * @return array - outbound menu.
	 */
	public function render_all_tabs(): array {
		$tab_objects = array();

		// Get Parent ID of Organisation to search from.
		$user_id   = \get_current_user_id();
		$parent_id = Factory::get_instance( WCFMTools::class )->staff_to_parent( $user_id );

		// Get all Tabs for Parent Org plus mandatory tabs.
		if ( $parent_id ) {
			$all_record_ids = Factory::get_instance( SandBoxDao::class )->get_entities_by_id( $parent_id );
		}
		// Check Setup Config.
		$user_config_record_ids = Factory::get_instance( UserPreferenceDAO::class )->get_by_pathway_id( $user_id );

		if ( count( $all_record_ids ) >= count( $user_config_record_ids ) ) {
			$sortable_ids = $user_config_record_ids;
			$sort         = false;
		} else {
			$sortable_ids = $all_record_ids;
			$sort         = true;

		}

		// Send each Tab item to get Menu item from it.
		foreach ( $sortable_ids as $id ) {

			$object = Factory::get_instance( SandBoxDao::class )->get_by_id( $id );
			if ( $object ) {
				$menu_item = $this->prepare_sandbox_tab( $object );
				\array_push( $tab_objects, $menu_item );
			} else {
				echo ' No Oject found';
			}
		}
		// Return the Array, and Sort if unset by User preference.
		if ( $sort ) {
			return Factory::get_instance( TabHelper::class )->tab_priority_sort( $tab_objects );
		} else {
			return $tab_objects;
		}
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
			$sandbox_object->get_record_id(),
			strval( $sandbox_object->get_column_priority() ),
		);

		return $host_menu;
	}

	/**
	 * Sort Global Directory Tabs.
	 *
	 * @param array $inputs - the tabs to sort.
	 * @return array - a sorted array.
	 */
	public function sort_sandbox_rooms( array $inputs ) {
		usort(
			$inputs,
			function (
				$a,
				$b ) {
				$a_val = (int) $a['column_priority'];
				$b_val = (int) $b['column_priority'];

				if ( $a_val > $b_val ) {
					return 1;
				}
				if ( $a_val < $b_val ) {
					return -1;
				}
				return 0;
			}
		);

		return $inputs;
	}

}
