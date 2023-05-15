<?php
/**
 * File Management Function Handlers for Elemental.
 *
 * @package /module/files/library/class-filehooks.php
 */

namespace ElementalPlugin\Module\Files\Library;

use ElementalPlugin\Library\EmailHelpers;
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
			Factory::get_instance( EmailHelpers::class )->notify_user_file_change( $user->user_email, $user->first_name );
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
