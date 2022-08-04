<?php
/**
 * Manages getting AJAX requests
 *
 * @package elemental/library/class-ajax.php
 */

declare( strict_types=1 );

namespace ElementalPlugin\Library;

/**
 * Class Ajax
 */
class Ajax {
	/**
	 * Get a string from the $_GET
	 *
	 * @param string $name    The name of the field.
	 * @param string $default The default value.
	 *
	 * @return string
	 */
	public function get_text_parameter( string $name, string $default = '' ): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return \sanitize_text_field( \wp_unslash( $_REQUEST[ $name ] ?? $default ) );
	}
	/**
	 * Get a string from the $_POST
	 *
	 * @param string $name The name of the field.
	 *
	 * @return string
	 */
	public function get_string_parameter( string $name ): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing --Nonce is verified in parent function
		return \sanitize_text_field( \wp_unslash( $_POST[ $name ] ?? '' ) );
	}

	/**
	 * Get Decrypt a Field Name from the $_POST
	 *
	 * @param string $name The name of the field to decode.
	 *
	 * @return string
	 */
	public function get_decrypted_parameter( string $name ): ?string {
		// phpcs:ignore -- not sanitized as upstream function will sanitize automatically on the decrypt call and sanitize will remove encoded special characters.
		$field = $_POST[ $name ] ?? '';
		return Factory::get_instance( Encryption::class )->decrypt_string( $field );
	}

	/**
	 * Get a integer from the $_POST
	 *
	 * @param string   $name    The name of the field.
	 * @param ?integer $default The default value.
	 *
	 * @return ?integer
	 */
	public function get_integer_parameter( string $name, int $default = null ): ?int {
		$value = $this->get_string_parameter( $name );

		if ( '' !== $value ) {
			return (int) $value;
		}

		return $default;
	}

	/**
	 * Get a value from a $_POST radio field
	 *
	 * @param string $name The name of the field.
	 *
	 * @return string
	 */
	public function get_radio_parameter( string $name ): string {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing --Nonce is verified in parent function
		return \sanitize_text_field( \wp_unslash( $_POST[ $name ] ?? '' ) );
	}

	/**
	 * Get an array from the $_POST
	 *
	 * @param string $name The name of the field.
	 *
	 * @return array
	 */
	public function get_string_list_parameter( string $name ): array {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$options = $_POST[ $name ] ?? array();

		$return = array();

		foreach ( $options as $option ) {
			$value = \trim( \sanitize_text_field( \wp_unslash( $option ) ) );
			if ( $value ) {
				$return[] = $value;
			}
		}

		return $return;
	}

	/**
	 * Get a boolean value from a $_POST checkbox
	 *
	 * @param string $name    The name of the field.
	 * @param bool   $default The default value.
	 *
	 * @return bool
	 */
	public function get_checkbox_parameter( string $name, bool $default = false ): bool {
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ?? false ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing --Nonce is verified in parent function
			return \sanitize_text_field( \wp_unslash( $_POST[ $name ] ?? '' ) ) === 'on';
		} else {
			return $default;
		}
	}

	/** MyVideoRoom Admin Ajax Support.
	 * Handles ajax calls from backend wp-admin pages
	 *
	 * @return mixed
	 */
	public function elemental_admin_ajax_handler() {
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_admin_ajax', 'security', false );

		$action_taken = $this->get_string_parameter( 'action_taken' );

		switch ( $action_taken ) {
			/*
			* Update Maintenance Settings.
			*
			*/
			case 'save_maintenance_settings':
				$template_update = Factory::get_instance( self::class )->get_string_parameter( 'template_update' );
				// Listeners Hook into this filter to pick up Ajax post and process in own module.
				$response = \apply_filters( 'elemental_maintenance_result_listener', $response );
				return \wp_send_json( $response );

		}
		die();
	}
}
