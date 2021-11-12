<?php
/**
 * Data Access Object for user video preferences default room setup
 *
 * @package ElementalPlugin\DAO
 */

namespace ElementalPlugin\DAO;

use ElementalPlugin\DAO\UserVideoPreference as UserVideoPreferenceDao;
use ElementalPlugin\Entity\UserVideoPreference as UserVideoPreferenceEntity;
use ElementalPlugin\Factory;


/**
 * Class UserVideoPreference
 */
class RoomInit {


	const TABLE_NAME = \ElementalPlugin\Core\SiteDefaults::TABLE_NAME_ROOM_MAP;



	/**
	 * Room Default Settings Install.
	 *
	 * @param  int    $user_id - the User ID.
	 * @param  string $room_name - the room name.
	 * @param  string $layout_id_to_set - room layout.
	 * @param  string $reception_id_to_set - reception layout.
	 * @param  bool   $reception_enabled - if reception is enabled.
	 * @return void
	 */
	public function room_default_settings_install( int $user_id, string $room_name, string $layout_id_to_set, string $reception_id_to_set, bool $reception_enabled ) {

		$video_preference_dao = Factory::get_instance( UserVideoPreferenceDao::class );
		// Check Exists.
		$current_user_setting = $video_preference_dao->read(
			$user_id,
			$room_name
		);

		if ( $current_user_setting ) {
			$current_user_setting->set_layout_id( $layout_id_to_set )
				->set_reception_id( $reception_id_to_set )
				->set_reception_enabled( $reception_enabled );
			$video_preference_dao->update( $current_user_setting );
		} else {
			$current_user_setting = new UserVideoPreferenceEntity(
				$user_id,
				$room_name,
				$layout_id_to_set,
				$reception_id_to_set,
				$reception_enabled
			);
			$video_preference_dao->create( $current_user_setting );
		}

	}

}




