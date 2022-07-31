<?php
/**
 * Sandbox Management Package
 * Managing helper functions for Sandbox
 *
 * @package ElementalPlugin\Module\Sandbox
 */

namespace ElementalPlugin\Module\Sandbox;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Sandbox\DAO\SandBoxDao;
use ElementalPlugin\Module\Sandbox\Library\SandBoxHelpers;
use ElementalPlugin\Module\Sandbox\Library\SandboxRender;
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
		Factory::get_instance( SandboxRender::class )->init();
		$this->register_scripts();
	}
	/**
	 * Activate Functions for Sandbox Module.
	 */
	public function activate(): void {
		Factory::get_instance( SandBoxDao::class )->install_sandbox_control_table();
	}

	/**
	 * De-Activate Functions for Sandbox Module.
	 */
	public function de_activate(): void {

	}

	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_sandbox_config_page(): string {
		\wp_enqueue_script( 'elemental-membership-js' );
		$items_sandbox = Factory::get_instance( SandBoxHelpers::class )->get_sandbox_rooms();
		$items_sandbox = Factory::get_instance( SandBoxHelpers::class )->sort_sandbox_rooms( $items_sandbox );
		return ( include __DIR__ . '/views/admin-table-output.php' )( $items_sandbox );
	}

	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	private function register_scripts(): void {

		// Tabbed Frame Navigation.
		wp_register_script(
			'elemental-advanced-tabs',
			plugins_url( '/assets/js/tabmanage.js', __FILE__ ),
			array( 'jquery' ),
			Factory::get_instance( Version::class )->get_plugin_version(),
			true
		);

		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_membership' ),

		);

		wp_localize_script(
			'elemental-advanced-tabs',
			'elemental_membershipadmin_ajax',
			$script_data_array
		);
	}

}
