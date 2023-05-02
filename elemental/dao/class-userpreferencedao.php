<?php
/**
 * Data Access Object for User Preferences
 *
 * @package dao/class-userpreferencedao.php
 */

namespace ElementalPlugin\DAO;

use ElementalPlugin\Entity\UserPreference as UserPreferenceEntity;

/**
 * Class UserVideoPreference
 * Manages DB Layer for User Preferences for Video Room Base Settings.
 */
class UserPreferenceDAO {

	const TABLE_NAME = 'elemental_userpreferences';

	/**
	 * Install_user_video_preference_table - this is the main table for all User Room Config
	 *
	 * @return ?string
	 */
	public static function install_user_preference_table(): ?string {
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`user_id` BIGINT NOT NULL,
			`tab_display_name` VARCHAR(255) NOT NULL,
			`column_priority` VARCHAR(255) NULL,
			`pathway_id` INT NOT NULL,
			`pathway_enabled` BOOLEAN,
			`destination_url` VARCHAR(255) NULL,
			`timestamp` BIGINT UNSIGNED NULL,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		$record = \maybe_create_table( $table_name, $sql_create );
		if ( $record ) {
			return $wpdb->last_error;
		} else {
			return null;
		}
	}


	/**
	 * Save a User Preference into the database
	 *
	 * @param UserPreferenceEntity $user_video_preference The video preference to save.
	 *
	 * @return UserPreferenceEntity|null
	 * @throws \Exception When failing to insert, most likely a duplicate key.
	 */
	public function create( UserPreferenceEntity $user_video_preference ): ?UserPreferenceEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_video_preference->get_user_id(),
			$user_video_preference->get_pathway_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'user_id'          => $user_video_preference->get_user_id(),
				'tab_display_name' => $user_video_preference->get_tab_display_name(),
				'column_priority'  => $user_video_preference->get_column_priority(),
				'pathway_id'       => $user_video_preference->get_pathway_id(),
				'pathway_enabled'  => $user_video_preference->is_pathway_enabled(),
				'destination_url'  => $user_video_preference->get_destination_url_setting(),
				'timestamp'        => $user_video_preference->get_timestamp(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$user_video_preference->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id',
				)
			)
		);
		\wp_cache_delete(
			$user_video_preference->get_user_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_pathway_id',
				)
			)
		);

		if ( ! $result ) {
			return null;
		}

		return $user_video_preference;
	}

	/**
	 * Create a cache key
	 *
	 * @param int $user_id   The user id.
	 * @param int $pathway_id The room name.
	 *
	 * @return string
	 */
	private function create_cache_key( int $user_id, int $pathway_id ): string {
		return "user_id:${user_id}:pathway_id:${pathway_id}:1";
	}

	/**
	 * Get the table name for this DAO.
	 *
	 * @return string
	 */
	private function get_table_name(): string {
		global $wpdb;

		return $wpdb->prefix . self::TABLE_NAME;
	}

	/**
	 * Delete a User Preference from the database
	 *
	 * @param UserPreferenceEntity $user_video_preference The user preference to delete.
	 *
	 * @return null
	 * @throws \Exception When failing to delete.
	 */
	public function delete( UserPreferenceEntity $user_video_preference ) {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_video_preference->get_user_id(),
			$user_video_preference->get_pathway_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->delete(
			$this->get_table_name(),
			array(
				'user_id'    => $user_video_preference->get_user_id(),
				'pathway_id' => $user_video_preference->get_pathway_id(),
			)
		);

		\wp_cache_delete( $cache_key, implode( '::', array( __CLASS__, 'get_by_id' ) ) );
		\wp_cache_delete(
			$user_video_preference->get_user_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_pathway_id',
				)
			)
		);

		return null;
	}

	/**
	 * Update Database Post ID.
	 * This function updates the Post ID of the User Entity Table so that new pages can pick up settings of deleted pages.
	 *
	 * @param int $new_user_id New post_id to update preference table with.
	 * @param int $old_user_id The old post that was deleted.
	 *
	 * @return bool
	 */
	public function update_user_id( int $new_user_id, int $old_user_id ): bool {
		$preferences = $this->get_by_pathway_id( $old_user_id );

		foreach ( $preferences as $preference ) {
			$preference->set_user_id( $new_user_id );
			$this->update( $preference );
		}

		return true;
	}

	/**
	 * Get a User Preference from the database
	 *
	 * @param int $user_id The user id.
	 *
	 * @return UserPreferenceEntity[]
	 */
	public function get_by_pathway_id( int $user_id ): array {
		global $wpdb;

		$tab_display_names = \wp_cache_get( $user_id, __METHOD__ );

		if ( false === $tab_display_names ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$tab_display_names = $wpdb->get_col(
				$wpdb->prepare(
					'
						SELECT pathway_id
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE user_id = %d
						ORDER BY column_priority ASC;
					',
					$user_id,
				)
			);
			\wp_cache_set( $user_id, $tab_display_names, __METHOD__ );
		}

		return $tab_display_names;
	}

	/**
	 * Get a User Preference from the database
	 *
	 * @param int $user_id   The user id.
	 * @param int $pathway_id The Pathway.
	 *
	 * @return UserPreferenceEntity|null
	 */
	public function get_by_id( int $user_id, int $pathway_id ): ?UserPreferenceEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_id,
			$pathway_id
		);

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( $result ) {
			return UserPreferenceEntity::from_json( $result );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'
				SELECT user_id, tab_display_name, column_priority, pathway_id, pathway_enabled, destination_url, timestamp
				FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				WHERE user_id = %d AND pathway_id = %d;
			',
				$user_id,
				$pathway_id,
			)
		);

		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'
					SELECT user_id, tab_display_name, column_priority, pathway_id, pathway_enabled, destination_url, timestamp
					FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
					WHERE user_id = %d AND pathway_id = %d;
				',
					$user_id,
					$pathway_id,
				)
			);
		}

		$result = null;

		if ( $row ) {
			$result = new UserPreferenceEntity(
				(int) $row->user_id,
				$row->tab_display_name,
				$row->column_priority,
				(int) $row->pathway_id,
				(bool) $row->pathway_enabled,
				$row->destination_url,
				$row->timestamp,
			);

			wp_cache_set( $cache_key, __METHOD__, $result->to_json() );
		} else {
			wp_cache_set( $cache_key, __METHOD__, null );
		}

		return $result;
	}

	// ---

	/**
	 * Update a User Preference into the database
	 *
	 * @param UserPreferenceEntity $user_video_preference The updated user preference.
	 *
	 * @return UserPreferenceEntity|null
	 * @throws \Exception When failing to update.
	 */
	public function update( UserPreferenceEntity $user_video_preference ): ?UserPreferenceEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_video_preference->get_user_id(),
			$user_video_preference->get_pathway_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$this->get_table_name(),
			array(
				'user_id'         => $user_video_preference->get_user_id(),
				'column_priority' => $user_video_preference->get_column_priority(),
				'pathway_id'      => $user_video_preference->get_pathway_id(),
				'pathway_enabled' => $user_video_preference->is_pathway_enabled(),
				'destination_url' => $user_video_preference->get_destination_url_setting(),
				'timestamp'       => $user_video_preference->get_timestamp(),
			),
			array(
				'user_id'    => $user_video_preference->get_user_id(),
				'pathway_id' => $user_video_preference->get_pathway_id(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$user_video_preference->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id',
				)
			)
		);
		\wp_cache_delete(
			$user_video_preference->get_user_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_pathway_id',
				)
			)
		);

		return $user_video_preference;
	}

	/**
	 * Database Restore and Update
	 *
	 * @param string $db_error_message   The Error Message.
	 *
	 * @return bool
	 */
	private function repair_update_database( string $db_error_message = null ): bool {
		global $wpdb;

		// Case Table Mising Column.
		if ( strpos( $db_error_message, 'Unknown column' ) !== false ) {
			// Update Database to new Schema.

			$table_name           = $this->get_table_name();
			$add_timestamp_column = "ALTER TABLE `{$table_name}` ADD `timestamp` BIGINT UNSIGNED NULL AFTER `destination_url`; ";
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$wpdb->query( $wpdb->prepare( $add_timestamp_column ) );
			return true;
		}

		// Case Table Delete.
		$table_message = $this->get_table_name() . '\' doesn\'t exist';
		if ( strpos( $db_error_message, $table_message ) !== false ) {
			// Recreate Table.
			$this->install_user_preference_table();
			return true;
		}

	}


	/**
	 * Update Timestamp
	 *
	 * @param int $user_id - User ID.
	 * @param int $pathway_id The ID of the Pathway to Check.
	 *
	 * @return bool
	 */
	public function update_timestamp( int $user_id, int $pathway_id ): bool {
		global $wpdb;
		$timestamp = current_time( 'timestamp' );

		// Try to Update First.

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->query(
			$wpdb->prepare(
				'
				UPDATE ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				SET timestamp = %d
				WHERE user_id = %d AND pathway_id = %d;
				',
				$timestamp,
				$user_id,
				$pathway_id,
			)
		);

		if ( $result ) {
			$cache_key = $this->create_cache_key(
				$user_id,
				$pathway_id
			);
			\wp_cache_delete( $cache_key );
			return true;
		} else {
			return false;
		}

	}
}
