<?php
/**
 * Data Access Object for Files Sync
 *
 * @package elemental/module/files/dao/class-filesyncdao.php
 */

namespace ElementalPlugin\Module\Files\DAO;

use ElementalPlugin\Entity\FileSync;
use ElementalPlugin\Module\Files\Files;

/**
 * Class RoomSyncDAO
 */
class FileSyncDao {

	const TABLE_NAME_FILE_SYNC = 'elemental_file_sync';

	/**
	 * Get the table name for Room Presence Table DAO.
	 *
	 * @return string
	 */
	private function get_room_presence_table_name(): string {
		global $wpdb;

		return $wpdb->prefix . self::TABLE_NAME_FILE_SYNC;
	}

	/**
	 * Create a cache key
	 *
	 * @param string $user_id   The user id.
	 * @param string $application_name The room name.
	 *
	 * @return string
	 */
	private function create_cache_key( string $user_id, string $application_name ): string {
		return 'user_id:' . $user_id . ':application_name:' . $application_name;
	}
		/**
	 * Database Restore and Update
	 *
	 * @param string $db_error_message   The Error Message.
	 *
	 * @return bool
	 */
	private function repair_update_database( string $db_error_message = null ): bool {

		// Case Table Delete.
		$table_message = $this->get_room_presence_table_name() . '\' doesn\'t exist';
		if ( strpos( $db_error_message, $table_message ) !== false ) {
			// Recreate Table.
			$this->install_room_presence_table();

			return true;
		}
	}
		/**
	 * Install SessionState Sync Config Table.
	 *
	 * @return bool
	 */
	public function install_room_presence_table(): bool {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = $wpdb->prefix . $this->get_room_presence_table_name();

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`user_id` BIGINT UNSIGNED NULL,
			`application_name` VARCHAR(255) NOT NULL,
			`timestamp` BIGINT UNSIGNED NULL,
			`user_picture_url` VARCHAR(255) NULL,
			`user_display_name` VARCHAR(255) NULL,
			`user_picture_path` VARCHAR(255) NULL,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		return \maybe_create_table( $table_name, $sql_create );
	}



	/**
	 * Get a User-File Object from the database
	 *
	 * @param string $user_id   The Cart id.
	 * @param string $application_name The room name.
	 *
	 * @return FileSync|null
	 */
	public function get_by_id_sync_table( string $user_id, string $application_name ): ?FileSync {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_id,
			$application_name
		);

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( $result ) {
			return FileSync::from_json( $result );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'
				SELECT 
			       user_id, 
			       application_name,
			       timestamp,
				   record_id,
				   user_picture_url,
				   user_display_name,
				   user_picture_path
				   
				FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_room_presence_table_name() . '
				WHERE user_id = %s AND application_name = %s;
			',
				array(
					$user_id,
					$application_name,
				)
			)
		);

		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
		}

		$result = null;

		if ( $row ) {
			$result = new FileSync(
				$row->user_id,
				$row->application_name,
				$row->timestamp,
				$row->id,
				$row->user_picture_url,
				$row->user_display_name,
				$row->user_picture_path
			);
			wp_cache_set( $cache_key, __METHOD__, $result->to_json() );
		} else {
			wp_cache_set( $cache_key, __METHOD__, null );
		}

		return $result;
	}
	/**
	 * Create a User Picture and Name Storage record in DB.
	 *
	 * @param string $user_id   The Cart id.
	 *
	 * @return FileSync|null
	 */
	public function create_new_user_storage_record( string $user_id = null ): ?FileSync {
		if ( ! $user_id ) {
			$user_id = $this->get_user_session();
		}
		$application_name = Files::APPLICATION_NAME;
		$current_object   = new FileSync(
			$user_id,
			$application_name,
			/*phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested*/
			current_time( 'timestamp' ),
			null,
			null,
			null,
			null
		);
		$success = $this->create( $current_object );
		if ( $success ) {
			return $current_object;
		} else {
			return null;
		}
	}

	/**
	 * Save a File Sync Event into the database
	 *
	 * @param FileSync $filesyncobj The video preference to save.
	 *
	 * @return FileSync|null
	 */
	public function create( FileSync $filesyncobj ): ?FileSync {
		global $wpdb;

		$user_id          = $filesyncobj->get_user_id();
		$application_name = $filesyncobj->get_application_name();

		// Check Record Doesn't already exist (update not create if it does).

		$check = $this->get_by_id_sync_table( $user_id, $application_name );

		if ( $check ) {
			return $this->update( $filesyncobj );
		}

		$cache_key = $this->create_cache_key(
			$user_id,
			$application_name
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->insert(
			$this->get_room_presence_table_name(),
			array(
				'user_id'           => $filesyncobj->get_user_id(),
				'application_name'  => $filesyncobj->get_application_name(),
				'timestamp'         => $filesyncobj->get_timestamp(),
				'user_picture_url'  => $filesyncobj->get_user_picture_url(),
				'user_display_name' => $filesyncobj->get_user_display_name(),
				'user_picture_path' => $filesyncobj->get_user_picture_path(),
			)
		);

		$filesyncobj->set_id( $wpdb->insert_id );

		\wp_cache_set(
			$cache_key,
			$filesyncobj->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id_sync_table',
				)
			)
		);
		\wp_cache_delete(
			$filesyncobj->get_user_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_user_id',
				)
			)
		);

		return $filesyncobj;
	}
		/**
	 * Update a Cart Object into the database
	 *
	 * @param FileSync $filesyncobj The updated Cart Object.
	 *
	 * @return FileSync|null
	 * @throws \Exception When failing to update.
	 */
	public function update( FileSync $filesyncobj ): ?FileSync {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$filesyncobj->get_user_id(),
			$filesyncobj->get_application_name()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$this->get_room_presence_table_name(),
			array(
				'user_id'           => $filesyncobj->get_user_id(),
				'application_name'  => $filesyncobj->get_application_name(),
				'timestamp'         => $filesyncobj->get_timestamp(),
				'user_picture_url'  => $filesyncobj->get_user_picture_url(),
				'user_display_name' => $filesyncobj->get_user_display_name(),
				'user_picture_path' => $filesyncobj->get_user_picture_path(),
			),
			array(
				'user_id'          => $filesyncobj->get_user_id(),
				'application_name' => $filesyncobj->get_application_name(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$filesyncobj->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id_sync_table',
				)
			)
		);
		\wp_cache_delete(
			$filesyncobj->get_user_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_user_id',
				)
			)
		);

		return $filesyncobj;
	}
	/**
	 * Get Session ID for Signed Out Users.
	 *
	 * @param ?int $user_id The user id. (optional).
	 *
	 * @return string the session ID of the user.
	 */
	public function get_user_session( int $user_id = null ): string {

		if ( $user_id ) {
			return wp_hash( $user_id );

		} elseif ( is_user_logged_in() ) {

			return wp_hash( get_current_user_id() );
		} else {

			// Get php session hash.
			if ( ! session_id() ) {
				session_start();
			}
			return session_id();
		}
	}

}
