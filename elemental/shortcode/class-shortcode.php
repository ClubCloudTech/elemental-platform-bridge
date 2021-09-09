<?php
/**
 * Abstract class for all shortcodes
 *
 * @package MyVideoRoomExtrasPlugin\ValueObjects
 */

declare(strict_types=1);

namespace MyVideoRoomExtrasPlugin\Shortcode;

/**
 * Abstract Shortcode
 */
abstract class Shortcode {

	/**
	 * Render and return a shortcode
	 *
	 * @param string $shortcode The shortcode.
	 * @param array  $params Key=>Value dictionary of params for the shortcode.
	 *
	 * @return string
	 */
	protected function render_shortcode( string $shortcode, array $params, string $text_safe = null ): string {
		$output = $shortcode;

		foreach ( $params as $key => $value ) {
			if ( is_bool( $value ) ) {
				if ( $value ) {
					$output .= ' ' . $key . '=true';
				} else {
					$output .= ' ' . $key . '=false';
				}
			} else {

				$output .= ' ' . $key . '="' . $value . '"';
			}
		}
		// Function Change to allow just the return of the Shortcode text rather than execution.
		if ( 'shortcode-view-only' === $text_safe ) {
			return $output;
		}

		$output = '[' . $output . ']';

		$return = \do_shortcode( $output );

		$dev_mode = ( $_GET['dev'] ?? false ) === 'true';

		if ( $dev_mode ) {
			$return = "<!-- ${output} -->\n${output}";
		}

		return $return;

	}
}
