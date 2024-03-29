<?php
/**
 * Generate Encryption Functions.
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

/**
 * Class Encryption
 */
class Encryption {

	/**
	 * Cipher Variable.
	 *
	 * @var string $openssl_cipher_name - the Cypher Type. OpenSSL Cipher
	 */
	private static $openssl_cipher_name = 'aes-128-cbc';

	/**
	 * Cipher Variable.
	 *
	 * @var int $cipher_key_len - the Cypher Key Length.
	 */
	private static $cipher_key_len = 16; // 128 bits


	const IV  = 'D%hfRKiBKjd&4WBj';
	const KEY = '3gAW!xqPyNp#)kY!';

	/**
	 * Get 11 digit integer based on WordPress Nonce Salt
	 *
	 * @return int
	 */
	private static function get_meeting_nonce(): int {
		return (int) substr( base_convert( NONCE_SALT, 16, 10 ), 0, 11 );
	}

	/**
	 * Create a unique hash based on the user and nonce
	 *
	 * @param int $user_id The WordPress user id.
	 *
	 * @return string
	 */
	public static function get_meeting_hash_from_user_id( int $user_id ): string {
		$input = $user_id ^ self::get_meeting_nonce();

		$items = array_map( 'intval', str_split( (string) $input ) );
		$seed  = array_pop( $items );

		$items = self::seeded_shuffle( $items, $seed + substr( self::get_meeting_nonce(), 3 ) );

		$items[] = $seed;

		$number = implode( '', $items );

		$number_sections = array(
			substr( $number, 0, 3 ),
			substr( $number, 3, 4 ),
			substr( $number, 7 ),
		);

		return implode( '-', $number_sections );
	}

	/**
	 * Deterministic shuffle an array based on a seed
	 *
	 * @param array   $items Array to shuffle.
	 * @param integer $seed  Seed for the randomizer.
	 *
	 * @return mixed
	 */
	private static function seeded_shuffle( array $items, int $seed ): array {
        // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_seeding_mt_srand
		mt_srand( $seed );

		for ( $i = count( $items ) - 1; $i > 0; $i -- ) {
            // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_mt_rand
			$j = mt_rand( 0, $i );

			list( $items[ $i ], $items[ $j ] ) = array( $items[ $j ], $items[ $i ] );
		}

		return $items;
	}

	/**
	 * Retrieve the user id from a meeting hash
	 *
	 * @param string $hash The hash generated by self::get_meeting_hash_from_user_id.
	 *
	 * @return int
	 */
	public static function get_user_id_from_meeting_hash( string $hash ): int {
		$items = str_split( (string) str_replace( '-', '', $hash ) );
		$seed  = array_pop( $items );

		$items = self::seeded_unshuffle( $items, $seed + substr( self::get_meeting_nonce(), 3 ) );

		$items[] = $seed;

		$number = implode( '', $items );

		return $number ^ self::get_meeting_nonce();
	}

	/**
	 * Un-shuffle an deterministically shuffled array based on a seed
	 *
	 * @param array $items A list of items.
	 * @param int   $seed  The seed used to shuffle the items.
	 *
	 * @return array
	 */
	private static function seeded_unshuffle( array $items, int $seed ): array {
        // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_seeding_mt_srand
		mt_srand( $seed );

		$indices = array();
		for ( $i = count( $items ) - 1; $i > 0; $i -- ) {

            // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_mt_rand
			$indices[ $i ] = mt_rand( 0, $i );
		}

		foreach ( array_reverse( $indices, true ) as $i => $j ) {
			list( $items[ $i ], $items[ $j ] ) = array( $items[ $j ], $items[ $i ] );
		}

		return $items;
	}
	/**
	 * Encrypts a plain text string
	 * initialization vector(IV) has to be the same when encrypting and decrypting
	 *
	 * @param string $encrypt_string - string to encrypt or decrypt.
	 *
	 * @return string
	 */
	public function encrypt_string( string $encrypt_string ) {
		$output         = false;
		$encrypt_method = 'AES-256-CBC';
		$secret_key     = self::KEY;
		$secret_iv      = self::IV;

		// Hash entry point.
		$key = hash( 'sha256', $secret_key );

		// iv - encrypt method AES-256-CBC expects 16 bytes.

		$iv     = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		$output = openssl_encrypt( $encrypt_string, $encrypt_method, $key, 0, $iv );
		$output = base64_encode( $output );

		return $output;
	}

	/**
	 * Decrypts a plain text string
	 * initialization vector(IV) has to be the same when encrypting and decrypting
	 *
	 * @param string $decrypt_string - string to encrypt or decrypt.
	 *
	 * @return string
	 */
	public function decrypt_string( string $decrypt_string ) {
		$output         = false;
		$encrypt_method = 'AES-256-CBC';
		$secret_key     = self::KEY;
		$secret_iv      = self::IV;

		// Hash entry point.
		$key = hash( 'sha256', $secret_key );

		// iv - encrypt method AES-256-CBC expects 16 bytes.

		$iv     = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		$output = openssl_decrypt( base64_decode( $decrypt_string ), $encrypt_method, $key, 0, $iv );

		return $output;
	}

	/**
	 * Encrypt data using AES Cipher (CBC) with 128 bit key
	 *
	 * @param string $data - data to encrypt.
	 * @param string $key - key to use should be 16 bytes long (128 bits).
	 * @param string $iv - initialization vector.
	 * @return encrypted data in base64 encoding with iv attached at end after a :.
	 */
	public static function encrypt( string $data, string $key = null, string $iv = null ) {
		if ( ! $key ) {
			$key = self::KEY;
		}
		if ( ! $iv ) {
			$iv = self::IV;
		}
		if ( strlen( $key ) < self::$cipher_key_len ) {
			$key = str_pad( "$key", self::$cipher_key_len, '0' ); // 0 pad to len 16.
		} elseif ( strlen( $key ) > self::$cipher_key_len ) {
			$key = substr( $key, 0, self::$cipher_key_len ); // truncate to 16 bytes.
		}

		$encoded_encrypted_data = base64_encode( openssl_encrypt( $data, self::$openssl_cipher_name, $key, OPENSSL_RAW_DATA, $iv ) );
		$encoded_iv             = base64_encode( $iv );
		$encrypted_payload      = $encoded_encrypted_data . ':' . $encoded_iv;

		return $encrypted_payload;

	}

}
