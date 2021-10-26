<?php
/**
 * Data Access Object for controlling Room Mapping Database Entries
 *
 * @package ElementalPlugin\DAO
 */

namespace ElementalPlugin\DAO;

/**
 * Class UserVideoPreference
 */
class RoomMap {


	const TABLE_NAME = \ElementalPlugin\Core\SiteDefaults::TABLE_NAME_ROOM_MAP;



	/**
	 * Get a User Video Preference from the database.
	 *
	 * @param  string $room_name inbound room from user.
	 * @return string (db entry)
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
			return $row->post_id;
		}

		return null;
	}

	/**
	 * Register a given room in the Database, and ensure it does not already exist
	 *
	 * @param UserVideoPreferenceEntity $user_video_preference The updated user video preference.
	 *
	 * @return DB Result Code or False s
	 */

	public function register_room_in_db( string $room_name, int $post_id ) {
		global $wpdb;
		// empty input exit
		if ( ! $room_name || ! $post_id ) {
			return 'Room Name or PostID Blank';
		}

		// Create Post
		$result = $wpdb->insert(
			$wpdb->prefix . self::TABLE_NAME,
			array(
				'room_name' => $room_name,
				'post_id'   => $post_id,
			)
		);
		return $result;
	}

	/**
	 * Update Room Post ID in Database
	 *
	 *  This plugin will update the room name in the database with the parameter
	 *
	 * @param  string Post_ID , string room_name - both needed
	 * @return Database updated result or False
	 */

	public function update_room_post_id( string $post_id, string $room_name ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		// empty input exit
		if ( ! $post_id || ! $room_name ) {
			return false;
		}
		// First Check Database for Room and Post ID - return No if blank

		$raw_sql        = '
				UPDATE ' . $wpdb->prefix . self::TABLE_NAME . '
				SET post_id = %s
				WHERE room_name = %s
			';
		$prepared_query = $wpdb->prepare(
       // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$raw_sql,
			array(
				$room_name,
				$post_id,
			)
		);

		$result = $wpdb->query( $prepared_query );

		return null;
	}
	/**
	 * Delete a Room Record in Database.
	 *
	 *  This function will delete the room name in the database with the parameter.
	 *
	 * @param  string Post_ID , string room_name - both needed
	 * @return Database updated result or False
	 */
	public function delete_room_mapping( string $room_name ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::TABLE_NAME;
		// empty input exit.
		if ( ! $room_name ) {
			return false;
		}

		$raw_sql        = '
				DELETE FROM ' . $wpdb->prefix . self::TABLE_NAME . '
				WHERE room_name = %s
			';
		$prepared_query = $wpdb->prepare(
       // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared.
			$raw_sql,
			array(
				$room_name,

			)
		);

		$result = $wpdb->query( $prepared_query );

		return null;
	}



	/**
	 * Register a given room in the Database, and ensure it does not already exist
	 *
	 * @param UserVideoPreferenceEntity $user_video_preference The updated user video preference.
	 *
	 * @return String  Yes, No, Orphan (database exists but page deleted)
	 */

	public function check_page_exists( string $room_name ) {
		global $wpdb;
		// empty input exit
		if ( ! $room_name ) {
			return false;
		}
		// First Check Database for Room and Post ID - return No if blank

		$post_id_check = \ElementalPlugin\Factory::get_instance( \ElementalPlugin\Dao\RoomMap::class )->read( $room_name );
		if ( ! $post_id_check ) {
			return 'No';
		}

		// Second Check Post Actually Exists in WP still (user hasn't deleted page)
		$post_object = get_post( $post_id_check );
		if ( ! $post_object ) {
			return 'Orphan';
		} else {
			return 'Yes';
		}

	}


}
