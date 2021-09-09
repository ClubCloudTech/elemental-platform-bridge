<?php
/**
 * Data Access Object for controlling Room Mapping Database Entries
 *
 * @package MyVideoRoomExtrasPlugin\DAO
 */

namespace MyVideoRoomExtrasPlugin\DAO;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\Dao\RoomMap;


/**
 * Class UserVideoPreference
 */
class ModuleConfig {

	const TABLE_NAME = SiteDefaults::TABLE_NAME_MODULE_CONFIG;


	/**
	 * Get a User Video Preference from the database
	 *
	 * @param int    $user_id   The user id.
	 * @param string $room_name The room name.Mandatory
	 *
	 * @return Post ID or Null
	 */
	public function read( string $room_name ) {
		global $wpdb;
		$raw_sql        = '
				SELECT post_id
				FROM ' . $wpdb->prefix . self::TABLE_NAME . '
				WHERE room_name = %s
			';
		$prepared_query = $wpdb->prepare(
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$raw_sql,
			array(
				$room_name,
			)
		);

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
		$row = $wpdb->get_row( $prepared_query );
		if ( $row ) {
			$result = $row->post_id;
		}
		return $result;
	}

	/**
	 * Register a given room in the Database, and ensure it does not already exist
	 *
	 * @return DB Result Code or False s
	 */

	public function register_module_in_db( string $module_name, int $module_id ) {
		global $wpdb;
		// empty input exit
		if ( ! $module_name || ! $module_id ) {
			return 'Invalid Entry need Module ID and Name';
		}

		// Create Post
		$result = $wpdb->insert(
			$wpdb->prefix . self::TABLE_NAME,
			array(
				'module_name' => $module_name,
				'module_id'   => $module_id,

			)
		);

		return $result;
	}

	/**
	 * Update Enabled Module Status in Database
	 */

	public function update_enabled_status( int $module_id, bool $module_enabled ) {
		 global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		// First Check Database for Room and Post ID - return No if blank

		if ( ! $module_id ) {

			return false;
		}

		$raw_sql        = '
				UPDATE ' . $wpdb->prefix . self::TABLE_NAME . '
				SET module_enabled = %d
				WHERE module_id = %d
			';
		$prepared_query = $wpdb->prepare(
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$raw_sql,
			array(
				$module_enabled,
				$module_id,

			)
		);

		$result = $wpdb->query( $prepared_query );

		return $result;

	}

	/**
	 * Read Enabled Module Status in Database
	 */
	public function read_enabled_status( int $module_id ) {
		 global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		// First Check Database for Room and Post ID - return No if blank.

		if ( ! $module_id ) {
			return false;
		}

		$raw_sql        = '
				SELECT module_enabled
				FROM ' . $wpdb->prefix . self::TABLE_NAME . '
				WHERE module_id = %s
			';
		$prepared_query = $wpdb->prepare(
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$raw_sql,
			array(
				$module_id,

			)
		);

		$row    = $wpdb->query( $prepared_query );
		$result = null;

		$row = $wpdb->get_row( $prepared_query );
		if ( $row ) {
			$result = $row->module_enabled;
		}

		return $result;

	}






	/**
	 * Delete a Room Record Mapping to a URL in Database
	 *
	 *  This function will delete the room name in the database with the parameter
	 *
	 * @param  string Post_ID , string room_name - both needed
	 * @return Database updated result or False
	 */
	public function delete_room_mapping( string $room_name ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		// empty input exit
		if ( ! $room_name ) {
			return false;
		}

		$raw_sql        = '
				DELETE FROM ' . $wpdb->prefix . self::TABLE_NAME . '
				WHERE room_name = %s
			';
		$prepared_query = $wpdb->prepare(
	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$raw_sql,
			array(
				$room_name,

			)
		);

		$result = $wpdb->query( $prepared_query );

		return null;
	}


	/**
	 * Check a Page Exists
	 *
	 * @return String  Yes, No, Orphan (database exists but page deleted ).
	 */

	public function check_page_exists( string $room_name ) {
		global $wpdb;
		// empty input exit.
		if ( ! $room_name ) {
			return false;
		}
		// First Check Database for Room and Post ID - return No if blank.

		$post_id_check = Factory::get_instance( RoomMap::class )->read( $room_name );
		if ( ! $post_id_check ) {
			return 'No';
		}

		// Second Check Post Actually Exists in WP still (user hasn't deleted page).
		$post_object = get_post( $post_id_check );
		if ( ! $post_object ) {
			return 'Orphan';
		} else {
			return 'Yes';
		}

	}

	/**
	 * This function renders the activate/deactivate button for a give module
	 * Used only in admin pages of plugin
	 *
	 * @return String  Button with link
	 */


