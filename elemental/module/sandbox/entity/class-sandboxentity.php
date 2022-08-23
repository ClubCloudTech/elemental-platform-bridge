<?php
/**
 * Sandbox Configuration Entity Object
 *
 * @package module/sandbox/entity/class-sandboxentity.php
 */

namespace ElementalPlugin\Module\Sandbox\Entity;

/**
 * Class SandboxEntity
 */
class SandboxEntity {

	/**
	 * Tab Name.
	 *
	 * @var string $tab_name
	 */
	private string $tab_name;

	/**
	 * User_name_prepend
	 *
	 * @var ?string $user_name_prepend - the string to pre-pend to user name.
	 */
	private ?string $user_name_prepend;

	/**
	 * Destination_url
	 *
	 * @var string $destination_url
	 */
	private string $destination_url;

	/**
	 * Customfield1 - custom item to add to header.
	 *
	 * @var ?string $customfield1
	 */
	private ?string $customfield1;

	/**
	 * Customfield2 - custom item to add to header.
	 *
	 * @var ?string $customfield2
	 */
	private ?string $customfield2;

	/**
	 * Employee Name Key String
	 *
	 * @var string $employee_name
	 */
	private ?string $employee_name;

	/**
	 * Company Domain Key String
	 *
	 * @var string $company_domain
	 */
	private ?string $company_domain;

	/**
	 * Is Item Enabled.
	 *
	 * @var bool $is_enabled
	 */
	private bool $is_enabled;

	/**
	 * Private/Public Key String
	 *
	 * @var string $private_key
	 */
	private string $private_key;

	/**
	 * Record ID
	 *
	 * @var ?int $record_id
	 */
	private ?int $record_id;

	/**
	 * Owner User ID
	 *
	 * @var ?int $owner_user_id
	 */
	private ?int $owner_user_id;

	/**
	 * Column Priority
	 *
	 * @var ?int $column_priority
	 */
	private ?int $column_priority;


	/**
	 * Admin Enforced Tab.
	 *
	 * @var bool $admin_enforced
	 */
	private bool $admin_enforced;

	/**
	 * SandboxEntity constructor.
	 *
	 * @param string  $tab_name                Tab Name.
	 * @param string  $user_name_prepend       User Name prepend string.
	 * @param string  $destination_url         Destination URL of Iframe.
	 * @param ?string $customfield1            Custom String for header.
	 * @param ?string $customfield2            Custom String for header.
	 * @param ?string $employee_name            Custom String for header.
	 * @param ?string $company_domain            Custom String for header.
	 * @param bool    $is_enabled              Is Item Enabled.
	 * @param string  $private_key             The Private or Public Key Cypher.
	 * @param ?int    $record_id               The Record ID.
	 * @param int     $owner_user_id           Owner User ID of the Record.
	 * @param ?int    $column_priority         Column sort priority.
	 * @param bool    $admin_enforced          Admin Enforced Record.
	 */
	public function __construct(
		string $tab_name,
		string $user_name_prepend,
		string $destination_url,
		?string $customfield1 = null,
		?string $customfield2 = null,
		?string $employee_name = null,
		?string $company_domain = null,
		bool $is_enabled = false,
		string $private_key,
		int $record_id,
		int $owner_user_id,
		?int $column_priority,
		bool $admin_enforced = false

	) {
		$this->tab_name          = $tab_name;
		$this->user_name_prepend = $user_name_prepend;
		$this->destination_url   = $destination_url;
		$this->customfield1      = $customfield1;
		$this->customfield2      = $customfield2;
		$this->employee_name     = $employee_name;
		$this->company_domain    = $company_domain;
		$this->is_enabled        = $is_enabled;
		$this->private_key       = $private_key;
		$this->record_id         = $record_id;
		$this->owner_user_id     = $owner_user_id;
		$this->column_priority   = $column_priority;
		$this->admin_enforced    = $admin_enforced;
	}

	/**
	 * Create from a JSON object
	 * Note - Original Room Name does not get stored in JSON as it does not get stored in database.
	 *
	 * @param string $json The JSON representation of the object.
	 *
	 * @return ?SandboxEntity
	 */
	public static function from_json( string $json ): ?self {
		$data = json_decode( $json );

		if ( $data ) {
			return new self(
				$data->tab_name,
				$data->user_name_prepend,
				$data->destination_url,
				$data->customfield1,
				$data->customfield2,
				$data->employee_name,
				$data->company_domain,
				$data->is_enabled,
				$data->private_key,
				$data->record_id,
				$data->owner_user_id,
				$data->column_priority,
				$data->admin_enforced,
			);
		}

		return null;
	}

	/**
	 * Convert to JSON
	 * Used for caching.
	 * Note - Original Room Name does not get stored in JSON as it does not get stored in database.
	 *
	 * @return string
	 */
	public function to_json(): string {
		return wp_json_encode(
			array(
				'tab_name'          => $this->tab_name,
				'user_name_prepend' => $this->user_name_prepend,
				'destination_url'   => $this->destination_url,
				'customfield1'      => $this->customfield1,
				'customfield2'      => $this->customfield2,
				'employee_name'     => $this->employee_name,
				'company_domain'    => $this->company_domain,
				'is_enabled'        => $this->is_enabled,
				'private_key'       => $this->private_key,
				'record_id'         => $this->record_id,
				'owner_user_id'     => $this->owner_user_id,
				'column_priority'   => $this->column_priority,
				'admin_enforced'    => $this->admin_enforced,
			)
		);
	}

	/**
	 * Gets Room Name.
	 *
	 * @return string
	 */
	public function get_tab_name(): string {
		return $this->tab_name;
	}

