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
	 * SandboxEntity constructor.
	 *
	 * @param string  $tab_name                Tab Name.
	 * @param string  $user_name_prepend       User Name prepend string.
	 * @param string  $destination_url         Destination URL of Iframe.
	 * @param ?string $customfield1            Custom String for header.
	 * @param ?string $customfield2            Custom String for header.
	 * @param bool    $is_enabled              Is Item Enabled.
	 * @param string  $private_key             The Private or Public Key Cypher.
	 * @param ?int    $record_id               The Record ID.
	 */
	public function __construct(
		string $tab_name,
		string $user_name_prepend,
		string $destination_url,
		?string $customfield1 = null,
		?string $customfield2 = null,
		bool $is_enabled = false,
		string $private_key,
		int $record_id

	) {
		$this->tab_name          = $tab_name;
		$this->user_name_prepend = $user_name_prepend;
		$this->destination_url   = $destination_url;
		$this->customfield1      = $customfield1;
		$this->customfield2      = $customfield2;
		$this->is_enabled        = $is_enabled;
		$this->private_key       = $private_key;
		$this->record_id         = $record_id;
	}

	/**
	 * Create from a JSON object
	 * Note - Original Room Name does not get stored in JSON as it does not get stored in database.
	 *
	 * @param string $json The JSON representation of the object.
	 *
	 * @return ?\MyVideoRoomPlugin\Entity\SandboxEntity
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
				$data->is_enabled,
				$data->private_key,
				$data->record_id,
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
				'is_enabled'        => $this->is_enabled,
				'private_key'       => $this->private_key,
				'record_id'         => $this->record_id,
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
}
