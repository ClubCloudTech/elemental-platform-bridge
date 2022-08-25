<?php
/**
 * Rendering Controllers for Sandbox.
 *
 * @package module/sandbox/library/class-sandboxrender.php
 */

 // phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.


namespace ElementalPlugin\Module\Sandbox\Library;

use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\TabHelper;

/**
 * Class Sandbox Render.
 */
class SandboxRender extends TabHelper {

	/**
	 * Install the shortcode
	 */
	public function init() {
		add_shortcode( 'elemental_sandbox', array( $this, 'render_sandbox_shortcode' ) );
	}

	/**
	 * Render Sandbox shortcode.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sandbox_shortcode( $attributes = array() ): ?string {
		wp_enqueue_script( 'elemental-advanced-tabs' );
		wp_enqueue_script( 'elemental-sandbox-tabs' );
		wp_enqueue_style( 'elemental-sandbox-style' );
		wp_enqueue_script( 'elemental-sandbox-loading' );

		return $this->sandbox_shortcode_handler();
	}

	/**
	 * Handle Sandbox Shortcode Generation.
	 *
	 * @return string
	 */
	public function sandbox_shortcode_handler() {
		if ( ! \is_user_logged_in() ) {
			$url = \get_site_url() . '/login';
			// Javascript as wp_safe_redirect runs too late when invoked in Shortcode.
			echo '<script type="text/javascript"> window.location="' . esc_url( $url ) . '";</script>';
			die();
		}
		$user_id      = Factory::get_instance( Encryption::class )->encrypt_string( \get_current_user_id() );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = Factory::get_instance( SandBoxHelpers::class )->render_all_tabs();
		$render       = include __DIR__ . '/../views/view-sandbox-main.php';
		$site_url     = \get_site_icon_url();
		return $render( $html_library, $tabs, $user_id, $site_url );

	}
}
