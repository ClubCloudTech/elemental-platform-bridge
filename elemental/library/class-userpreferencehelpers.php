<?php
/**
 * Class User Preference Helpers
 *
 * @package library/class-userpreferencehelpers.php
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\DAO\UserPreferenceDAO;
use ElementalPlugin\Entity\UserPreference;

/**
 * Class User Preference Helpers
 * Contains assistance controllers to manage User Preference Transactions.
 */
class UserPreferenceHelpers {

	/**
	 * Update User Preference Record
	 *
	 * @param int $user_id the user ID.
	 * @param int $pathway_id - the pathway of the Record.
	 * @param int $column_priority - the priority of the tab pathway of the Record.
	 * @return void
	 */
	public function update_user_preference_object_from_ajax( int $user_id, int $pathway_id, int $column_priority ) :void {

		$user_preference_dao = Factory::get_instance( UserPreferenceDAO::class );
		$ajax_field          = Factory::get_instance( Ajax::class );

		$current_user_setting = $user_preference_dao->get_by_id(
			$user_id,
			$pathway_id
		);

		$tab_display_name = $ajax_field->get_string_parameter( 'tab_display_name' );
		$pathway_enabled  = $ajax_field->get_checkbox_parameter( 'pathway_enabled' );
		$destination_url  = $ajax_field->get_string_parameter( 'destination_url' );
		$timestamp        = current_time( 'timestamp' );

		if ( $current_user_setting ) {
			$current_user_setting->set_user_id( $user_id )
				->set_tab_display_name( $tab_display_name )
				->set_column_priority( $column_priority )
				->set_pathway_id( $pathway_id )
				->set_pathway_enabled( $pathway_enabled )
				->set_destination_url_setting( $destination_url )
				->set_timestamp( $timestamp );

			$user_preference_dao->update( $current_user_setting );
		} else {
			$current_user_setting = new UserPreference(
				$user_id,
				$tab_display_name,
				$column_priority,
				$pathway_id,
				$pathway_enabled,
				$destination_url,
				$timestamp
			);
			$user_preference_dao->create( $current_user_setting );
		}
	}

}
