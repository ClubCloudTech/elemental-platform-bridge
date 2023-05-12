<?php
/**
 * Ajax for Files and Pictures
 *
 * @package Elemental\Module\File\library\class-fileajax.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\Ajax;
use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Files;
use ElementalPlugin\Module\Membership\Library\MembershipShortCode;
use ElementalPlugin\Module\Membership\Membership;
use Error;

/**
 * Class MVRSiteVideo - Renders the Video Plugin for SiteWide Video Room.
 */
class FileAjax {

	const DELETE_APPROVED      = 'delete-approved';
	const DELETE_FILE_REQUEST  = 'delete-request';
	const DELETE_FILE_APPROVED = 'delete-approved';


	/** File Upload Ajax Support.
	 * Handles Uploads from Welcome Area, sends them to storage and updates the database.
	 *
	 * @return mixed
	 */
	public function file_upload_handler() {
		$temp_name = null;
		$response  = array();

		// Security Checks.
		check_ajax_referer( Files::AJAX_FILE_NONCE, 'security', false );
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$nonce        = Factory::get_instance( Ajax::class )->get_string_parameter( 'nonce' );
		$checksum     = Factory::get_instance( Ajax::class )->get_string_parameter( 'checksum' );
		$filecheck    = Factory::get_instance( Ajax::class )->get_string_parameter( 'filecheck' );
		$user_check   = Factory::get_instance( Ajax::class )->get_string_parameter( 'userid' );
		if ( $checksum ) {
			$user_id = Factory::get_instance( Encryption::class )->decrypt_string( $checksum );
		} elseif ( $user_check ) {
			$user_id = Factory::get_instance( Encryption::class )->decrypt_string( $user_check );
		} else {
			$user_id = get_current_user_id();
		}

		/*
		* Refresh Page Section.
		*
		*/
		if ( 'refresh_page' === $action_taken ) {
			$response['welcome'] = Factory::get_instance( FileManagement::class )->render_picture_page();

			return \wp_send_json( $response );
		}

		/*
		* Delete Me Section.
		*
		*/
		if ( 'delete_me' === $action_taken ) {
			// Process Delete.

			$application_name = Files::APPLICATION_NAME;
			$response         = array();
			$room_object      = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $user_id, $application_name );
			if ( ! $room_object ) {
				return null;
			}
			$delete = Factory::get_instance( FileSyncDao::class )->delete( $room_object );
			\do_action( 'elemental_avatar_delete', \get_current_user_id() );
			if ( $delete ) {
				$response['feedback'] = esc_html__( 'Record Deleted', 'elementalplugin' );
			} else {
				$response['feedback'] = esc_html__( 'Record Delete Failed', 'elementalplugin' );
			}
			// Delete Existing File in Uploads directory if exists.
			$delete_path = $this->get_current_picture_path( $user_id );
			if ( $delete_path ) {
				$delete = \wp_delete_file( $delete_path );
			}

			return \wp_send_json( $response );
		}

