<?php
/**
 * Frontend Helpers for WCFM.
 *
 * @package elemental/wcfm/library/class-wcfmhelpers.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\BuddyPress\ElementalBP;
use ElementalPlugin\Factory;
use ElementalPlugin\Membership\DAO\MembershipDAO;
use ElementalPlugin\Membership\Library\WooCommerceHelpers;
use ElementalPlugin\UltimateMembershipPro\DAO\ElementalUMPDAO;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Class WCFMHelpers
 */
class WCFMHelpers {

	const SHORTCODE_ARCHIVE_TEMPLATE_REDIRECT = 'elemental_wcfm_archive_switch';
	const SETTING_WCFM_PREMIUM_MEMBERSHIPS    = 'elemental-WCFM-premium-memberships';
	const SETTING_WCFM_ARCHIVE_SHORTCODE_ID   = 'elemental-WCFM-archive';
	const SHORTCODE_MYSTORE                   = 'elemental_mystore';
	const SHORTCODE_MYPROFILE                 = 'elemental_myprofile';

	/**
	 * Install the shortcode
	 */
	public function init() {

		add_shortcode( self::SHORTCODE_ARCHIVE_TEMPLATE_REDIRECT, array( $this, 'switch_product_archive' ) );
		add_shortcode( self::SHORTCODE_MYSTORE, array( $this, 'render_mystore_button' ) );
		add_shortcode( self::SHORTCODE_MYPROFILE, array( $this, 'render_myprofile_button' ) );
		// Option for Premium Memberships Setting.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_wcfm_premium_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_wcfm_premium_setting' ), 10, 2 );

		// Option for WCFM Store Template.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_wcfm_archive_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_wcfm_archive_setting' ), 10, 2 );

	}

	/** Child Account User Table
	 * Handles the rendering of the User tables for Child Accounts.
	 *
	 * @param int $user_id The WP User ID.
	 * @return ?string
	 */
	public function generate_membership_settings_table( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$sponsored_accounts = Factory::get_instance( MembershipUser::class )->get_sponsored_users( $user_id );
		$render             = ( require __DIR__ . '/../views/table-sponsored-accounts.php' );

		return $render( $sponsored_accounts );

	}
	/**
	 * Switch Product Archive
	 * Switches the Product Archive Template inside WCFM stores to a given template, and normal product archives to another.
	 * Used as WCFM without template overrides always displays product archive pages.
	 *
	 * @return string
	 */
	public function switch_product_archive(): string {

		$is_wcfm_shop = Factory::get_instance( WCFMTools::class )->is_wcfm_store();
		if ( $is_wcfm_shop ) {
			$template_id = $this->get_store_template();
			return \do_shortcode( '[elementor-template id="' . $template_id . '"]' );
		} else {
			return \do_shortcode( '[elementor-template id="' . \get_option( WooCommerceHelpers::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID ) . '"]' );
		}
	}

	/**
	 * Return Store Template
	 * Correctly Retrieves Membership Level Store Template.
	 *
	 * @param int $store_id - the store_id (if blank current store owner used if any).
	 * @return string
	 */
	public function get_store_template( int $store_id = null ): ?string {
		if ( $store_id ) {
			$owner_id = $store_id;
		} else {
			$owner_id = Factory::get_instance( WCFMTools::class )->get_wcfm_page_owner();
			if ( ! $owner_id ) {
				echo '<h1>' . esc_html__( 'No Valid Owner Found', 'myvideoroom' ) . '</h1>';
				return null;
			}
		}
		// Individual Store Template Switch.
		$individual_store_template = $this->premium_template_mappings( $owner_id );
		if ( $individual_store_template ) {
			return $individual_store_template;
		}

		$membership_level = Factory::get_instance( ElementalUMPDAO::class )->get_all_active_ump_levels( $owner_id, true );
		if ( ! $membership_level ) {
			echo '<h1>' . esc_html__( 'No Valid User Memberships Found', 'myvideoroom' ) . '</h1>';
			return null;
		}
		$data_object = Factory::get_instance( MembershipDAO::class )->get_limit_info( intval( $membership_level[0] ) );
		$template    = $data_object->template;
		if ( ! $template ) {
			echo '<h1>' . esc_html__( 'No Valid Template Found', 'myvideoroom' ) . '</h1>';
			return null;
		}
		return $template;
	}
	/**
	 * Visit Store Button Shortcode Handler.
	 * Renders Visit My Store Button for Staff and Store Owners
	 *
	 * @param int $atts - the shortcode attributes.
	 * @return string
	 */
	public function render_mystore_button( $atts = array() ): ?string {
		if ( isset( $atts['user_id'] ) ) {
			$user_id = $atts['user_id'];
		} else {
			$user_id = \get_current_user_id();
		}
		return $this->render_mystore_button_worker( $user_id );
	}

