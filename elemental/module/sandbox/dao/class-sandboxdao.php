<?php
/**
 * Data Access Object for Dataroom preference.
 *
 * @package module/sandbox/dao/class-sandboxdao.php
 */

namespace ElementalPlugin\Module\Sandbox\DAO;

use ElementalPlugin\Module\Sandbox\Entity\SandboxEntity;

/**
 * Class UserVideoPreference
 * Manages DB Layer for User Preferences for Video Room Base Settings.
 */
class SandBoxDao {

	const TABLE_NAME  = 'elemental_sandbox';
	const USER_ID_ALL = -1;

	/**
	 * Install Sandbox Control Table -
	 *
	 * @return bool
	 */
	public function install_sandbox_control_table(): bool {
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		global $wpdb;

		$table_name = $this->get_table_name();

		$sql_create = 'CREATE TABLE IF NOT EXISTS `' . $table_name . '` (
			`record_id` int NOT NULL AUTO_INCREMENT,
			`tab_name` VARCHAR(255) NOT NULL,
			`user_name_prepend` VARCHAR(255) NOT NULL,
			`destination_url` VARCHAR(512) NOT NULL,
			`customfield1` VARCHAR(512) NULL,
			`customfield2` VARCHAR(512) NULL,
			`employee_name` VARCHAR(512) NULL,
			`company_domain` VARCHAR(512) NULL,
			`enabled` BOOLEAN,
			`private_key` VARCHAR(512) NOT NULL,
			`owner_user_id` INT NOT NULL,
			`column_priority` INT UNSIGNED NOT NULL,
			`admin_enforced` BOOLEAN,
			PRIMARY KEY (`record_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

		return \maybe_create_table( $table_name, $sql_create );
	}

	/**
	 * Save a Sandbox entity in the databas
	 *
	 * @param SandboxEntity $sandbox_entity The Sandbox to save.
	 *
	 * @return SandboxEntity|null
	 * @throws \Exception When failing to insert, most likely a duplicate key.
	 */
	public function create( SandboxEntity $sandbox_entity ): ?SandboxEntity {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$sandbox_entity->get_tab_name(),
			$sandbox_entity->get_destination_url()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'tab_name'          => $sandbox_entity->get_tab_name(),
				'user_name_prepend' => $sandbox_entity->get_user_name_prepend(),
				'destination_url'   => $sandbox_entity->get_destination_url(),
				'customfield1'      => $sandbox_entity->get_customfield1(),
				'customfield2'      => $sandbox_entity->get_customfield2(),
				'employee_name'     => $sandbox_entity->get_employee_name(),
				'company_domain'    => $sandbox_entity->get_company_domain(),
				'enabled       '    => $sandbox_entity->is_enabled(),
				'private_key'       => $sandbox_entity->get_private_key(),
				'owner_user_id'     => $sandbox_entity->get_owner_user_id(),
				'column_priority'   => $sandbox_entity->get_column_priority(),
				'admin_enforced'    => $sandbox_entity->is_admin_enforced(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$sandbox_entity->to_json(),
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

		return $sandbox_entity;
	}

	/**
	 * Create a cache key
	 *
	 * @param string $tab_name   The user id.
	 * @param string $destination_url The room name.
	 *
	 * @return string
	 */
	private function create_cache_key( string $tab_name, string $destination_url ): string {
		return "tab_name:${tab_name}:destination_url:${destination_url}:1";
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
	 * @param SandboxEntity $sandbox_entity The Sandbox to delete.
	 *
	 * @return null
	 * @throws \Exception When failing to delete.
	 */
	public function delete( SandboxEntity $sandbox_entity ) {
		global $wpdb;

		$cache_key = $this->create_cache_key(
			$sandbox_entity->get_tab_name(),
			$sandbox_entity->get_destination_url()
		);

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->delete(
			$this->get_table_name(),
			array(
				'record_id' => $sandbox_entity->get_record_id(),
			)
		);

		\wp_cache_delete( $cache_key, implode( '::', array( __CLASS__, 'get_by_id' ) ) );

		return null;
	}


	/**
	 * Get a Sandbox from the database
	 *
	 * @param int $record_id The Record ID to retrieve.
	 *
	 * @return SandboxEntity|null
	 */
	public function get_by_id( int $record_id ): ?SandboxEntity {
		global $wpdb;

		$cache_key = $record_id;

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( $result ) {
			return SandboxEntity::from_json( $result );
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'
				SELECT tab_name, user_name_prepend, destination_url, customfield1, customfield2, employee_name, company_domain, enabled, private_key, record_id, owner_user_id, column_priority, admin_enforced 
				FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
				WHERE record_id = %d;
			',
				$record_id,
			)
		);

		if ( $wpdb->last_error ) {
			$this->repair_update_database( $wpdb->last_error );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'
					SELECT tab_name, user_name_prepend, destination_url, customfield1, customfield2, employee_name, company_domain, enabled, private_key, record_id, owner_user_id, column_priority, admin_enforced
					FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
					WHERE record_id = %d;
				',
					$record_id,
				)
			);
		}

		$result = null;

		if ( $row ) {
			$result = new SandboxEntity(
				$row->tab_name,
				$row->user_name_prepend,
				$row->destination_url,
				$row->customfield1,
				$row->customfield2,
				$row->employee_name,
				$row->company_domain,
				(bool) $row->enabled,
				$row->private_key,
				(int) $row->record_id,
				$row->owner_user_id,
				$row->column_priority,
				(bool) $row->admin_enforced,
			);

			wp_cache_set( $cache_key, __METHOD__, $result->to_json() );
		} else {
			wp_cache_set( $cache_key, __METHOD__, null );
		}

		return $result;
	}

	// ---

	/**
	 * Update a Sandbox into the database
	 *
	 * @param SandboxEntity $sandbox_entity The updated Sandbox Object.
	 *
	 * @return SandboxEntity|null
	 * @throws \Exception When failing to update.
	 */
	public function update( SandboxEntity $sandbox_entity ): ?SandboxEntity {
		global $wpdb;

		$cache_key = $sandbox_entity->get_record_id();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$this->get_table_name(),
			array(
				'tab_name'          => $sandbox_entity->get_tab_name(),
				'user_name_prepend' => $sandbox_entity->get_user_name_prepend(),
				'destination_url'   => $sandbox_entity->get_destination_url(),
				'customfield1'      => $sandbox_entity->get_customfield1(),
				'customfield2'      => $sandbox_entity->get_customfield2(),
				'employee_name'     => $sandbox_entity->get_employee_name(),
				'company_domain'    => $sandbox_entity->get_company_domain(),
				'enabled       '    => $sandbox_entity->is_enabled(),
				'private_key'       => $sandbox_entity->get_private_key(),
				'owner_user_id'     => $sandbox_entity->get_owner_user_id(),
				'column_priority'   => $sandbox_entity->get_column_priority(),
				'admin_enforced'    => $sandbox_entity->is_admin_enforced(),
			),
			array(
				'record_id' => $sandbox_entity->get_record_id(),
			)
		);

		\wp_cache_set(
			$cache_key,
			$sandbox_entity->to_json(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_id',
				)
			)
		);
		\wp_cache_delete(
			$sandbox_entity->get_record_id(),
			implode(
				'::',
				array(
					__CLASS__,
					'get_by_record_id',
				)
			)
		);

		return $sandbox_entity;
	}

	/**
	 * Update a field by a value.
	 *
	 * @param mixed  $value The value to change.
	 * @param string $field The field to update.
	 * @param int    $id - The record id.
	 *
	 * @return bool
	 */
	public function update_by_field( $value, string $field, int $id ): bool {

		global $wpdb;
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->update(
			$this->get_table_name(),
			array( $field => $value ),
			array( 'record_id' => (int) $id )
		);
		return true;
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
			$add_employee_company = "ALTER TABLE `{$table_name}` ADD `company_domain` VARCHAR(512) NULL AFTER `customfield2`, ADD `employee_name` VARCHAR(512) NULL AFTER `customfield2` ;
			 ";
			//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery
			$wpdb->query( $wpdb->prepare( $add_employee_company ) );
			return true;
		}

		// Case Table Delete.
		$table_message = $this->get_table_name() . '\' doesn\'t exist';
		if ( strpos( $db_error_message, $table_message ) !== false ) {
			// Recreate Table.
			$this->install_sandbox_control_table();

			return true;
		}
	}

	/**
	 * Get All Entity Items
	 *
	 * @return array
	 */
	public function get_all_entities(): array {
		global $wpdb;

		$cache_key = '__ALL__';

		$result = \wp_cache_get( $cache_key, __METHOD__ );

		if ( false === $result ) {

				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
				$rows = $wpdb->get_results(
					'
                        SELECT record_id
                        FROM ' . /*phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared*/ $this->get_table_name() . '
                        ORDER BY record_id ASC
                    '
				);

			$result = array_map(
				function ( $row ) {
					return (int) $row->record_id;
				},
				$rows
			);

			\wp_cache_set( $cache_key, $result, __METHOD__ );
		}

		return $result;
	}

	/**
	 * Get Entity Pathways by a user id.
	 *
	 * @param int  $user_id The user_id to retrieve.
	 * @param bool $only -only retrieves the User_id and not the all user (-1).
	 *
	 * @return array
	 */
	public function get_entities_by_id( int $user_id, bool $only = false ): array {
		global $wpdb;

		$result = \wp_cache_get( $user_id . strval( $only ), __METHOD__ );

		if ( false === $result ) {
         // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$rows = $wpdb->get_results(
				$wpdb->prepare(
					'
						SELECT record_id
						FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
						WHERE owner_user_id = %d
						ORDER BY record_id ASC
					',
					$user_id,
				)
			);

			$result = array_map(
				function ( $row ) {
					return (int) $row->record_id;
				},
				$rows
			);

			if ( ! $only ) {
				$user_id = self::USER_ID_ALL;
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
					$rows2 = $wpdb->get_results(
						$wpdb->prepare(
							'
								SELECT record_id
								FROM ' . /* phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared */ $this->get_table_name() . '
								WHERE owner_user_id = %d
								ORDER BY record_id ASC
							',
							$user_id,
						)
					);

				$result_all   = array_map(
					function ( $row2 ) {
						return (int) $row2->record_id;
					},
					$rows2
				);
				$result_final = \array_merge( $result, $result_all );
			}
		}
		if ( $result_final ) {
			\wp_cache_set( $user_id . strval( $only ), $result_final, __METHOD__ );
			return $result_final;
		} else {
			\wp_cache_set( $user_id . strval( $only ), $result, __METHOD__ );
			return $result;
		}
	}
}
