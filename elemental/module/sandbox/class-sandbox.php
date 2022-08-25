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
use ElementalPlugin\Module\Sandbox\Library\SandBoxAjaxFilters;
use ElementalPlugin\Module\Sandbox\Library\SandBoxHelpers;
use ElementalPlugin\Module\Sandbox\Library\SandboxRender;

/**
 * Class Sandbox - Main Control Function Class for Sandbox.
 */
class Sandbox {

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init(): void {

		Factory::get_instance( SandboxRender::class )->init();
		$this->register_scripts_styles();
		$this->initialise_sandbox_ajax();

	}
	/**
	 * Initialise Sandbox Ajax.
	 */
	public function initialise_sandbox_ajax(): void {

		// Ajax Handling Function.
		\add_action( 'wp_ajax_elemental_sandbox_script_object', array( Factory::get_instance( SandBoxAjaxFilters::class ), 'sandbox_ajax_handler' ), 10, 2 );
		// Hooks from Ajax Handling Function.
		add_filter( 'elemental_sandbox_ajax_response', array( Factory::get_instance( SandBoxAjaxFilters::class ), 'ajax_tab_sort' ), 10, 2 );
		add_filter( 'elemental_sandbox_ajax_response', array( Factory::get_instance( SandBoxAjaxFilters::class ), 'ajax_login_process' ), 10, 2 );
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
	private function register_scripts_styles(): void {
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		// Tabbed Frame Navigation.
		wp_register_script(
			'elemental-advanced-tabs',
			plugins_url( '/assets/js/tabmanage.js', __FILE__ ),
			array( 'jquery' ),
			$plugin_version,
			true
		);

		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_sandbox' ),

		);

		wp_localize_script(
			'elemental-advanced-tabs',
			'elemental_sandbox_script_object',
			$script_data_array
		);

		wp_register_script(
			'jquery-ui',
			'https://code.jquery.com/ui/1.13.2/jquery-ui.js',
			array( 'jquery' ),
			Factory::get_instance( Version::class )->get_plugin_version(),
			true
		);

		// Tabbed Frame Navigation.
		wp_register_script(
			'elemental-sandbox-tabs',
			plugins_url( '/assets/js/sandbox-tabbed.js', __FILE__ ),
			array( 'jquery-ui' ),
			$plugin_version,
			true
		);

		wp_register_style(
			'elemental-sandbox-style',
			plugins_url( '/assets/css/sandbox.css', __FILE__ ),
			null,
			$plugin_version
		);
	}
}
