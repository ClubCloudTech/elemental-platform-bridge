<?php
/**
 * File Management Function Handlers for Elemental.
 *
 * @package /module/files/library/class-filemanagement.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Files;

/**
 * Class File Management - Manages File Related Operations
 */
class FileManagement {

	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param string $user_login - The WP user login.
	 *
	 * @return string
	 */
	public function get_user_upload_directory( string $user_login ): string {
			$upload_dir = wp_upload_dir();
			$user_dir   = $upload_dir['basedir'] . '/user_dirs/' . $user_login;
			return $user_dir;
	}
	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param string $user_login - The WP user login.
	 *
	 * @return string
	 */
	public function get_user_upload_url(): string {
		$user_info = \wp_get_current_user();
		$site_url  = \get_site_url();
		$site_url .= '/wp-content/uploads/user_dirs/' . $user_info->user_login . '/';

		return $site_url;
	}


	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param int $user_id - The User id passed in.
	 *
	 * @return void
	 */
	public function create_user_dir( $user_id ) {
		$user_info = get_userdata( $user_id );
		// Only create if user folder does not exist.
		if ( ! $this->check_user_dir_exists( $user_info->user_login ) ) {
			$user_dir = get_user_upload_directory( $user_info->user_login );
			wp_mkdir_p( $user_dir );
		}
	}

	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param $user_login - The User id passed in.
	 *
	 * @return bool
	 */
	public function check_user_dir_exists( $user_login ): bool {
		$user_info = \get_user_by( 'login', $user_login );
		$user_dir  = get_user_upload_directory( $user_info->user_login );
		return is_dir( $user_dir );
	}

	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param $user_login - The User id passed in.
	 *
	 * @return void
	 */
	public function check_then_create_user_dir( $user_login ) {
		if ( ! $this->check_user_dir_exists( $user_login ) ) {
			$user_info = \get_user_by( 'login', $user_login );
			$this->create_user_dir( $user_info->id );
		}
	}
	/**
	 * Gets list of files in a folder (non recursively)
	 *
	 * @param $dir - The directory passed in.
	 *
	 * @return void
	 */
	public function get_file_list( $dir ) :?array {

		require_once ABSPATH . 'wp-admin/includes/file.php';
		$dir_objects  = \list_files( $dir );
		$return_array = array();
		foreach ( $dir_objects as $object ) {

			// skip hidden files
			if ( '.' === $object[0] ) {
				continue;
			} else {
				if ( is_readable( $object ) ) {
					$name   = \basename( $object );
					$retval = array(
						'name'    => $name,
						'url'     => $this->get_user_upload_url() . $name,
						'type'    => mime_content_type( $object ),
						'size'    => filesize( $object ),
						'lastmod' => filemtime( $object ),
					);
					array_push( $return_array, $retval );
				}
			}
		}
		return $return_array;
	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 *
	 * @return string
	 */
	public function render_user_file_page(): string {
		\wp_enqueue_style( 'elemental-admin-css' );

		if ( \is_user_logged_in() ) {
			$user_object = wp_get_current_user();
		} else {
			return 'No User Logged in';
		}
		$dir       = $this->get_user_upload_directory( $user_object->user_login );
		$file_list = $this->get_file_list( $dir );

		return ( include __DIR__ . '/../views/files/table-file-views.php' )( $file_list );

	}

	/**
	 * Render Picture Page
	 *
	 * @return string - Welcome Picture Page.
	 */
	public function render_picture_page(): string {
		wp_enqueue_script( 'elemental-webcam-stream-js' );
		wp_enqueue_style( 'elemental-admin-css' );
		wp_enqueue_script( 'elemental-protect-username' );
		wp_enqueue_style( 'elemental-template' );
		wp_enqueue_style( 'dashicons' );
		$user_session     = get_current_user_id();
		$application_name = Files::APPLICATION_NAME;
		$user_info        = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_session, $application_name );

		// Check for Blank Record of new user and create record.
		if ( ! $user_info ) {
				$user_info = Factory::get_instance( FileSyncDao::class )->create_new_user_storage_record();
		}

		// Check Logged in user Profile Picture or Display Name.
		if ( \is_user_logged_in() ) {
			$current_user = \wp_get_current_user();

			if ( $user_info && ! $user_info->get_user_picture_url() ) {
				$avatar = \get_avatar_url( $current_user );
				$user_info->set_user_picture_url( $avatar );
			}

			if ( $user_info && ! $user_info->get_user_display_name() ) {
				$user_display = $current_user->display_name;
				$user_info->set_user_display_name( $user_display );
			}
		}
			$render = require __DIR__ . '/../views/pictures/view-picture-register.php';

			return $render( $user_info );
	}


}
