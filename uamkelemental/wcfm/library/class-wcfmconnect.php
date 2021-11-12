<?php
/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 *
 * @package elemental/wcfm/library/class-wcfmconnect.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\SectionTemplates;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use ElementalPlugin\Shortcode\MyVideoRoomApp;
use ElementalPlugin\Factory;
use ElementalPlugin\Core\VideoHelpers;
use ElementalPlugin\Core\Security;
use ElementalPlugin\Shortcode\SecurityVideoPreference;

/**
 * Connect MyVideoRoom to Woocommerce FrontEnd Manager Video
 */
class WCFMConnect extends Shortcode {

	/**
	 * Install the shortcode
	 */
	public function install() {
		$this->add_shortcode( 'mvrvideo', array( $this, 'wcfmvideo' ) );// For backwards compat with MVR and Templates.
		$this->add_shortcode( 'wcfmvideo', array( $this, 'wcfmvideo' ) );
	}

	/**
	 * Controls the Calling of My Video Room Merchant Stores
	 * This is used for both members and guests and switches room permissions and options
	 * It accepts hostname or invite as an argument which it gets from the page URL get request parameter
	 *
	 * Function used to render both Merchant and Staff Member Aware Store Video  It requires WCFM
	 */
	public function wcfmvideo( $params = array() ) {
        // phpcs:ignore as wp_filter no html is more restrictive than unslash.etc so its sanitised propertly with just that. 
		$host = $params['host'] ?? wp_filter_nohtml_kses( $_GET['host'] ?? '' );
        // phpcs:ignore as wp_filter no html is more restrictive than unslash.etc so its sanitised propertly with just that. 
		$invite = $params['invite'] ?? wp_filter_nohtml_kses( $_GET['invite'] ?? '' );

		$user_id    = get_current_user_id();
		$owner_id   = Factory::get_instance( SiteDefaults::class )->page_owner();
		$store_name = Factory::get_instance( WCFMHelpers::class )->store_displayname( $owner_id, 'slug' );
		if ( ! $store_name ) {
			$store_name = 'yoga-for-life';
		}

		$room_name          = Factory::get_instance( SiteDefaults::class )->room_map( 'store', $owner_id );
		$video_template     = Factory::get_instance( VideoHelpers::class )->get_videoroom_template( $owner_id, $store_name );
		$reception_template = Factory::get_instance( VideoHelpers::class )->get_reception_template( $owner_id, $store_name );
		$reception_setting  = Factory::get_instance( VideoHelpers::class )->get_enable_reception_state( $owner_id, $store_name );

		$user       = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( $user_id );
		$user_roles = Factory::get_instance( UserRoles::class, array( $user ) );

		$video_reception_state = Factory::get_instance( VideoHelpers::class )->get_video_reception_state( $owner_id, $store_name );
		$video_reception_url   = Factory::get_instance( VideoHelpers::class )->get_video_reception_url( $owner_id, $store_name );
		$show_floorplan        = Factory::get_instance( VideoHelpers::class )->get_show_floorplan( $owner_id, $store_name );

		// Security Engine - Checking if Page can be Rendered.
		$render_block = Factory::get_instance( Security::class )->render_block( $owner_id, 'wcfm-connect', SiteDefaults::MODULE_WCFM_ID, $room_name );
		if ( $render_block ) {
			return $render_block;
		}

		if ( ! is_user_logged_in()  // // First Deal with Signed Out Users.
			|| ( ! $user_roles->is_wcfm_vendor() && ! $user_roles->is_wcfm_shop_staff() ) // Next deal with Plain Users.
		) {

			$myvideoroom_app = MyVideoRoomApp::create_instance(
				$room_name,
				$video_template
			);

			if ( $reception_setting ) {
				$myvideoroom_app->enable_reception()->set_reception_id( $reception_template );
			}
			// Prepare Elements and Send to Wrapper Function.
			$header    = Factory::get_instance( SectionTemplates::class )->wcfmc_visitor_header();
			$shortcode = $myvideoroom_app->output_shortcode();
			return Factory::get_instance( SectionTemplates::class )->shortcode_template_wrapper_wcfm( $header, $shortcode );
		}

		// Handle Logged in Users with normal browsing.
		if ( ! $invite && ! $host && is_user_logged_in() ) {

			// Owner in their own room.
			if ( $user_id === $owner_id || Factory::get_instance( WCFMHelpers::class )->staff_to_parent( $user_id ) === $owner_id ) {
				// Handling Staff or Owners.
				if ( $user_roles->is_wcfm_vendor() || $user_roles->is_wcfm_shop_staff() ) {
					if ( $user_roles->is_wcfm_shop_staff() ) {
						$owner_id = $user->_wcfm_vendor;
					}

					$myvideoroom_app = MyVideoRoomApp::create_instance(
						$room_name,
						$video_template,
					)->enable_admin();
					// Prepare Room Settings to Send to Wrapper Template - Host.
					$header           = Factory::get_instance( SectionTemplates::class )->wcfmc_visitor_header();
					$shortcode        = $myvideoroom_app->output_shortcode();
					$admin_page       = Factory::get_instance( \ElementalPlugin\Shortcode\UserVideoPreference::class )->choose_settings(
						$owner_id,
						$store_name,
						array( 'basic', 'premium' )
					);
					$permissions_page = Factory::get_instance( SecurityVideoPreference::class )->choose_settings(
						$owner_id,
						$store_name
					);

					return Factory::get_instance( SectionTemplates::class )->shortcode_template_wrapper_wcfm( $header, $shortcode, $admin_page, $permissions_page );
				}
			}

			// Handling being in Someone else's Room.
			if ( $user_id !== $owner_id ) {

				$myvideoroom_app = MyVideoRoomApp::create_instance(
					$room_name,
					$video_template,
				);

				if ( $reception_setting ) {
						  $myvideoroom_app->enable_reception()->set_reception_id( $reception_template );

					if ( $video_reception_state ) {
						echo 'reception';
						$myvideoroom_app->set_reception_video_url( $video_reception_url );
					}
				}
				// Prepare Elements and Send to Wrapper Function.
				$header    = Factory::get_instance( SectionTemplates::class )->wcfmc_visitor_header();
				$shortcode = $myvideoroom_app->output_shortcode();
				return Factory::get_instance( SectionTemplates::class )->shortcode_template_wrapper_wcfm( $header, $shortcode );
			}
		} //End User Logged in Section.
	}
}
