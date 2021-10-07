<?php
/**
 * Ajax for Site Video Room.
 *
 * @package MyVideoRoomPlugin\Modules\SiteVideo
 */

namespace ElementalPlugin\Membership\Library;

use MyVideoRoomPlugin\DAO\RoomSyncDAO;
use MyVideoRoomPlugin\DAO\UserVideoPreference;
use ElementalPlugin\Factory;

use MyVideoRoomPlugin\Library\RoomAdmin;
use MyVideoRoomPlugin\Module\SiteVideo\MVRSiteVideo;


/**
 * Class MVRSiteVideo - Renders the Video Plugin for SiteWide Video Room.
 */
class MembershipAjax {


	/** File Upload Ajax Support.
	 * Handles Uploads from Welcome Area, sends them to storage and updates the database.
	 *
	 * @return mixed
	 */
	public function membership_ajax_handler() {
		$temp_name           = null;
		$response            = array();
		$response['message'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_membership', 'security', false );

		if ( isset( $_POST['action_taken'] ) ) {
			$action_taken = sanitize_text_field( wp_unslash( $_POST['action_taken'] ) );
		}
		if ( isset( $_POST['level'] ) ) {
			$membership_level = sanitize_text_field( wp_unslash( $_POST['level'] ) );
		}
		if ( isset( $_POST['value'] ) ) {
			$set_value = sanitize_text_field( wp_unslash( $_POST['value'] ) );
		}

		/*
		* Update Display Name section.
		*
		*/
		if ( 'update_db' === $action_taken ) {


			if ( true === $display_updated ) {
				$response['feedback'] = \esc_html__( 'Display Name Update Updated', 'myvideoroom' );
			} else {
				$response['feedback'] = \esc_html__( 'Display Name Update Failed', 'myvideoroom' ) . $membership_level . '->' . $set_value;
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

}
