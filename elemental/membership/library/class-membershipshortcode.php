<?php
/**
 * Ajax for Site Video Room.
 *
 * @package MyVideoRoomPlugin\Modules\SiteVideo
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use \MyVideoRoomPlugin\Module\SiteVideo\Library\MVRSiteVideoViews;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class MembershipShortCode {


	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_membership_shortcode( $attributes = array() ): ?string {
		$user_id = $attributes['user'] ?? null;

		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		return $this->membership_shortcode_worker( $user_id );
	}


	/**
	 * Membership Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function membership_shortcode_worker( int $user_id = null ): ?string {

		$this->enqueue_style_scripts();
		$user_id             = get_current_user_id();
		$accounts_remaining  = $this->render_remaining_account_count( $user_id );
		$child_account_table = $this->generate_child_account_table();
		if ( ! \is_user_logged_in() ) {
			$login_form = Factory::get_instance( MVRSiteVideoViews::class )->render_login_page();
		}
<<<<<<< HEAD
		$render              = ( include __DIR__ . '/../views/manage-child.php' );
		$manage_account_form = ( include __DIR__ . '/../views/add-new-user.php' );
     // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
     echo $render( $manage_account_form(), $accounts_remaining, $child_account_table, $login_form );
		return null;
=======
		$render              = ( require __DIR__ . '/../views/manage-child.php' );
		$manage_account_form = ( require __DIR__ . '/../views/add-new-user.php' );
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
		return $render( $manage_account_form(), $accounts_remaining, $child_account_table, $login_form );
>>>>>>> a6fd707ad028a28273f93e9e7f27caddb908fd31
	}

	/**
	 * Child Account User Table
	 * Handles the rendering of the User tables for Child Accounts.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function generate_child_account_table( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$sponsored_accounts = Factory::get_instance( MembershipUser::class )->get_sponsored_users( $user_id );
		$render             = ( include __DIR__ . '/../views/table-sponsored-accounts.php' );

		return $render( $sponsored_accounts );

	}

	/**
	 * Enqueue Styles and Scripts
	 * Handles the Styles and Scripts needed for Membership Form front end to look like WCFM.
	 *
	 * @return void
	 */
	private function enqueue_style_scripts() {
		global $WCFM, $WCFMgs;
		$css_lib_url       = $WCFM->plugin_url . 'assets/css/';
		$upload_dir        = wp_upload_dir();
		$wcfm_style_custom = get_option( 'wcfm_style_custom' );
		\wp_enqueue_script( 'myvideoroom-iframe-handler' );
		wp_enqueue_style( 'wcfm_capability_css', $WCFM->library->css_lib_url . 'capability/wcfm-style-capability.css', false, 1 );
		wp_enqueue_style( 'collapsible_css', $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', false, $WCFMgs->version );
		wp_enqueue_style( 'wcfmgs_staffs_manage_css', $WCFMgs->plugin_url . 'assets/css/wcfmgs-style-staffs-manage.css', false, $WCFMgs->version );
		wp_enqueue_style( 'wcfm_menu_css', $WCFM->library->css_lib_url_min . 'menu/wcfm-style-menu.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_settings_css', $css_lib_url . 'settings/wcfm-style-settings.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_messages_css', $css_lib_url . 'messages/wcfm-style-messages.css', array(), $WCFM->version );
		wp_enqueue_style( 'collapsible_css', $css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_notice_view_css', $css_lib_url . 'notice/wcfm-style-notice-view.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_dashboard_css', $css_lib_url . 'dashboard/wcfm-style-dashboard.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_dashboard_welcomebox_css', $css_lib_url . 'dashboard/wcfm-style-dashboard-welcomebox.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_template_css', $WCFM->plugin_url . 'templates/classic/template-style.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_no_menu_css', $css_lib_url . 'menu/wcfm-style-no-menu.css', array( 'wcfm_menu_css' ), $WCFM->version );
		wp_enqueue_style( 'wcfm_menu_css', $css_lib_url . 'min/menu/wcfm-style-menu.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_products_manage_css', $css_lib_url . 'products-manager/wcfm-style-products-manage.css', array(), $WCFM->version );
		wp_enqueue_style( 'wcfm_custom_css', trailingslashit( $upload_dir['baseurl'] ) . 'wcfm/' . $wcfm_style_custom, array( 'wcfm_menu_css' ), $WCFM->version );
		wp_enqueue_style( 'myvideoroom-menutab-header' );

		\wp_enqueue_script( 'elemental-membership-js' );
		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_membership' ),

		);

		wp_localize_script(
			'elemental-membership-js',
			'elemental_membershipadmin_ajax',
			$script_data_array
		);
	}


	/**
	 * Enqueue Styles and Scripts
	 * Handles the Styles and Scripts needed for Membership Form front end to look like WCFM.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return string
	 */
	public function render_remaining_account_count( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		$accounts_remaining = Factory::get_instance( MembershipUMP::class )->child_account_available_number( $user_id );

		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
			return '<div id="div-holder-temp" class="elemental-initial"> <div id="elemental-remaining-counter" class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You Have Unlimited Accounts Remaining ', 'myvideoroom' ) . '</div></div>';
		} elseif ( $accounts_remaining > 0 ) {
			return '<div id="div-holder-temp" class="elemental-initial"><div id="elemental-remaining-counter" class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You Have ', 'myvideoroom' ) . esc_textarea( $accounts_remaining ) . esc_html__( ' accounts remaining', 'myvideoroom' ) . '</div></div>';
		}
		return null;
	}

	/**
	 * Render Confirmation Pages
	 *
	 * @param  string $message                      - Message to Display.
	 * @param  string $confirmation_button_approved - Button to Display for Approved.
	 * @return string
	 */
	public function membership_confirmation( string $message, string $confirmation_button_approved ):string {

		$cancel_button = $this->cancel_nav_bar_button( 'cancel', esc_html__( 'Cancel', 'my-video-room' ), null, 'mvr-main-button-cancel' );

		// Render Confirmation Page View.
		$render = include __DIR__ . '/../views/confirmation-page.php';
		return $render( $message, $confirmation_button_approved, $cancel_button );

	}
	/**
	 * Render the Basket Nav Bar Button
	 *
	 * @param string $button_type   - Feedback for Ajax Post.
	 * @param string $button_label  - Label for Button.
	 * @param string $style         - Add a class for the button (optional).
	 * @param string $target_id     - Adds a class to the button to javascript take an action on.
	 * @param string $target_window - adds a target window element used to switch destination windows.
	 *
	 * @return string
	 */
	public function cancel_nav_bar_button( string $button_type, string $button_label, string $style = null, string $target_id = null, string $target_window = null ): string {

		$id_text = null;

		if ( $target_window ) {
			$id_text = ' data-target="' . $target_window . '" ';
		}

		$style .= ' ' . $target_id;

		return '
		<button id="' . $target_id . '" class="' . $style . '" data-target="' . $target_window . '">
		<a data-input-type="' . $button_type . '" ' . $id_text . ' class=" ' . $style . ' ">' . $button_label . '</a>
		</button>
		';
	}

	/**
	 * Render the Basket Nav Bar Button
	 *
	 * @param string $button_type   - Feedback for Ajax Post.
	 * @param string $button_label  - Label for Button.
	 * @param string $room_name     -  Name of Room.
	 * @param string $nonce         - Nonce for operation (if confirmation used).
	 * @param string $product_or_id - Adds additional Data to Nonce for more security (optional).
	 * @param string $style         - Add a class for the button (optional).
	 * @param string $target_id     - Adds a class to the button to javascript take an action on.
	 * @param string $href_class    - Adds a class to the button to javascript take an action on.
	 * @param string $target_window - adds a target window element used to switch destination windows.
	 *
	 * @return string
	 */
	public function basket_nav_bar_button( string $button_type, string $button_label, string $room_name = null, string $nonce = null, string $product_or_id = null, string $style = null, string $target_id = null, string $href_class = null, string $target_window = null ): string {

		$id_text = null;
		if ( $product_or_id ) {
			$id_text .= ' data-record-id="' . $product_or_id . '" ';
		}

		if ( $target_window ) {
			$id_text .= ' data-target="' . $target_window . '" ';
		}

		if ( ! $style ) {
			$style = 'mvr-main-button-enabled';
		}

		return '
		<button  class="' . $style . ' myvideoroom-woocommerce-basket-ajax" data-target="' . $target_id . '">
		<a  data-input-type="' . $button_type . '" data-auth-nonce="' . $nonce . '" data-room-name="' . $room_name . '"' . $id_text . ' class="' . $style . ' myvideoroom-woocommerce-basket-ajax ' . $href_class . '">' . $button_label . '</a>
		</button>
		';
	}
}