	/**
	 * MyProfile Shortcode BP.
	 * Renders My Profile Button in BP
	 *
	 * @param int $atts - the shortcode attributes.
	 * @return string
	 */
	public function render_myprofile_button( $atts = array() ): ?string {
		if ( isset( $atts['user_id'] ) ) {
			$user_id = $atts['user_id'];
		} else {
			$user_id = \get_current_user_id();
		}
		if ( ! \is_user_logged_in() ) {
			return '';
		}
		$url    = Factory::get_instance( ElementalBP::class )->get_buddypress_profile_url( $user_id );
		$output = '<a href="' . $url . '" class="elementor-item">' . esc_html__( 'Profile', 'myvideoroom' ) . '</a>';
		return $output;
	}

	/**
	 * Visit Store Button
	 * Renders Visit My Store Button for Staff and Store Owners
	 *
	 * @param int $store_id - the store_id (if blank current store owner used if any).
	 * @return string
	 */
	public function render_mystore_button_worker( int $store_id ): ?string {
		if ( ! Factory::get_instance( WCFMTools::class )->am_i_merchant() ) {
			return null;
		}

		$store_url = Factory::get_instance( WCFMTools::class )->get_store_url( $store_id );

		$output = '<a href="' . $store_url . '" class="elementor-item">' . esc_html__( 'My Site', 'myvideoroom' ) . '</a>';
		return $output;
	}



	/**
	 * Add WCFM Premium Account List.
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_wcfm_premium_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Premium WCFM Memberships', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="text" size="32"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_WCFM_PREMIUM_MEMBERSHIPS ) . '"
		value="' . get_option( self::SETTING_WCFM_PREMIUM_MEMBERSHIPS ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Comma separated list of what accounts site considers Premium (use numeric ID of WCFM Membership ID)', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. WCFM Update Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_wcfm_premium_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_WCFM_PREMIUM_MEMBERSHIPS );
		\update_option( self::SETTING_WCFM_PREMIUM_MEMBERSHIPS, $field );
		$response['feedback'] = \esc_html__( 'WCFM Premium Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Add WCFM Archive Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_wcfm_archive_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'WCFM Default Archive Page ID', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_WCFM_ARCHIVE_SHORTCODE_ID ) . '"
		value="' . get_option( self::SETTING_WCFM_ARCHIVE_SHORTCODE_ID ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Shortcode Post ID Template Switch to Call for a WCFM Store in case a membership level has no setting', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. WCFM Update Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_wcfm_archive_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_WCFM_ARCHIVE_SHORTCODE_ID );
		\update_option( self::SETTING_WCFM_ARCHIVE_SHORTCODE_ID, $field );
		$response['feedback'] = \esc_html__( 'WCFM Archive Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Get WCFM Control Panel Page
	 *
	 * @return string
	 */
	public function get_wcfm_control_panel_page(): string {
		$pages = get_option( 'wcfm_page_options' );
		$link  = get_permalink( $pages['wc_frontend_manager_page_id'] );
		return $link;
	}

	/**
	 * Premium Organisation Mappings
	 * Returns mapping of premium templates that are custom.
	 * Add a user ID to the list as a case with an elementor template number to give it a custom template.
	 *
	 * @param int $owner_id - Owner ID.
	 * @return ?int
	 */
	public function premium_template_mappings( int $owner_id ): ?int {

		switch ( $owner_id ) {
			// Civata Global.
			case 126:
				return 49705;
			default:
				return null;
		}
	}


}
