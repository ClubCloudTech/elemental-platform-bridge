<?php
/**
 * Sandbox Management Package
 * Managing helper functions for Sandbox
 *
 * @package ElementalPlugin\Module\Sandbox
 */

namespace ElementalPlugin\Module\Sandbox;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Sandbox\Library\SandboxShortCode;

/**
 * Class Sandbox - Main Control Function Class for Sandbox.
 */
class Sandbox {

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init(): void {

		add_shortcode( 'the_content_sand', array( Factory::get_instance( SandboxShortCode::class ), 'render_sandbox_shortcode' ) );
	}
	/**
	 * Activate Functions for Sandbox Module.
	 */
	public function activate(): void {

	}

	/**
	 * De-Activate Functions for Sandbox Module.
	 */
	public function de_activate(): void {

	}

}
