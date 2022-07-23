<?php
/**
 * Utilities for BuddyPress
 *
 * @package xprofile/library/class-xprofiletools.php
 */

namespace ElementalPlugin\Module\BuddyPress\Library;

/**
 * Class BuddyPressTools- utilities for BuddyPress Integration
 */
class XprofileTools {


	/**
	 * Runtime Shortcodes
	 */
	public function init() {

	}

	/**
	 * Check if a field group exists.
	 *
	 * @param string $name - group name.
	 * @param bool   $return_id - Return ID rather than true or false.
	 *
	 * @return mixed
	 */
	public function does_bp_xprofile_group_exist( string $name, bool $return_id = null ) {
		$bp = buddypress();
		global $wpdb;

		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$bp->profile->table_name_groups} WHERE name = %s",
				$name
			)
		);

		if ( $return_id && $exists ) {
			return $exists;

		} elseif ( $exists ) {
			return true;
		}
		return false;
	}
	/**
	 * Get Group Fields.
	 *
	 * @param string $name - group name.
	 *
	 * @return bool
	 */
	public function get_bp_xprofile_group_fields( string $name ) {
		$bp = buddypress();
		global $wpdb;

		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT id FROM {$bp->profile->table_name_groups} WHERE name = %s",
				$name
			)
		);
		if ( ! $exists || ! \function_exists( 'bp_xprofile_get_groups' ) ) {
			return null;
		}
		$groups = bp_xprofile_get_groups(
			array(
				'fetch_fields'     => true,
				'profile_group_id' => $exists,
			)
		);
		if ( ! $groups ) {
			return null;
		} else {
			$out_array = array();
		}

		foreach ( $groups[0]->fields as $field ) {
			\array_push( $out_array, $field->name );
		}
		if ( count( $out_array ) >= 1 ) {
			return $out_array;
		} else {
			return null;
		}
	}

	/**
	 * Create Xprofile Group.
	 * Will Create an Xprofile Field Group in Users Profile Fields if group name doesn't already exist.
	 *
	 * @param string $group_name - group name.
	 * @param string $description - group description.
	 *
	 * @return bool
	 */
	public function create_xprofile_group( string $group_name, string $description = null ) {
		$group_exists_check = $this->does_bp_xprofile_group_exist( $group_name, true );
		if ( $group_exists_check ) {
			return $group_exists_check;
		}
		if ( ! \function_exists( 'xprofile_insert_field_group' ) ) {
			return null;
		}
		$group_id = xprofile_insert_field_group(
			array(
				'name'        => $group_name,
				'description' => $description,
				'can_delete'  => true,
			)
		);
		return $group_id;
	}
}