	public function module_activation_button( int $module_id ) {
		// Listening for Input.
		$module_status    = $params['action'] ?? htmlspecialchars( $_GET['action'] ?? '' );
		$module_id_by_url = $params['moduleid'] ?? htmlspecialchars( $_GET['moduleid'] ?? '' );

		// Replace Module ID from URL post if one exists.
		if ( $module_id_by_url ) {
			$module_id = $module_id_by_url;
		}

		// Check Modules State.
		if ( $module_status ) {
			// Case Disable State Change
			if ( 'disable' === $module_status ) {
				Factory::get_instance( self::class )->update_enabled_status( $module_id, false );

			} elseif ( 'enable' === $module_status ) {
				Factory::get_instance( self::class )->update_enabled_status( $module_id, true );
			}
		}

		// Processing Link for Button.

		// Check enabled status to see which button to render.
		$is_module_enabled = Factory::get_instance( self::class )->read_enabled_status( $module_id );
		// Check if is sub tab to mark as such to strip out extra data in URL when called back.

		$sub_tab_tag = '&subtab=1';

		// Build URL.
		$current_url = home_url( $_SERVER['REQUEST_URI'] );
		if ( ! $is_module_enabled ) {

			$current_url .= '&action=enable&moduleid=' . $module_id;
			$output_link  = '<div style= "display: flex; justify-content: space-between; width: 50%;"> <a href="' . $current_url . '" class="button button-primary" style="background-color:red;" >Disabled</a><a href="' . $current_url . $sub_tab_tag . '" class="button button-primary" >Enable Module</a></div>';
			echo $output_link;
			return false;

		} else {
			$current_url .= '&action=disable&moduleid=' . $module_id;
			$output_link  = '<div style= "display: flex;	justify-content: space-between; width: 50%;"> <a href="' . $current_url . '" class="button button-primary" style="background-color:green;" >Enabled</a><a href="' . $current_url . $sub_tab_tag . '" class="button button-primary"  >Disable Module</a></div>';

		}

		echo $output_link;
		return true;
	}


	public function sub_module_activation_button( int $module_id ) {

		// Listening for Input.
		$module_status    = $params['subaction'] ?? htmlspecialchars( $_GET['subaction'] ?? '' );
		$module_id_by_url = $params['submoduleid'] ?? htmlspecialchars( $_GET['submoduleid'] ?? '' );

		// Replace Module ID from URL post if one exists.
		if ( $module_id_by_url ) {
			$module_id = $module_id_by_url;
		}

		// Check Modules State.
		if ( $module_status ) {
			// Case Disable State Change.
			if ( 'disable' === $module_status ) {
				Factory::get_instance( self::class )->update_enabled_status( $module_id, false );

			} elseif ( 'enable' === $module_status ) {
				Factory::get_instance( self::class )->update_enabled_status( $module_id, true );
			}
		}

		// Processing Link for Button.
		if ( $module_id_by_url && $module_id_by_url ) {
			$original_url = home_url( $_SERVER['REQUEST_URI'] );
			// Strip out anything after &action which is done by plugin.
			$original_url = substr( $original_url, 0, strpos( $original_url, '&subaction' ) );
			wp_redirect( $original_url );
			exit();
			return true;
		}

		// Check enabled status to see which button to render.
					$is_module_enabled = Factory::get_instance( self::class )->read_enabled_status( $module_id );
					// Check if is sub tab to mark as such to strip out extra data in URL when called back.

					$sub_tab_tag = '&subtab=1';

					// Build URL.
					$current_url = home_url( $_SERVER['REQUEST_URI'] );
		if ( ! $is_module_enabled ) {

			$current_url .= '&subaction=enable&submoduleid=' . $module_id;
			$output_link  = '<div id="ccbutton-array" style="display: flex;	justify-content: space-between; width: 50%;">
			<a href="' . $current_url . $sub_tab_tag . '" class="button button-primary" style="background-color:red" >Disabled</a>
			<a href="' . $current_url . $sub_tab_tag . '" class="button button-primary" >Enable Module</a>
			</div>';
			echo $output_link;
			return false;

		} else {
			$current_url .= '&subaction=disable&submoduleid=' . $module_id;
			$output_link  = '<div id="ccbutton-array" style= "display: flex;	justify-content: space-between; width: 50%;">
			<a href="' . $current_url . $sub_tab_tag . '" class="button button-primary" style="background-color:green" >Enabled</a>
			<a href="' . $current_url . $sub_tab_tag . '" class="button button-primary"  >Disable Module</a>
			</div>';
		}

					echo $output_link;
						// Exit if both action and module were in URL (as it can only happen when sub features were called ) - Need to strip out action parameters and refresh page to allow child modules to not display incorrectly.
					return true;

	}



	/**
	 * This function renders the activate/deactivate button for a give module
	 * Used only in admin pages of plugin
	 *
	 * @return String  Button with link
	 */


	public function module_activation_status( int $module_id ) {

		// Check enabled status.
		$is_module_enabled = Factory::get_instance( self::class )->read_enabled_status( $module_id );

		if ( $is_module_enabled ) {
			return true;
		}
		return false;

	}



}//end class
