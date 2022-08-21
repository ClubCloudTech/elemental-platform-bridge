<?php
/**
 * Data Access Object for User Preferences
 *
 * @package dao/class-userpreferencedao.php
 */

namespace ElementalPlugin\DAO;

use ElementalPlugin\Entity\Token as TokenEntity;

/**
 * Class UserVideoPreference
 * Manages DB Layer for User Preferences for Video Room Base Settings.
 */
class TokenDAO {

	const TABLE_NAME = 'elemental_user_tokens';

	/**
	 * Install_token_object_table - this is the main table for all User Room Config
	 *
	 * @return ?string
	 */
	public static function install_user_tokens_table(): ?string {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		$table_name = $wpdb->prefix . self::TABLE_NAME;

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`user_id` BIGINT NOT NULL,
			`user_token` VARCHAR(255) NULL,
			`timestamp` BIGINT UNSIGNED NOT NULL,
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
	 * Save a Token Reference
	 *
	 * @param TokenEntity $token_object The video preference to save.
	 *
	 * @return TokenEntity|null
	 * @throws \Exception When failing to insert, most likely a duplicate key.
	 */
	public function create( TokenEntity $token_object ): ?TokenEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$token_object->get_user_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'user_id'    => $token_object->get_user_id(),
				'user_token' => $token_object->get_user_token(),
				'timestamp'  => $token_object->get_timestamp(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$token_object->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id',
				)
			)
		);

		if ( ! $result ) {
			return null;
		}

		return $token_object;
	}

	/**
	 * Create a cache key
	 *
	 * @param int $user_id   The user id.
	 *
	 * @return string
	 */
	private function create_cache_key( int $user_id ): string {
		return "user_id:${user_id}";
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
	 * Delete a Token Record the database
	 *
	 * @param TokenEntity $token_object The token to delete.
	 *
	 * @return null
	 * @throws \Exception When failing to delete.
	 */
	public function delete( TokenEntity $token_object ) {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$token_object->get_user_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->delete(
			$this->get_table_name(),
			array(
				'user_id' => $token_object->get_user_id(),
			)
		);

		\wp_cache_delete( $cache_key, implode( '::', array( __CLASS__, 'get_by_id' ) ) );

		return null;
	}


	/**
	 * Get a Token Record
	 *
	 * @param int $user_id   The user id.
	 *
	 * @return TokenEntity|null
	 */
	public function get_by_id( int $user_id ): ?TokenEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$user_id
		);

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( $result ) {
			return TokenEntity::from_json( $result );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'
				SELECT user_id, user_token, timestamp
				FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				WHERE user_id = %d;
			',
				$user_id,
			)
		);

		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'
					SELECT user_id, user_token, timestamp
					FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
					WHERE user_id = %d;
				',
					$user_id,
				)
			);
		}

		$result = null;

		if ( $row ) {
			$result = new TokenEntity(
				(int) $row->user_id,
				$row->user_token,
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
	 * Update a Token Object into the database
	 *
	 * @param TokenEntity $token_object The updated Token Object.
	 * @return TokenEntity|null
	 * @throws \Exception When failing to update.
	 */
	public function update( TokenEntity $token_object ): ?TokenEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$token_object->get_user_id()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$this->get_table_name(),
			array(
				'user_id'    => $token_object->get_user_id(),
				'user_token' => $token_object->get_user_token(),
				'timestamp'  => $token_object->get_timestamp(),
			),
			array(
				'user_id' => $token_object->get_user_id(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$token_object->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id',
				)
			)
		);

		return $token_object;
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
			$add_timestamp_column = "ALTER TABLE `{$table_name}` ADD `timestamp` BIGINT UNSIGNED NULL AFTER `user_token`; ";
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$wpdb->query( $wpdb->prepare( $add_timestamp_column ) );
			return true;
		}

		// Case Table Delete.
		$table_message = $this->get_table_name() . '\' doesn\'t exist';
		if ( strpos( $db_error_message, $table_message ) !== false ) {
			// Recreate Table.
			$this->install_user_tokens_table();
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
				$user_id
			);
			\wp_cache_delete( $cache_key );
			return true;
		} else {
			return false;
		}

	}
}
