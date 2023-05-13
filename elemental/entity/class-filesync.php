<?php
/**
 * File Sync Object
 *
 * @package elemental/entity/class-filesync.php
 */

namespace ElementalPlugin\Entity;

/**
 * Class Elemental - File Sync Object.
 */
class FileSync {

	/**
	 * The record id
	 *
	 * @var ?int
	 */
	private ?int $id;

	/**
	 * user_id - identifies the user.
	 *
	 * @var string
	 */
	private string $user_id;

	/**
	 * Application_name
	 *
	 * @var string
	 */
	private string $application_name;

	/**
	 * Timestamp
	 *
	 * @var int $timestamp - the Timestamp.
	 */
	private int $timestamp;

	/**
	 * User Picture URL
	 *
	 * @var ?string $user_picture_url
	 */
	private ?string $user_picture_url;

	/**
	 * User Display Name
	 *
	 * @var ?string $user_display_name
	 */
	private ?string $user_display_name;

	/**
	 * User Picture Path
	 *
	 * @var ?string $user_display_name
	 */
	private ?string $user_picture_path;

	/**
	 * File Sync Constructor
	 *
	 * @param  string  $user_id                The User ID.
	 * @param  string  $application_name       The Room Name.
	 * @param  int     $timestamp              Last Updated Timestamp.
	 * @param  ?int    $id                     The record id.
	 * @param  ?string $user_picture_url       The URL of the User picture.
	 * @param  ?string $user_display_name      The User Display Name.
	 * @param  ?string $user_picture_path      The User Display Name.
	 */
	public function __construct(
		string $user_id,
		string $application_name,
		?int $timestamp = null,
		?int $id,
		string $user_picture_url = null,
		string $user_display_name = null,
		string $user_picture_path = null
	) {
		$this->user_id           = $user_id;
		$this->application_name  = $application_name;
		$this->timestamp         = $timestamp;
		$this->id                = $id;
		$this->user_picture_url  = $user_picture_url;
		$this->user_display_name = $user_display_name;
		$this->user_picture_path = $user_picture_path;
	}

	/**
	 * Create from a JSON object
	 *
	 * @param string $json The JSON representation of the object.
	 *
	 * @return ?FileSync
	 */
	public static function from_json( string $json ): ?self {
		$data = json_decode( $json );

		if ( $data ) {
			return new self(
				$data->user_id,
				$data->application_name,
				$data->timestamp,
				$data->id,
				$data->user_picture_url,
				$data->user_display_name,
				$data->user_picture_path,
			);
		}
		return null;
	}

	/**
	 * Convert to JSON
	 * Used for caching.
	 *
	 * @return string
	 */
	public function to_json(): string {
		return wp_json_encode(
			array(
				'user_id'           => $this->user_id,
				'application_name'  => $this->application_name,
				'timestamp'         => $this->timestamp,
				'id'                => $this->id,
				'user_picture_url'  => $this->user_picture_url,
				'user_display_name' => $this->user_display_name,
				'user_picture_path' => $this->user_picture_path,
			)
		);
	}

	/**
	 * Get the record id
	 *
	 * @return ?int
	 */
	public function get_id(): ?int {
		return $this->id;
	}

	/**
	 * Set the record id
	 *
	 * @param int $id - userid.
	 *
	 * @return $this
	 */
	public function set_id( int $id ): self {
		$this->id = $id;

		return $this;
	}

	/**
	 * Gets Cart ID.
	 *
	 * @return string
	 */
	public function get_user_id(): string {
		return $this->user_id;
	}

	/**
	 * Set the Cart ID
	 *
	 * @param string $user_id The new Cart id.
	 *
	 * @return $this
	 */
	public function set_user_id( string $user_id ): self {
		$this->user_id = $user_id;

		return $this;
	}

	/**
	 * Gets Room Name.
	 *
	 * @return string
	 */
	public function get_application_name(): string {
		return $this->application_name;
	}

	/**
	 * Set the Room Name
	 *
	 * @param string $application_name - the Room name.
	 *
	 * @return $this
	 */
	public function set_application_name( string $application_name ): self {
		$this->application_name = $application_name;

		return $this;
	}

	/**
	 * Gets Timestamp.
	 *
	 * @return int
	 */
	public function get_timestamp(): int {
		return $this->timestamp;
	}

	/**
	 * Sets Timestamp.
	 *
	 * @param int $timestamp - sets the Single Product Sync state.
	 *
	 * @return RoomSync
	 */
	public function set_timestamp( int $timestamp ): self {
		$this->timestamp = $timestamp;

		return $this;
	}

	/**
	 * Gets User Picture URL.
	 *
	 * @return string
	 */
	public function get_user_picture_url(): ?string {
		return $this->user_picture_url;
	}

	/**
	 * Set the User Picture URL
	 *
	 * @param string $user_picture_url The URL of the User Picture.
	 *
	 * @return $this
	 */
	public function set_user_picture_url( ?string $user_picture_url ): self {
		$this->user_picture_url = $user_picture_url;

		return $this;
	}

	/**
	 * Gets User Display Name.
	 *
	 * @return string
	 */
	public function get_user_display_name(): ?string {
		return $this->user_display_name;
	}

	/**
	 * Set the User Display Name.
	 *
	 * @param string $user_display_name The new Cart id.
	 *
	 * @return $this
	 */
	public function set_user_display_name( string $user_display_name ): self {
		$this->user_display_name = $user_display_name;

		return $this;
	}

	/**
	 * Gets User Display Name.
	 *
	 * @return string
	 */
	public function get_user_picture_path(): ?string {
		return $this->user_picture_path;
	}

	/**
	 * Set the User Display Name.
	 *
	 * @param string $user_picture_path The User Picture Path.
	 *
	 * @return $this
	 */
	public function set_user_picture_path( ?string $user_picture_path ): self {
		$this->user_picture_path = $user_picture_path;

		return $this;
	}
}