	/**
	 * Sets Tab Name.
	 *
	 * @param string|null $tab_name - the Tab name.
	 *
	 * @return SandboxEntity
	 */
	public function set_tab_name( string $tab_name ): SandboxEntity {
		$this->tab_name = $tab_name;

		return $this;
	}

	/**
	 * Sets User Name Prepend String.
	 *
	 * @param string|null $user_name_prepend - the prepend string to the user name.
	 *
	 * @return SandboxEntity
	 */
	public function set_user_name_prepend( string $user_name_prepend ): SandboxEntity {
		$this->user_name_prepend = $user_name_prepend;

		return $this;
	}

	/**
	 * Gets User Name Prepend String.
	 *
	 * @return string
	 */
	public function get_user_name_prepend(): string {
		return $this->user_name_prepend;
	}

	/**
	 * Sets Destination URL.
	 *
	 * @param string|null $destination_url - The Destination URL.
	 *
	 * @return SandboxEntity
	 */
	public function set_destination_url( string $destination_url ): SandboxEntity {
		$this->destination_url = $destination_url;

		return $this;
	}

	/**
	 * Gets Destination URL.
	 *
	 * @return bool
	 */
	public function get_destination_url(): string {
		return $this->destination_url;
	}

	/**
	 * Sets Custom Field 1
	 *
	 * @param ?string $customfield1 - Reception Status.
	 *
	 * @return SandboxEntity
	 */
	public function set_customfield1( string $customfield1 ): SandboxEntity {
		$this->customfield1 = $customfield1;

		return $this;
	}

	/**
	 * Gets Custom Field 1.
	 *
	 * @return ?string
	 */
	public function get_customfield1(): ?string {
		return $this->customfield1;
	}

	/**
	 * Sets Custom Field 2
	 *
	 * @param ?string $customfield2 - Reception Status.
	 *
	 * @return SandboxEntity
	 */
	public function set_customfield2( string $customfield2 ): SandboxEntity {
		$this->customfield2 = $customfield2;

		return $this;
	}

	/**
	 * Gets Custom Field 2.
	 *
	 * @return ?string
	 */
	public function get_customfield2(): ?string {
		return $this->customfield2;
	}

	/**
	 * Gets employee_name.
	 *
	 * @return ?string
	 */
	public function get_employee_name(): ?string {
		return $this->employee_name;
	}

	/**
	 * Sets employee_name
	 *
	 * @param ?string $employee_name - Reception Status.
	 *
	 * @return SandboxEntity
	 */
	public function set_employee_name( string $employee_name ): SandboxEntity {
		$this->employee_name = $employee_name;

		return $this;
	}

	/**
	 * Sets company_domain
	 *
	 * @param ?string $company_domain - Reception Status.
	 *
	 * @return SandboxEntity
	 */
	public function set_company_domain( string $company_domain ): SandboxEntity {
		$this->company_domain = $company_domain;

		return $this;
	}

	/**
	 * Gets company_domain.
	 *
	 * @return ?string
	 */
	public function get_company_domain(): ?string {
		return $this->company_domain;
	}

	/**
	 * Checks Item is Enabled in Database.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return $this->is_enabled;
	}

	/**
	 * Sets Enabled Status
	 *
	 * @param bool $is_enabled - Item Enabled Status.
	 *
	 * @return SandboxEntity
	 */
	public function set_enabled_status( bool $is_enabled ): SandboxEntity {
		$this->is_enabled = $is_enabled;

		return $this;
	}

	/**
	 * Gets Public/Private Key String.
	 *
	 * @return string
	 */
	public function get_private_key(): string {
		return $this->private_key;
	}

	/**
	 * Sets Public/Private Key String.
	 *
	 * @param string|null $private_key - the private or public key string.
	 *
	 * @return SandboxEntity
	 */
	public function set_private_key( string $private_key ): SandboxEntity {
		$this->private_key = $private_key;

		return $this;
	}

	/**
	 * Gets Record ID.
	 *
	 * @return ?int
	 */
	public function get_record_id(): ?int {
		return $this->record_id;
	}

	/**
	 * Gets Owner User_ID.
	 *
	 * @return ?int
	 */
	public function get_owner_user_id(): ?int {
		return $this->owner_user_id;
	}

	/**
	 * Sets Owner User_ID.
	 *
	 * @param int|null $owner_user_id - the private or public key string.
	 *
	 * @return SandboxEntity
	 */
	public function set_owner_user_id( int $owner_user_id ): SandboxEntity {
		$this->owner_user_id = $owner_user_id;

		return $this;
	}

	/**
	 * Gets Owner User_ID.
	 *
	 * @return ?int
	 */
	public function get_column_priority(): ?int {
		return $this->column_priority;
	}

	/**
	 * Sets Owner User_ID.
	 *
	 * @param int|null $column_priority - the private or public key string.
	 *
	 * @return SandboxEntity
	 */
	public function set_column_priority( int $column_priority ): SandboxEntity {
		$this->column_priority = $column_priority;

		return $this;
	}

	/**
	 * Checks Item is Enabled in Database.
	 *
	 * @return bool
	 */
	public function is_admin_enforced(): bool {
		return $this->admin_enforced;
	}

	/**
	 * Sets Enabled Status
	 *
	 * @param bool $admin_enforced - Whether this record is enforced by Admins.
	 *
	 * @return SandboxEntity
	 */
	public function set_admin_enforced( bool $admin_enforced ): SandboxEntity {
		$this->admin_enforced = $admin_enforced;

		return $this;
	}
}
