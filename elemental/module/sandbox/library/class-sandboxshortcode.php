<?php
/**
 * Sandbox Shortcode Controller.
 *
 * @package module/sandbox/library/class-sandboxshortcode.php
 */

namespace ElementalPlugin\Module\Sandbox\Library;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class SandboxShortCode {


	/**
	 * Render shortcode to render Sandbox Control View
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sandbox_shortcode( $attributes = array() ): ?string {
		if ( ! \is_user_logged_in() ) {

			$args       = array(
				'echo'     => false,
				'redirect' => get_permalink( get_the_ID() ),
				'remember' => true,
			);
			return wp_login_form( $args );
		}
		$current_user = wp_get_current_user();

		return $this->sandbox_shortcode_worker( $current_user );
	}


	/**
	 * Sandbox Shortcode Worker Function
	 * Handles the rendering of the shortcode for Sandbox Control.
	 *
	 * @param  object $current_user - The signed in user object.
	 * @return ?string
	 */
	public function sandbox_shortcode_worker( object $current_user ): ?string {
		$encryption_key = get_option('northbridge_email_key');


		
		$render = ( require __DIR__ . '/../views/view-sandbox-control.php' );
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
		return $render( $current_user );
	}


}
