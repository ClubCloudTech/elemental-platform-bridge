<?php
/**
 * Data Access Object for controlling User Child/Parent account mappings.
 *
 * @package ElementalPlugin\DAO
 */

namespace ElementalPlugin\Membership\DAO;

use ElementalPlugin\Membership\Membership;
use stdClass;

/**
 * Class MembersyncDAO
 * Registers Child/Parent account Database Objects and manages Parent/Child Account Table.
 */
class MemberSyncDAO {
	const TABLE_NAME = Membership::TABLE_NAME_MEMBERSYNC;


	/**
	 * Install Parent/Child Table -
	 *
	 * @return bool
	 */
	public function install_membership_sync_table(): bool {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		$table_name = $this->get_table_name();

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`parent_id` BIGINT UNSIGNED NOT NULL,
			`user_id` BIGINT UNSIGNED NOT NULL,
			`timestamp` BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		return \maybe_create_table( $table_name, $sql_create );
	}

	/**
	 * Register a given room in the Database, and ensure it does not already exist
	 *
	 * @param int $child_id    The User ID of the child account to store.
	 * @param int $parent_id  The Parent ID.
	 *
	 * @return string|int|false
	 */
	public function register_child_account( int $child_id, int $parent_id ) {
		global $wpdb;

		//phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$timestamp = current_time( 'timestamp' );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'parent_id' => $parent_id,
				'user_id'   => $child_id,
				'timestamp' => $timestamp,
			)
		);

		\wp_cache_delete( $child_id, __CLASS__ . '::get_parent_by_child' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_all_child_accounts' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_child_count' );
		\wp_cache_delete( '__ALL__', __CLASS__ . '::get_all_child_accounts' );

		if ( $result ) {
			return $result;
		} else {
			return false;
		}

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
	 * Update Room Post ID in Database
	 * This plugin will update the room name in the database with the parameter
	 *
	 * @param int $child_id   The user limit.
	 * @param int $parent_id Membership level to update.
	 *
	 * @return bool|null
	 */
	public function update_child_account( int $child_id, int $parent_id ): ?bool {
		global $wpdb;

		// Check Record Exists.
		$record_exists = $this->get_parent_by_child( $child_id );
		if ( ! $record_exists ) {
			$success = $this->register_child_account( $child_id, $parent_id );
			if ( $success ) {
				return true;
			} else {
				return false;
			}
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$success = $wpdb->query(
			$wpdb->prepare(
				'
					UPDATE ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
					SET parent_id = %d
					WHERE user_id = %d
				',
				$parent_id,
				$child_id,
			)
		);

		\wp_cache_delete( $child_id, __CLASS__ . '::get_parent_by_child' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_child_account_info' );

		return $success;
	}

	/**
	 * Update Room Post ID in Database
	 * This plugin will update the room name in the database with the parameter
	 *
	 * @param int $child_id   The user limit.
	 *
	 * @return bool|null
	 */
	public function delete_child_account( int $child_id ): ?bool {
		global $wpdb;
		$parent_id = $this->get_parent_by_child( $child_id );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$success = $wpdb->query(
			$wpdb->prepare(
				'
					DELETE FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				    WHERE user_id = %d
			    ',
				$child_id,
			)
		);

		\wp_cache_delete( $child_id, __CLASS__ . '::get_parent_by_child' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_child_count' );
		\wp_cache_delete( $child_id, __CLASS__ . '::get_all_child_accounts' );
		\wp_cache_delete( $child_id, __CLASS__ . '::get_child_account_info' );
		\wp_cache_delete( '__ALL__', __CLASS__ . '::get_all_child_accounts' );

		return $success;
	}

	/**
	 * Delete all accounts from a Parent.
	 * This function will delete all child account entries for a given parent.
	 *
	 * @param int $parent_id The Parent ID.
	 *
	 * @return bool
	 */
	public function delete_parent_account_mappings( int $parent_id ): bool {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->query(
			$wpdb->prepare(
				'
					DELETE FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				    WHERE parent_id = %d
			    ',
				$parent_id,
			)
		);

		\wp_cache_delete( $parent_id, __CLASS__ . '::get_parent_by_child' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_all_child_accounts' );
		\wp_cache_delete( $parent_id, __CLASS__ . '::get_child_account_info' );
		\wp_cache_delete( '__ALL__', __CLASS__ . '::get_all_child_accounts' );

		return true;
	}

	/**
	 * Get a Parent ID from a User.
	 *
	 * @param int $child_id The Child Account.
	 *
	 * @return ?int
	 */
	public function get_parent_by_child( int $child_id ): ?int {
		global $wpdb;

		$result = \wp_cache_get( $child_id, __METHOD__ );

		if ( false === $result ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'
						SELECT parent_id
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE user_id = %d
					',
					$child_id,
				)
			);

			if ( $row ) {
				$result = (int) $row->parent_id;
			}

			\wp_cache_set( $child_id, $result, __METHOD__ );
		}

		return (int) $result;
	}

	/**
	 * Get All Child Accounts from Database by Parent.
	 *
	 * @param int $child_id The Parent ID to query.
	 *
	 * @return ?\stdClass
	 */
	public function get_child_account_info( int $child_id ): ?\stdClass {
		global $wpdb;

		$result = \wp_cache_get( $child_id, __METHOD__ );

		if ( false === $result ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$return = $wpdb->get_row(
				$wpdb->prepare(
					'
						SELECT timestamp, parent_id, user_id
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE user_id = %d
					',
					$child_id,
				),
				'ARRAY_A'
			);
			\wp_cache_set( $child_id, $result, __METHOD__ );
		}

		if ( $return ) {
			$result            = new stdClass();
			$result->parent_id = $return->parent_id;
			$result->child_id  = $return->user_id;
			$result->timestamp = $return->timestamp;
		} else {
			$result = null;
		}

		return $result;
	}
	/**
	 * Get All Child Accounts from Database by Parent.
	 *
	 * @param int $parent_id The Parent ID to query.
	 *
	 * @return ?int
	 */
	public function get_child_count( int $parent_id ): ?int {
		global $wpdb;

		$result = \wp_cache_get( $parent_id, __METHOD__ );
		// $result = false;
		if ( false === $result ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$wpdb->get_results(
				$wpdb->prepare(
					'
						SELECT user_id
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE parent_id = %d
					',
					$parent_id,
				)
			);
			$rowcount = $wpdb->num_rows;
			\wp_cache_set( $parent_id, $rowcount, __METHOD__ );
			if ( $rowcount ) {
				return $rowcount;
			} else {
				return 0;
			}
		} else {
			return $result;
		}
	}

	/**
	 * Get All Child Accounts, either for a given parent or for all parents.
	 *
	 * @param int $parent_id parent ID to filter on (null for all parents).
	 *
	 * @return array
	 */
	public function get_all_child_accounts( int $parent_id = null ): array {
		global $wpdb;

		$cache_key = $parent_id;
		if ( ! $parent_id ) {
			$cache_key = '__ALL__';
		}

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( false === $result ) {
			if ( $parent_id ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$rows = $wpdb->get_results(
					$wpdb->prepare(
						'
							SELECT user_id, timestamp, parent_id
							FROM ' . /*phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/ $this->get_table_name() . '
							WHERE parent_id = %d
							ORDER BY parent_id ASC
						',
						$parent_id
					)
				);
			} else {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$rows = $wpdb->get_results(
					'
                        SELECT user_id, timestamp, parent_id
                        FROM ' . /*phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/ $this->get_table_name() . '
                        ORDER BY parent_id ASC
                    '
				);
			}

			$result = array_map(
				function ( $row ) {
					$item              = array();
					$item['user_id']   = (int) $row->user_id;
					$item['timestamp'] = (int) $row->timestamp;
					$item['parent_id'] = (int) $row->parent_id;
					return $item;
				},
				$rows
			);

			\wp_cache_set( $cache_key, $result, __METHOD__ );
		}

		return $result;
	}
}
