<?php
/**
 * File Package
 * Application for File Management
 *
 * @package ElementalPlugin\Module\Files
 */

namespace ElementalPlugin\Module\Files;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Library\FileAjax;
use ElementalPlugin\Module\Files\Library\FileHooks;
use ElementalPlugin\Module\Files\Library\FileManagement;

/**
 * Class Files
 */
class Files {

	const SHORTCODE_USER_FILES   = 'elemental_all_user_files';
	const SHORTCODE_PICTURE_VIEW = 'elemental_view_image_editor';
	const APPLICATION_NAME       = 'default-application';
	const AJAX_FILE_NONCE        = 'handle_file_upload_AMFb';
	const STATUS_FIELD_MESSAGE   = 'new-message-state';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		// Add User Folder Creation - and management on login.
		add_action( 'user_register', array( Factory::get_instance( FileManagement::class ), 'create_user_dir' ) );
		add_action( 'wp_login', array( Factory::get_instance( FileManagement::class ), 'check_then_create_user_dir' ) );

		// Shortcodes.
		add_shortcode( self::SHORTCODE_USER_FILES, array( Factory::get_instance( FileManagement::class ), 'render_user_file_page_shortcode' ) );
		add_shortcode( self::SHORTCODE_PICTURE_VIEW, array( Factory::get_instance( FileManagement::class ), 'render_picture_page' ) );

		// Ajax for Pictures and Files.
		\add_action( 'wp_ajax_elemental_base_ajax', array( Factory::get_instance( FileAjax::class ), 'file_upload_handler' ), 10, 2 );

		// Action for email notification.
		\add_action( 'elemental_file_upload', array( Factory::get_instance( FileHooks::class ), 'notify_user_file_change_hook' ), 10, 1 );
		// Action for User Notification Icon.
		\add_action( 'elemental_file_upload', array( Factory::get_instance( FileHooks::class ), 'new_user_file_notification_hook' ), 10, 2 );

		$this->register_scripts_styles();

	}
	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	private function register_scripts_styles() {
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		wp_register_script(
			'elemental-protect-username',
			plugins_url( '/assets/js/protect-username.js', __FILE__ ),
			null,
			$plugin_version,
			true
		);
		// Register Script Ajax Upload.
		\wp_register_script(
			'elemental-webcam-stream-js',
			plugins_url( '/assets/js/elemental-stream.js', __FILE__ ),
			array( 'jquery' ),
			Factory::get_instance( Version::class )->get_plugin_version(),
			true
		);
		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( self::AJAX_FILE_NONCE ),

		);
		wp_localize_script(
			'elemental-webcam-stream-js',
			'elemental_base_ajax',
			$script_data_array
		);
	}

	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {
		Factory::get_instance( FileSyncDao::class )->install_file_sync_table();
	}

	/**
	 * Dectivation Functions for Membership.
	 */
	public function de_activate() {

	}

}


