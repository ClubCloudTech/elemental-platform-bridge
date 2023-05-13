<?php
/**
 * File Management Function Handlers for Elemental.
 *
 * @package /module/files/library/class-filehooks.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Files\DAO\FileSyncDao;
use ElementalPlugin\Module\Files\Files;

/**
 * Class File Hooks - Manages File Related Operation Hooks
 */
class FileHooks {

	/**
	 * Hook to Send Notification Mail for File Change.
	 *
	 * @param string $user_id - the user_id from the hook.
	 *
	 * @return void
	 */
	public function notify_user_file_change_hook( int $user_id ):void {
		$user = \get_user_by( 'id', $user_id );
		$this->notify_user_file_change( $user->user_email, $user->first_name );
	}
	/**
	 * Send Document Change Notification Mail to New User.
	 *
	 * @param string $email_address - the User Email Address.
	 * @param string $first_name    - the User First Name.
	 *
	 * @return bool
	 */
	private function notify_user_file_change( string $email_address, string $first_name ) {

		$template = include __DIR__ . '/../views/email/email-template.php';
		$headers  = array( 'Content-Type: text/html; charset=UTF-8' );

		$status = wp_mail(
			$email_address,
			\esc_html__( ' New Document for you at ', 'elementalplugin' ) . \get_bloginfo( 'name' ),
			$template( $email_address, $first_name ),
			$headers
		);
		return $status;
	}
	/**
	 * Hook to Send Notification Message Status for File Change.
	 *
	 * @param string $user_id - the user_id from the hook.
	 *
	 * @return void
	 */
	public function new_user_file_notification_hook( int $user_id ):void {
		Factory::get_instance( FileSyncDao::class )->create_new_user_storage_record( strval( $user_id ), Files::STATUS_FIELD_MESSAGE );
	}


}
