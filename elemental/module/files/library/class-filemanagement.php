<?php
/**
 * File Management Function Handlers for Elemental.
 *
 * @package /module/files/library/class-filemanagement.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Files;
use ElementalPlugin\Module\UltimateMembershipPro\ElementalUMP;

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
	 * Checks if a user has New Notification Status.
	 *
	 * @param string $user_id - The WP user login.
	 *
	 * @return string
	 */
	public function check_user_notification( string $user_id ): bool {
		$record = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_id, Files::STATUS_FIELD_MESSAGE );
		if ( $record ) {
			return true;
		}
		return false;
	}
	/**
	 * Clears a user Notification Status.
	 *
	 * @param string $user_id - The WP user login.
	 *
	 * @return void
	 */
	public function clear_user_notification( string $user_id ): void {

		$record = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_id, Files::STATUS_FIELD_MESSAGE );
		if ( $record ) {
			Factory::get_instance( FileSyncDao::class )->delete( $record );
		}

	}
	/**
	 * Get User Upload URL.
	 *
	 * @param int $user_id = null - The user_id if that is needed.
	 *
	 * @return string
	 */
	public function get_user_upload_url( int $user_id = null ): string {
		if ( $user_id ) {
			$user_info = get_user_by( 'id', $user_id );
			$output    = $user_info->user_login;
		} else {
			$user_info = \wp_get_current_user();
			$output    = $user_info->user_login;
		}

		$site_url  = \get_site_url();
		$site_url .= '/wp-content/uploads/user_dirs/' . $output . '/';
		return $site_url;
	}

	/**
	 * Get User Upload File location.
	 *
	 * @param int $user_id = null - The user_id if that is needed.
	 *
	 * @return string
	 */
	public function get_user_upload_folder( int $user_id = null ): string {
		if ( $user_id ) {
			$user_info = get_user_by( 'id', $user_id );
		} else {
			$user_info = \wp_get_current_user();
		}
		// Check Folder Exists, create it- if it doesnt.
		$this->check_then_create_user_dir( $user_info->user_login );

		$site_path = \ABSPATH . 'wp-content/uploads/user_dirs/' . $user_info->user_login . '/';

		return $site_path;
	}


	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param int $user_id - The User id passed in.
	 *
	 * @return void
	 */
	public function create_user_dir( int $user_id ) {
		$user_info = get_userdata( $user_id );
		// Only create if user folder does not exist.
		if ( ! $this->check_user_dir_exists( $user_info->user_login ) ) {
			$user_dir = $this->get_user_upload_directory( $user_info->user_login );
			wp_mkdir_p( $user_dir );
		}
	}

	/**
	 * Make a User Home Folder on user creation
	 *
	 * @param $user_login - The User login passed in.
	 *
	 * @return bool
	 */
	public function check_user_dir_exists( string $user_login ): bool {
		$user_info = \get_user_by( 'email', $user_login );
		if ( ! $user_info ) {
			$user_info = \wp_get_current_user();
		}
		$user_dir  = $this->get_user_upload_directory( $user_info->user_login );
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
	 * @param string $dir - The directory passed in.
	 * @param int    $user_id = null - The user_id if that is needed.
	 *
	 * @return ?array
	 */
	public function get_file_list( string $dir, int $user_id = null ) :?array {

		require_once ABSPATH . 'wp-admin/includes/file.php';
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		$dir_objects  = \list_files( $dir );
		$return_array = array();

		foreach ( $dir_objects as $object ) {

			// Skip hidden files.
			if ( '.' === $object[0] ) {
				continue;
			} else {
				if ( is_readable( $object ) ) {
					$name   = \basename( $object );
					$retval = array(
						'name'              => $name,
						'url'               => $this->get_user_upload_url( $user_id ) . $name,
						'path'              => Factory::get_instance( Encryption::class )->encrypt_string( $this->get_user_upload_folder( $user_id ) . $name ),
						'type'              => mime_content_type( $object ),
						'user_id_encrypted' => Factory::get_instance( Encryption::class )->encrypt_string( strval( $user_id ) ),
						'size'              => round( filesize( $object ) / 1024, 1 ) . 'KB',
						'lastmod'           => filemtime( $object ),
					);
					array_push( $return_array, $retval );

				}
			}
		}
		return $return_array;
	}
	/**
	 * Delete a File if Exists
	 *
	 * @param string $filename - The fully qualified path to delete file from.
	 *
	 * @return bool
	 */
	public function delete_file_if_exists( string $filename ): bool {
		if ( file_exists( $filename ) ) {
			$status = unlink( $filename );
			return $status;
		} else {
			return false;
		}
	}
	/**
	 * Shortcode Handler for Membership Config Page.
	 *
	 *  @param array $atts = the attributes.
	 *  @return ?string
	 */
	public function render_user_file_page_shortcode( $atts = array() ) {
		// User_id in attributes.
		if ( ! isset( $atts['user_id'] ) ) {

			if ( \is_user_logged_in() ) {
				$user_id = \get_current_user_id();
				$this->clear_user_notification( $user_id );
			} else {
				return null;
			}
			// Atts has ID.
		} else {
			$user_id = intval( $atts['user_id'] );
		}

		return $this->render_user_file_page( $user_id );
	}

	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 *
	 * @param int $user_id - the optional User ID to process.
	 * @return string
	 */
	public function render_user_file_page( int $user_id = null ): string {
		$this->enqueue_scripts_user_management();
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		} else {
			$user_passed_in = true;
		}
		$user = get_user_by( 'id', $user_id );

		$encrypted_user_id = Factory::get_instance( Encryption::class )->encrypt_string( \strval( $user_id ) );
		$nonce             = \wp_create_nonce( FileAjax::DELETE_FILE_REQUEST . strval( $user_id ) );

		if ( true === $user_passed_in ) {
			$user_object = get_user_by( 'id', $user_id );
		} elseif ( \is_user_logged_in() ) {
			$user_object = wp_get_current_user();
		} else {
			return 'No User Logged in';
		}
		$dir       = $this->get_user_upload_directory( $user_object->user_login );
		$file_list = $this->get_file_list( $dir, $user_id );

		return ( include __DIR__ . '/../views/files/table-file-views.php' )( $file_list, $encrypted_user_id, $nonce, $user );

	}
	/**
	 * Runs required Scripts to start.
	 *
	 * @return void
	 */
	public function enqueue_scripts_user_management(): void {
		wp_enqueue_style( 'elemental-admin-css' );
		wp_enqueue_script( 'elemental-webcam-stream-js' );
		wp_enqueue_script( 'elemental-protect-username' );
		wp_enqueue_style( 'elemental-template' );
		wp_enqueue_style( 'dashicons' );

	}


	/**
	 * Render Picture Page
	 *
	 * @return string - Welcome Picture Page.
	 */
	public function render_picture_page(): string {
		$this->enqueue_scripts_user_management();
		$user_id          = get_current_user_id();
		$application_name = Files::APPLICATION_NAME;
		$user_info        = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_id, $application_name );

		$encrypted_user_id = Factory::get_instance( Encryption::class )->encrypt_string( \strval( $user_id ) );

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
				// Set UMP Avatar Picture.
				Factory::get_instance( ElementalUMP::class )->update_ump_avatar_hook( $current_user->id, $avatar );

			}

			if ( $user_info && ! $user_info->get_user_display_name() ) {
				$user_display = $current_user->display_name;
				$user_info->set_user_display_name( $user_display );
			}
		}
			$render = require __DIR__ . '/../views/pictures/view-picture-register.php';

			return $render( $user_info, $encrypted_user_id );
	}

	/**
	 * Room Picture and Name Update - changes Avatar Picture and sets User Meeting Display Name.
	 *
	 * @param string $file_path The Display Name the User wants to use.
	 * @param string $file_url The Display Name the User wants to use.
	 * @param string $display_name The Display Name the User wants to use.
	 *
	 * @return bool
	 */
	public function user_picture_name_update( string $file_path = null, string $file_url = null, string $display_name = null ):bool {
		$user_id = \get_current_user_id();

		$application_name = Files::APPLICATION_NAME;
		$current_object   = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_id, $application_name );
		if ( ! $current_object ) {
			$current_object = Factory::get_instance( FileSyncDao::class )->create_new_user_storage_record();
		}
		if ( $file_path && $file_url ) {

			$current_object->set_user_picture_url( $file_url );
			$current_object->set_user_picture_path( $file_path );
		}
		if ( $display_name ) {
			$current_object->set_user_display_name( $display_name );
		}

		$return = Factory::get_instance( FileSyncDao::class )->update( $current_object );
		if ( $return ) {
			return true;
		} else {
			return false;
		}
	}
}
