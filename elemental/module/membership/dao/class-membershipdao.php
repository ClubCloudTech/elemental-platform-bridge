<?php
/**
 * Data Access Object for controlling Room Mapping Database Entries
 *
 * @package ElementalPlugin\DAO
 */

namespace ElementalPlugin\Module\Membership\DAO;

use ElementalPlugin\Module\Membership\Membership;

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
		global $wpdb;

		$table_name = $this->get_table_name();

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`membership_level` BIGINT UNSIGNED NOT NULL,
			`woocomm_level` BIGINT UNSIGNED NOT NULL,
			`user_limit` BIGINT UNSIGNED NOT NULL,
			`template` BIGINT UNSIGNED NULL,
			`landing_template` BIGINT UNSIGNED NULL,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		return \maybe_create_table( $table_name, $sql_create );
	}

	/**
	 * Register an account limit
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
	 * Register a Template Record
	 *
	 * @param int $template       The User Limit to store.
	 * @param int $membership_level The Membership Level.
	 *
	 * @return string|int|false
	 */
	public function register_template_record( int $template, int $membership_level ) {
		global $wpdb;

     // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'membership_level' => $membership_level,
				'template'         => $template,
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
	 * Register a Landing Template Record
	 *
	 * @param int $template       The Landing Template.
	 * @param int $membership_level The Membership Level.
	 *
	 * @return string|int|false
	 */
	public function register_landing_template_record( int $template, int $membership_level ) {
		global $wpdb;

     // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'membership_level' => $membership_level,
				'landing_template' => $template,
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
	 * Update Limits by Membership Level.
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
	 * Update Template ID in the Database. Used for Control Panel Elemental.
	 *
	 * @param int $template       The Template ID.
	 * @param int $membership_level Membership level to update.
	 *
	 * @return bool|null
	 */
	public function update_template( int $template, int $membership_level ): ?bool {
		global $wpdb;

		// Check Record Exists.
		$record_exists = $this->get_limit_info( $membership_level );
		if ( ! $record_exists ) {
			$success = $this->register_template_record( $user_limit, $membership_level );
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
					SET template = %d
					WHERE membership_level = %d
				',
				$template,
				$membership_level,
			)
		);

		\wp_cache_delete( $membership_level, __CLASS__ . '::get_limit_info' );

		return $success;
	}

	/**
	 * Update Landing Template ID in the Database. Used for Control Panel Elemental.
	 *
	 * @param int $template       The Template ID.
	 * @param int $membership_level Membership level to update.
	 *
	 * @return bool|null
	 */
	public function update_landing_template( int $template, int $membership_level ): ?bool {
		global $wpdb;

		// Check Record Exists.
		$record_exists = $this->get_limit_info( $membership_level );
		if ( ! $record_exists ) {
			$success = $this->register_landing_template_record( $template, $membership_level );
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
					SET landing_template = %d
					WHERE membership_level = %d
				',
				$template,
				$membership_level,
			)
		);

		\wp_cache_delete( $membership_level, __CLASS__ . '::get_limit_info' );

		return $success;
	}


	/**
	 * Delete a Level Mapping.
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
		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
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
						SELECT record_id, membership_level, woocomm_level, user_limit, template, landing_template
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE membership_level = %d
					',
					$membership_level,
				),
				'ARRAY_A'
			);
		}
		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
		} else {
			\wp_cache_set( $membership_level, $result, __METHOD__ );
		}
		if ( $result ) {
			$result                   = (object) $result;
			$result->id               = $result->membership_level;
			$result->user_limit       = $result->user_limit;
			$result->template         = $result->template;
			$result->landing_template = $result->landing_template;
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
			if ( $wpdb->last_error ) {
				$this->repair_update_database( $wpdb->last_error );
			}

			\wp_cache_set( $cache_key, $result, __METHOD__ );
		}

		return $result;
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
		$table_name = $this->get_table_name();
		// Case Table Mising Column.
		if ( strpos( $db_error_message, 'Unknown column' ) !== false ) {
			// Update Database to new Schema.
			// V2.
			$update_db = "ALTER TABLE `{$table_name}` ADD `template` BIGINT UNSIGNED NULL AFTER `user_limit`";
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$wpdb->query( $wpdb->prepare( $update_db ) );
			// V3.
			$update_db = "ALTER TABLE `{$table_name}` ADD `landing_template` BIGINT UNSIGNED NULL AFTER `template`";
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$wpdb->query( $wpdb->prepare( $update_db ) );

			return true;
		}

		// Case Table Delete.
		$table_message = $table_name . '\' doesn\'t exist';
		if ( strpos( $db_error_message, $table_message ) !== false ) {
			// Recreate Table.
			$this->install_membership_mapping_table();

			return true;
		}
	}
}
