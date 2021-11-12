<?php
/**
 * Room Admin Functions
 *
 * @package ElementalPlugin\RoomAdmin
 */

namespace ElementalPlugin\Setup;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\DAO\RoomMap;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\DAO\UserVideoPreference as UserVideoPreferenceDao;
use ElementalPlugin\Factory;

/**
 * Class RoomAdmin
 */
class RoomAdmin extends Shortcode {



	const TABLE_NAME = SiteDefaults::TABLE_NAME_ROOM_MAP;

	/**
	 * Install - called by Plugin initiation, and will run any lines in this class for the plugin automatically.
	 *
	 * @return void
	 */
	public function install() {

		$this->add_shortcode( 'getvideo_room_info', array( $this, 'get_video_info_shortcode' ) );

	}

	/**
	 * Create Page for Meet Center and record Post ID - Called by Activation Plugin.
	 *
	 * $param mixed - below
	 *
	 * @param string $room_name - name of room to call.
	 * @param string $room_type - type of room to call.
	 *
	 * @return string Sends shortcode output to worker function.
	 */
	public function get_video_info_shortcode( $params = array() ) {

		$room_name = $params['room'] ?? '';
		$room_type = $params['type'] ?? '';

		return $this->get_videoroom_info( $room_name, $room_type );
	}

	/**
	 * Returns Video Page information of pages created in the database.
	 *
	 * @param string $room_name - name of room.
	 * @param string $type      - type of room.
	 *
	 * @return bool|int|string|null
	 */
	public function get_videoroom_info( string $room_name, string $type = 'name' ) {
		// Trap Blank Input.
		if ( ! $room_name ) {
			return null;
		}
		// Get Data from Database.
		$room_post_id = Factory::get_instance( RoomMap::class )->read( $room_name );
		// Retrieve Post Object from Post.

		$post       = get_post( $room_post_id );
		$post_slug  = $post->post_name;
		$post_title = $post->post_title;
		$post_guid  = $post->guid;
		$post_id    = $post->ID;

		if ( 'name' === $type ) {
			return $post_slug;
		} elseif ( 'slug' === $type ) {
			return $post_slug;
		} elseif ( 'post_id' === $type ) {
			return $post_id;

		} elseif ( 'title' === $type ) {
			return $post_title;
		} elseif ( 'url' === $type ) {
			return $post_guid;
		}

	}

	/**
	 * Create a page into the Worpress environment, register in page table, and ensure its enabled.
	 *
	 * @param  string $room_name     - name of room to build.
	 * @param  string $display_title - Title of Page.
	 * @param  string $slug          - Worpress Slug to assign page.
	 * @param  string $shortcode     - Shortcode to place on page.
	 * @return null  - page executes database functions doesn't return to user.
	 */
	public function create_and_check_page( string $room_name, string $display_title, string $slug, string $shortcode ) {
		// Check Page Doesn't already Exist in Database and hasn't been deleted if it does.
		$check_page_exists = Factory::get_instance( RoomMap::class )->check_page_exists( $room_name );

		// Check_page_exists has three states, Yes, No, Or Orphan - if yes - exit function, if no create the room, if orphan delete room mapping in database and create room again.
		if ( 'Yes' === $check_page_exists ) {

			return null;
		}

		// Create Page in DB as Page doesn't exist.
		$post_id = wp_insert_post(
			array(
				'post_author'  => 1,
				'post_title'   => $display_title,
				'post_name'    => strtolower( str_replace( ' ', '-', trim( $slug ) ) ),
				'post_status'  => 'publish',
				'post_content' => $shortcode,
				'post_type'    => 'page',
			)
		);
		// Insert into DB as Page Didn't Exist.
		if ( 'No' === $check_page_exists ) {
			$room_update = Factory::get_instance( RoomMap::class )->register_room_in_db( $room_name, $post_id );
			return null;
		}
		// Update DB with New Value of created post.
		elseif ( 'Orphan' === $check_page_exists ) {
			$room_update = Factory::get_instance( RoomMap::class )->update_room_post_id( $room_name, $post_id );
			return null;
		}

	}

	/**
	 * Check_default_settings_exist in main Site config page
	 *
	 * @return bool yes it does or no it doesnt.
	 */
	public function check_default_settings_exist(): bool {

		$video_preference_dao = Factory::get_instance( UserVideoPreferenceDao::class );
		// Check Exists.
		$current_user_setting = $video_preference_dao->read(
			SiteDefaults::USER_ID_SITE_DEFAULTS,
			SiteDefaults::ROOM_NAME_SITE_DEFAULT
		);
		if ( $current_user_setting ) {
			return true;
		} else {
			return false;
		}

	}
}
