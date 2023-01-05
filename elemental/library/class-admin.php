<?php
/**
 * Manages the configuration settings for the video plugin .
 *
 * @package library/class-admin.php
 */

declare(strict_types=1);

namespace ElementalPlugin\Library;

use ElementalPlugin\Module\Sandbox\Sandbox;

/**
 * Class Admin
 */
class Admin {

	const SHORTCODE_TAG = 'elemental_';
	/**
	 * Initialise menu items.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'wp_ajax_elemental_admin_ajax', array( Factory::get_instance( Ajax::class ), 'elemental_admin_ajax_handler' ), 10, 2 );
		add_shortcode( self::SHORTCODE_TAG . 'proxytest', array( $this, 'proxy_test_function' ) );
		$this->register_scripts();
		Factory::get_instance( Sandbox::class )->initialise_sandbox_ajax();
	}

	/**
	 * Add the admin menu page.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			'Elemental Configuration',
			'Elemental',
			'manage_options',
			'elemental',
			array( $this, 'create_elemental_admin_page' ),
			'dashicons-menu-alt3'
		);
	}
	/**
	 * Create the extra admin page contents.
	 */
	public function create_elemental_admin_page(): void {
     // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized,WordPress.Security.NonceVerification.Recommended -- Not required
		$active_tab = $_GET['tab'] ?? null;
		Factory::get_instance( Sandbox::class )->initialise_sandbox_ajax();

		\wp_enqueue_script( 'elemental-admin-ajax-js' );
		\wp_enqueue_script( 'elemental-advanced-tabs' );
		\wp_enqueue_script( 'jquery-ui' );

		add_action(
			'wp_enqueue_scripts',
			function() {
				wp_enqueue_style( 'dashicons' );
			}
		);
				\wp_enqueue_style(
					'elemental-admin-css',
					\plugins_url( '/../assets/css/admin.css', __FILE__ ),
					false,
					Factory::get_instance( Version::class )->get_plugin_version(),
				);

		$tabs = array(
			'admin-settings-membership' => \esc_html__( 'Membership Settings', 'elementalplugin' ),
			'admin-settings-plugin'     => \esc_html__( 'Plugin Settings', 'elementalplugin' ),
			'admin-settings-sandbox'    => \esc_html__( 'Sandbox Settings', 'elementalplugin' ),
		);

		$tabs = \apply_filters( 'elemental_admin_menu', $tabs );

		if ( ! $active_tab || ! isset( $tabs[ $active_tab ] ) ) {
			$active_tab = array_key_first( $tabs );
		}

		$messages      = array();
		$header_render = include __DIR__ . '/../views/admin/header.php';
		//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $header_render( $active_tab, $tabs, $messages );

		$render = include __DIR__ . '/../views/admin/' . $active_tab . '.php';
		//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $render();
	}

	/**
	 * A Function for  to Proxy Functions to Front End for Testing
	 * Used to Setup basic settings
	 * Used to run whatever is in here from the /test page
	 *
	 * @return null
	 */
	public function proxy_test_function() {

		return null;
	}

	/**
	 * Register Admin Page Scripts
	 *
	 * @return void
	 */
	public function register_scripts() {

			// Enqueue Script Ajax Handling.
			\wp_register_script(
				'elemental-admin-ajax-js',
				\plugins_url( '../assets/js/mvradminajax.js', \realpath( __FILE__ ) ),
				array( 'jquery' ),
				Factory::get_instance( Version::class )->get_plugin_version(),
				true
			);
			// Localize script Ajax Upload.
			$script_data_array = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'elemental_admin_ajax' ),

			);
			wp_localize_script(
				'elemental-admin-ajax-js',
				'elemental_admin_ajax',
				$script_data_array
			);
	}
}

