<?php
/**
 * User Preference Entity Object
 *
 * @package entity/class-uservideopreference.php
 */

namespace ElementalPlugin\Entity;

/**
 * Class UserPreference
 */
class UserPreference {

	/**
	 * User_id
	 *
	 * @var int $user_id
	 */
	private int $user_id;

	/**
	 * Tab_name
	 *
	 * @var string $tab_display_name
	 */
	private string $tab_display_name;

	/**
	 * Layout_id
	 *
	 * @var ?string $column_priority
	 */
	private ?string $column_priority;

	/**
	 * Reception_id
	 *
	 * @var ?string $pathway_id
	 */
	private ?string $pathway_id;

	/**
	 * Reception_enabled
	 *
	 * @var bool $pathway_enabled
	 */
	private bool $pathway_enabled;


	/**
	 * Destination URL
	 *
	 * @var ?string $destination_url
	 */
	private ?string $destination_url;


	/**
	 * Timestamp
	 *
	 * @var int $timestamp - the Timestamp.
	 */
	private ?int $timestamp;

	/**
	 * UserPreference constructor.
	 *
	 * @param int     $user_id                 Userid.
	 * @param string  $tab_display_name        Tabs Display Name.
	 * @param ?int    $column_priority         Tab Priority Order.
	 * @param int     $pathway_id              Pathway ID to map to main setting.
	 * @param bool    $pathway_enabled         If User has disabled Pathway.
	 * @param ?string $destination_url         URL to store instead of base.
	 * @param ?int    $timestamp               Last Updated Timestamp.
	 */
	public function __construct(
		int $user_id,
		string $tab_display_name,
		?int $column_priority = null,
		int $pathway_id,
		bool $pathway_enabled = false,
		string $destination_url = null,
		?int $timestamp = null
	) {
		$this->user_id          = $user_id;
		$this->tab_display_name = $tab_display_name;
		$this->column_priority  = $column_priority;
		$this->pathway_id       = $pathway_id;
		$this->pathway_enabled  = $pathway_enabled;
		$this->destination_url  = $destination_url;
		$this->timestamp        = $timestamp;
	}

	/**
	 * Create from a JSON object
	 * Note - Original Room Name does not get stored in JSON as it does not get stored in database.
	 *
	 * @param string $json The JSON representation of the object.
	 *
	 * @return ?\MyVideoRoomPlugin\Entity\UserPreference
	 */
	public static function from_json( string $json ): ?self {
		$data = json_decode( $json );

		if ( $data ) {
			return new self(
				$data->user_id,
				$data->tab_display_name,
				$data->column_priority,
				$data->pathway_id,
				$data->pathway_enabled,
				$data->destination_url,
				$data->timestamp,
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
				'user_id'          => $this->user_id,
				'tab_display_name' => $this->tab_display_name,
				'column_priority'  => $this->column_priority,
				'pathway_id'       => $this->pathway_id,
				'pathway_enabled'  => $this->pathway_enabled,
				'destination_url'  => $this->destination_url,
				'timestamp'        => $this->timestamp,
			)
		);
	}

	/**
	 * Gets User ID.
	 *
	 * @return int
	 */
	public function get_user_id(): int {
		return $this->user_id;
	}

	/**
	 * Set the user ID
	 *
	 * @param int $user_id The new user id.
	 *
	 * @return $this
	 */
	public function set_user_id( int $user_id ): self {
		$this->user_id = $user_id;

		return $this;
	}

	/**
	 * Gets Tab Display Name.
	 *
	 * @return string
	 */
	public function get_tab_display_name(): string {
		return $this->tab_display_name;
	}

	/**
	 * Sets Tab Display Name.
	 *
	 * @param string|null $tab_display_name - The unmodified room name as it appears in storage db.
	 *
	 * @return UserPreference
	 */
	public function set_tab_display_name( string $tab_display_name = null ): UserPreference {
		$this->tab_display_name = $tab_display_name;

		return $this;
	}

	/**
	 * Gets Template (layout) ID.
	 *
	 * @return ?int
	 */
	public function get_column_priority(): ?int {
		return $this->column_priority;
	}

	/**
	 * Sets Template ID.
	 *
	 * @param int|null $column_priority - the Priority of the Column Sort.
	 *
	 * @return UserPreference
	 */
	public function set_column_priority( int $column_priority = null ): UserPreference {
		$this->column_priority = $column_priority;

		return $this;
	}

	/**
	 * Gets Pathway Id.
	 *
	 * @return int
	 */
	public function get_pathway_id(): ?int {
		return $this->pathway_id;
	}

	/**
	 * Sets Pathway Id.
	 *
	 * @param int|null $pathway_id - The Template.
	 *
	 * @return UserPreference
	 */
	public function set_pathway_id( int $pathway_id = null ): UserPreference {
		$this->pathway_id = $pathway_id;

		return $this;
	}

	/**
	 * Gets Pathway Enabled State.
	 *
	 * @return bool
	 */
	public function is_pathway_enabled(): bool {
		return $this->pathway_enabled;
	}

	/**
	 * Sets Pathway Enabled State
	 *
	 * @param bool $pathway_enabled - Reception Status.
	 *
	 * @return UserPreference
	 */
	public function set_pathway_enabled( bool $pathway_enabled ): UserPreference {
		$this->pathway_enabled = $pathway_enabled;

		return $this;
	}

	/**
	 * Sets Reception Video URL.
	 *
	 * @param string|null $destination_url - The URL of Custom Video.
	 *
	 * @return UserPreference
	 */
	public function set_destination_url_setting( string $destination_url = null ): UserPreference {
		$this->destination_url = $destination_url;

		return $this;
	}

	/**
	 * Gets Custom Reception Video Status.
	 *
	 * @return ?string
	 */
	public function get_destination_url_setting(): ?string {
		return $this->destination_url;
	}

	/**
	 * Gets Timestamp.
	 *
	 * @return int
	 */
	public function get_timestamp(): ?int {
		return $this->timestamp;
	}

	/**
	 * Sets Timestamp.
	 *
	 * @param int $timestamp - sets the Timestamp.
	 *
	 * @return int
	 */
	public function set_timestamp( int $timestamp ): UserPreference {
		$this->timestamp = $timestamp;

		return $this;
	}
}