		/*
		* Update Picture Section.
		*
		*/
		if ( 'update_picture' === $action_taken ) {

			// Image Upload Section.
			if ( isset( $_FILES['upimage']['type'] ) && isset( $_FILES['upimage']['tmp_name'] ) ) {
				$temp_name = sanitize_file_name( wp_unslash( $_FILES['upimage']['tmp_name'] ) );
			}

			$arr_img_ext = array( 'image/png', 'image/jpeg', 'image/jpg', 'image/gif' );

			if ( isset( $_FILES['upimage']['type'] ) && ! in_array( $_FILES['upimage']['type'], $arr_img_ext, true ) ) {
				$response['feedback'] = esc_html__( 'Incorrect Attachment Type Sent', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			$session = 'tmp-' . $user_id . wp_rand( 200, 20000 ) . '.png';

			// Delete Existing File in Uploads directory if exists.
			$delete_path = $this->get_current_picture_path( $user_id );

			if ( $delete_path ) {
				$delete = \wp_delete_file( $delete_path );
			}

			if ( $temp_name ) {
				$user_id = \get_current_user_id();
				//phpcs:ignore -- WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$upload = \wp_upload_bits( $session, null, file_get_contents( $_FILES['upimage']['tmp_name'] ) );
				$return = Factory::get_instance( FileManagement::class )->user_picture_name_update( $upload['file'], $upload['url'] );
				\do_action( 'elemental_avatar_update', $user_id, $upload['url'], $upload['file'] );

				if ( $return ) {
					$response['feedback'] = esc_html__( 'Picture Update Success', 'elementalplugin' );
				} else {
					$response['feedback'] = esc_html__( 'Picture Update Failed', 'elementalplugin' );
				}
			}
			return \wp_send_json( $response );
		}

		/*
		* Upload a File Picture Section.
		*
		*/
		if ( 'upload_file' === $action_taken ) {

			// File Upload Section.
			if ( isset( $_FILES['upfile']['type'] ) && isset( $_FILES['upfile']['tmp_name'] ) ) {
				$file_name = sanitize_file_name( wp_unslash( $_FILES['upfile']['tmp_name'] ) );
			}
			// Filter files here.
			$arr_img_ext = array( 'image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'text/plain', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );

			if ( isset( $_FILES['upfile']['type'] ) && ! in_array( $_FILES['upfile']['type'], $arr_img_ext, true ) ) {
				$response['message'] = esc_html__( 'Incorrect Attachment Type Sent', 'elementalplugin' );
				return \wp_send_json( $response );
			}

			if ( $file_name ) {

				//phpcs:ignore -- WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$source      = $_FILES['upfile']['tmp_name'];
				$uploads_dir = Factory::get_instance( FileManagement::class )->get_user_upload_folder( $user_id );
				$destination = trailingslashit( $uploads_dir ) . $_FILES['upfile']['name'];

				$upload = move_uploaded_file( $source, $destination );

				\do_action( 'elemental_file_upload', $user_id, $destination . $_FILES['upfile']['name'], $_FILES['upfile']['name'] );

				$new_table = Factory::get_instance( FileManagement::class )->render_user_file_page( intval( $user_id ) );

				if ( $new_table ) {
					$response['feedback'] = \esc_html__( 'File Management', 'elementalplugin' );
					$response['table']    = $new_table;
				} else {
					$response['feedback'] = \esc_html__( 'Error With File Manager', 'elementalplugin' );
				}

				if ( $upload ) {
					$response['feedback'] = esc_html__( 'File Upload Success', 'elementalplugin' );
				} else {
					$response['feedback'] = esc_html__( 'File Upload Failed', 'elementalplugin' );
				}
			}
			return \wp_send_json( $response );
		}

		/*
		* Manage File Operations for User.
		*
		*/
		if ( 'file_manage_start' === $action_taken ) {

			$new_table = Factory::get_instance( FileManagement::class )->render_user_file_page( intval( $user_id ) );

			if ( $new_table ) {
				$response['feedback'] = \esc_html__( 'File Management', 'elementalplugin' );
				$response['table']    = $new_table;
			} else {
				$response['feedback'] = \esc_html__( 'Error With File Manager', 'elementalplugin' );
			}

			return \wp_send_json( $response );

		}
			/*
			* Delete File.
			*
			*/
		if ( 'delete_file' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, self::DELETE_FILE_REQUEST . strval( $user_id ) );
			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received - First Step', 'elementalplugin' ).$user_id;
				return \wp_send_json( $response );
			}

			$message                  = \esc_html__( 'delete this file ? This operation can not be undone', 'elementalplugin' );
			$approved_nonce           = wp_create_nonce( $user_id . self::DELETE_FILE_APPROVED );
			$button_approved          = Factory::get_instance( MembershipShortCode::class )->basket_nav_bar_button( $filecheck, esc_html__( 'Delete File', 'elementalplugin' ), null, $approved_nonce, $user_id );
			$response['confirmation'] = Factory::get_instance( MembershipShortCode::class )->membership_confirmation( $message, $button_approved );

			return \wp_send_json( $response );
		}

		if ( 'delete_file_final' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, $user_id . self::DELETE_FILE_APPROVED );

			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			// Decode File Path.
			$path = Factory::get_instance( Encryption::class )->decrypt_string( $filecheck );

			// Delete File.
			if ( $path ) {
				$status = Factory::get_instance( FileManagement::class )->delete_file_if_exists( $path );
			}
			if ( $status ) {
				$response['feedback']     = \esc_html__( 'File Deleted', 'elementalplugin' );
				$response['confirmation'] = '<h1>' . \esc_html__( 'File Deleted', 'elementalplugin' ) . '</h1>';
				$response['table']        = Factory::get_instance( FileManagement::class )->render_user_file_page( intval( $user_id ) );
			} else {
				$response['feedback'] = \esc_html__( 'Error Deleting File', 'elementalplugin' );
			}

			return \wp_send_json( $response );
		}

		/*
		* Check Login.
		*
		*/
		if ( 'check_login' === $action_taken ) {

			if ( \is_user_logged_in() ) {
				$response['login'] = true;
			} else {
				$response['login'] = false;
			}
			return \wp_send_json( $response );
		}

		die();
	}

	/** Current Picture Path
	 * Returns current file name of upload directory.
	 *
	 * @param string $session_id the cart hash of the user.
	 * @return ?string
	 */
	private function get_current_picture_path( string $session_id ) {
		$application_name = Files::APPLICATION_NAME;
		$user_object      = Factory::get_instance( FileSyncDao::class )->get_by_id_sync_table( $session_id, $application_name );
		if ( $user_object && $user_object->get_user_picture_path() ) {
			return $user_object->get_user_picture_path();
		} else {
			return null;
		}
	}
}
