<?php
/**
 * Filters to Handle Ajax Calls pertaining to Sanbox.
 *
 * @package module/sandbox/library/class-sandboxajaxfilters.php
 */

namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HttpPost;
use ElementalPlugin\Library\UserPreferenceHelpers;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;

/**
 * Class Sandbox Helpers
 * Assistance functions to build sandboxes
 */
class SandBoxAjaxFilters {

	/**
	 * Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 * @return mixed
	 */
	public function sandbox_ajax_handler() {
		$response = array();
		check_ajax_referer( 'elemental_sandbox', 'security', false );
		$response = \apply_filters( 'elemental_sandbox_ajax_response', $response );

		return \wp_send_json( $response );

	}

	/**
	 * Ajax Handler for Sort Tabs
	 *
	 * @param array $response - the Response Object for Ajax return to Frontend.
	 * @return array - $response - the Response Object for Ajax return to Frontend
	 */
	public function ajax_tab_sort( array $response ) {
		/*
		* Update Sandbox section.
		*
		*/

		$set_value      = Factory::get_instance( Ajax::class )->get_string_parameter( 'value' );
		$pathway_record = Factory::get_instance( Ajax::class )->get_integer_parameter( 'level' );
		$action_taken   = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$user           = Factory::get_instance( Ajax::class )->get_string_parameter( 'user' );
		$levels         = Factory::get_instance( Ajax::class )->get_string_parameter( 'levels' );

		if ( 'tab_sort' === $action_taken ) {
			if ( $user ) {
				$user_id = Factory::get_instance( Encryption::class )->decrypt_string( $user );
			}
			if ( $levels && $user ) {
				$pathway_order            = \explode( ',', $levels );

				$place_index = 1;
				foreach ( $pathway_order as $pathway ) {
					$update_user_preference = Factory::get_instance( UserPreferenceHelpers::class )->update_user_preference_object_from_ajax( intval( $user_id ), intval( $pathway ), $place_index );
					//$update                 = Factory::get_instance( SandBoxDao::class )->update_by_field( $place_index, 'column_priority', intval( $pathway ) );
					$place_index++;
				}
			}

			$response['feedback'] = \esc_html__( 'Success', 'elementalplugin' ) . $user_id;

			return $response;
		}

		/*
		* Update Sandbox section.
		*
		*/
		if ( 'update_sandbox' === $action_taken ) {
			$field = Factory::get_instance( Ajax::class )->get_string_parameter( 'field' );
			if ( $field ) {
				$set_field = Factory::get_instance( Ajax::class )->get_decrypted_parameter( 'field' );
			}
			if ( 'enabled' === $set_field || 'admin_enforced' === $set_field ) {
				$set_value = Factory::get_instance( HttpPost::class )->get_control_checkbox( 'checkbox' );
			}
			if ( 'owner_user_name' === $set_field || 'column_priority' === $set_field ) {
				$set_value = \intval( $set_value );
			}

			$update = Factory::get_instance( SandBoxDao::class )->update_by_field( $set_value, $set_field, $pathway_record );

			$response['feedback'] = \esc_html__( 'Success ', 'elementalplugin' );

			return \wp_send_json( $response );
		}
		return $response;
	}

	/**
	 * Ajax Handler for Sort Tabs
	 *
	 * @param array $response - the Response Object for Ajax return to Frontend.
	 * @return array - $response - the Response Object for Ajax return to Frontend
	 */
	public function ajax_login_process( array $response ) {
		/*
		* Update Sandbox section.
		*
		*/

		$set_value      = Factory::get_instance( Ajax::class )->get_string_parameter( 'value' );
		$pathway_record = Factory::get_instance( Ajax::class )->get_integer_parameter( 'level' );
		$action_taken   = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$user           = Factory::get_instance( Ajax::class )->get_string_parameter( 'user' );
		$levels         = Factory::get_instance( Ajax::class )->get_string_parameter( 'levels' );

		if ( 'login' === $action_taken ) {
			$response['feedback'] = 'Login';
		}


			return $response;
		}
}


