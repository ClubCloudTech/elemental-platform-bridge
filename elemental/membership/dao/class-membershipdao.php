<?php
/**
 * Data Access Object for controlling Room Mapping Database Entries
 *
 * @package ElementalPlugin\DAO
 */

namespace ElementalPlugin\Membership\DAO;

use ElementalPlugin\Membership\Membership;

/**
 * Class MembershipDAO
 * Registers Membership Status Database Objects and manages Parent/Child Account Table.
 */
class MembershipDAO {

	const TABLE_NAME = Membership::TABLE_NAME_MEMBERSHIPS;


	/**
	 * Install Membership Table -
	 *
	 * @return bool
	 */
	public function install_membership_mapping_table(): bool {
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		$table_name = $this->get_table_name();

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`membership_level` BIGINT UNSIGNED NOT NULL,
			`woocomm_level` BIGINT UNSIGNED NOT NULL,
			`user_limit` BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		return \maybe_create_table( $table_name, $sql_create );
	}

	/**
	 * Register a given room in the Database, and ensure it does not already exist
	 *
	 * @param int $user_limit       The User Limit to store.
	 * @param int $membership_level The Membership Level.
	 *
	 * @return string|int|false
	 */
	public function register_account_limit( int $user_limit, int $membership_level ) {
		global $wpdb;

     // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'membership_level' => $membership_level,
				'user_limit'       => $user_limit,
			)
		);

		\wp_cache_delete( $user_limit, __CLASS__ . '::get_limit_by_membership' );
		\wp_cache_delete( $membership_level, __CLASS__ . '::get_all_membership_limits' );
		\wp_cache_delete( '__ALL__', __CLASS__ . '::get_all_membership_limits' );

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
	 * @param int $user_limit       The user limit.
	 * @param int $membership_level Membership level to update.
	 *
	 * @return bool|null
	 */
	public function update_membership_limit( int $user_limit, int $membership_level ): ?bool {
		global $wpdb;

		// Check Record Exists.
		$record_exists = $this->get_limit_info( $membership_level );
		if ( ! $record_exists ) {
			$success = $this->register_account_limit( $user_limit, $membership_level );
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
					SET user_limit = %d
					WHERE membership_level = %d
				',
				$user_limit,
				$membership_level,
			)
		);

		\wp_cache_delete( $user_limit, __CLASS__ . '::get_limit_by_membership' );
		\wp_cache_delete( $membership_level, __CLASS__ . '::get_limit_info' );

		return $success;
	}

	/**
	 * Delete a Room Record in Database.
	 * This function will delete the room name in the database with the parameter.
	 *
	 * @param int $membership_level The Membership Level to query.
	 *
	 * @return bool
	 */
	public function delete_level_mapping( int $membership_level ): bool {
		global $wpdb;

		$membership_level = $this->get_limit_by_membership( $membership_level );

		if ( ! $membership_level ) {
			return false;
		}

		$record_exists = $this->get_limit_info( $membership_level );

		if ( ! $record_exists ) {
			return false;
		}

     // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->query(
			$wpdb->prepare(
				'
					DELETE FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				    WHERE membership_level = %d
			    ',
				$membership_level,
			)
		);

		\wp_cache_delete( $membership_level, __CLASS__ . '::get_limit_by_membership' );
		\wp_cache_delete( $record_exists->user_limit, __CLASS__ . '::get_all_membership_limits' );
		\wp_cache_delete( $membership_level, __CLASS__ . '::get_limit_info' );
		\wp_cache_delete( '__ALL__', __CLASS__ . '::get_all_membership_limits' );

		return true;
	}

	/**
	 * Get a PostID from the Database for a Page
	 *
	 * @param int $membership_level The Membership Level.
	 *
	 * @return ?int
	 */
	public function get_limit_by_membership( int $membership_level ): ?int {
		global $wpdb;

		$result = \wp_cache_get( $membership_level, __METHOD__ );

		if ( false === $result ) {
         // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'
						SELECT user_limit
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE membership_level = %d
					',
					$membership_level,
				)
			);

			if ( $row ) {
				$result = (int) $row->user_limit;
			}

			\wp_cache_set( $membership_level, $result, __METHOD__ );
		}

		return (int) $result;
	}

	/**
	 * Get Room Info from Database.
	 *
	 * @param int $membership_level The Room iD to query.
	 *
	 * @return ?\stdClass
	 */
	public function get_limit_info( int $membership_level ): ?\stdClass {
		global $wpdb;

		$result = \wp_cache_get( $membership_level, __METHOD__ );

		if ( false === $result ) {
         // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$result = $wpdb->get_row(
				$wpdb->prepare(
					'
						SELECT record_id, membership_level, woocomm_level, user_limit
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE membership_level = %d
					',
					$membership_level,
				),
				'ARRAY_A'
			);
			\wp_cache_set( $membership_level, $result, __METHOD__ );
		}

		if ( $result ) {
			$result     = (object) $result;
			$result->id = $result->membership_level;
		} else {
			$result = null;
		}

		return $result;
	}


	/**
	 * Get All Membership Level Data
	 *
	 * @param int $membership_level Membership level to update.
	 *
	 * @return array
	 */
	public function get_all_membership_limits( int $membership_level = null ): array {
		global $wpdb;

		$cache_key = $membership_level;
		if ( ! $membership_level ) {
			$cache_key = '__ALL__';
		}

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( false === $result ) {
			if ( $membership_level ) {
             // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$rows = $wpdb->get_results(
					$wpdb->prepare(
						'
							SELECT user_limit
							FROM ' . /*phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/ $this->get_table_name() . '
							WHERE membership_level = %d
							ORDER BY membership_level ASC
						',
						$membership_level
					)
				);
			} else {
             // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$rows = $wpdb->get_results(
					'
                        SELECT user_limit
                        FROM ' . /*phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/ $this->get_table_name() . '
                        ORDER BY membership_level ASC
                    '
				);
			}

			$result = array_map(
				function ( $row ) {
					return (int) $row->user_limit;
				},
				$rows
			);

			\wp_cache_set( $cache_key, $result, __METHOD__ );
		}

		return $result;
	}
}
