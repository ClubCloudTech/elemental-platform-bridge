<?php
/**
 * User Preference Entity Object
 *
 * @package entity/class-token.php
 */

namespace ElementalPlugin\Entity;

/**
 * Class Token
 */
class Token {

	/**
	 * User_id
	 *
	 * @var int $user_id
	 */
	private int $user_id;


	/**
	 * Destination URL
	 *
	 * @var ?string $user_token
	 */
	private ?string $user_token;


	/**
	 * Timestamp
	 *
	 * @var int $timestamp - the Timestamp.
	 */
	private ?int $timestamp;

	/**
	 * Token constructor.
	 *
	 * @param int     $user_id                 Userid.
	 * @param ?string $user_token         URL to store instead of base.
	 * @param ?int    $timestamp               Last Updated Timestamp.
	 */
	public function __construct(
		int $user_id,
		string $user_token = null,
		?int $timestamp = null
	) {
		$this->user_id    = $user_id;
		$this->user_token = $user_token;
		$this->timestamp  = $timestamp;
	}

	/**
	 * Create from a JSON object
	 * Note - Original Room Name does not get stored in JSON as it does not get stored in database.
	 *
	 * @param string $json The JSON representation of the object.
	 *
	 * @return ?\MyVideoRoomPlugin\Entity\Token
	 */
	public static function from_json( string $json ): ?self {
		$data = json_decode( $json );

		if ( $data ) {
			return new self(
				$data->user_id,
				$data->user_token,
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
				'user_id'    => $this->user_id,
				'user_token' => $this->user_token,
				'timestamp'  => $this->timestamp,
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
	 * Gets User Token Setting.
	 *
	 * @return string
	 */
	public function get_user_token(): string {
		return $this->user_token;
	}

	/**
	 * Sets User Token Setting.
	 *
	 * @param string $user_token - sets Token.
	 *
	 * @return int
	 */
	public function set_user_token( string $user_token ): Token {
		$this->user_token = $user_token;

		return $this;
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
	public function set_timestamp( int $timestamp ): Token {
		$this->timestamp = $timestamp;

		return $this;
	}
}
