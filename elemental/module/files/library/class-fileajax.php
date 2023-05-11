<?php
/**
 * Ajax for Files and Pictures
 *
 * @package Elemental\Module\File\library\class-fileajax.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Files;
use Error;

/**
 * Class MVRSiteVideo - Renders the Video Plugin for SiteWide Video Room.
 */
class FileAjax {

	const DELETE_APPROVED = 'delete-approved';


	/** File Upload Ajax Support.
	 * Handles Uploads from Welcome Area, sends them to storage and updates the database.
	 *
	 * @return mixed
	 */
	public function file_upload_handler() {
		$temp_name           = null;
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( Files::AJAX_FILE_NONCE, 'security', false );
		$user_id = get_current_user_id();

		if ( isset( $_POST['room_name'] ) ) {
			$application_name = sanitize_text_field( wp_unslash( $_POST['room_name'] ) );
		}
		if ( isset( $_POST['action_taken'] ) ) {
			$action_taken = sanitize_text_field( wp_unslash( $_POST['action_taken'] ) );
		}
		if ( isset( $_POST['display_name'] ) ) {
			$display_name = sanitize_text_field( wp_unslash( $_POST['display_name'] ) );
		}
		if ( isset( $_POST['checksum'] ) ) {
			$checksum = sanitize_text_field( wp_unslash( $_POST['checksum'] ) );
			$user_id  = Factory::get_instance( Encryption::class )->decrypt_string( $checksum );
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
		* Update Picture Section.
		*
		*/
		if ( 'update_file' === $action_taken ) {

			// File Upload Section.
			if ( isset( $_FILES['upfile']['type'] ) && isset( $_FILES['upfile']['tmp_name'] ) ) {
				$temp_name = sanitize_file_name( wp_unslash( $_FILES['upfile']['tmp_name'] ) );
			}
//TODO -- iiz here   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Filter files here.
			$arr_img_ext = array();

			if ( isset( $_FILES['upfile']['type'] ) && ! in_array( $_FILES['upfile']['type'], $arr_img_ext, true ) ) {
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
				$upload = \wp_upload_bits( $session, null, file_get_contents( $_FILES['upfile']['tmp_name'] ) );
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
		* Update Display Name section.
		*
		*/
		if ( 'update_display_name' === $action_taken ) {
			if ( $display_name && $application_name ) {
				$display_updated = Factory::get_instance( FileManagement::class )->user_picture_name_update( null, null, $display_name );
			}

			if ( true === $display_updated ) {
				$response['feedback'] = \esc_html__( 'Display Name Update Updated', 'elementalplugin' );
			} else {
				$response['feedback'] = \esc_html__( 'Display Name Update Failed', 'elementalplugin' );
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
