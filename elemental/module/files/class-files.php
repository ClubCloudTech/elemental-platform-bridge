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
use ElementalPlugin\Module\Files\Library\FileManagement;

/**
 * Class Membership
 */
class Files {

	const SHORTCODE_USER_FILES   = 'elemental_all_user_files';
	const SHORTCODE_PICTURE_VIEW = 'elemental_view_image_editor';
	const APPLICATION_NAME       = 'default-application';

	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		// Add User Folder Creation - and management on login.
		add_action( 'user_register', array( Factory::get_instance( FileManagement::class ), 'create_user_dir' ) );
		add_action( 'wp_login', array( Factory::get_instance( FileManagement::class ), 'check_then_create_user_dir' ) );

		add_shortcode( self::SHORTCODE_USER_FILES, array( Factory::get_instance( FileManagement::class ), 'render_user_file_page' ) );
		add_shortcode( self::SHORTCODE_PICTURE_VIEW, array( Factory::get_instance( FileManagement::class ), 'render_picture_page' ) );

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
			plugins_url( '/js/protect-username.js', __FILE__ ),
			null,
			$plugin_version,
			true
		);
	}

	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {
		Factory::get_instance( FileSyncDao::class )->install_room_presence_table();
	}

	/**
	 * Dectivation Functions for Membership.
	 */
	public function de_activate() {

	}

}


