<?php
/**
 * Ajax for Site Video Room.
 *
 * @package MyVideoRoomPlugin\Modules\SiteVideo
 */

namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;

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


	/** Membership Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management.
	 *
	 * @param int $user_id The WP User ID.
	 * @return ?string
	 */
	public function membership_shortcode_worker( int $user_id = null ): ?string {

		$this->enqueue_style_scripts();
		$user_id             = get_current_user_id();
		$accounts_remaining  = Factory::get_instance( MembershipUMP::class )->child_account_available_number( $user_id );
		$render              = ( require __DIR__ . '/../views/manage-child.php' );
		$manage_account_form = ( require __DIR__ . '/../views/add-new-user.php' );
		echo $render( $manage_account_form(), $accounts_remaining );
		return null;
	}

	/** Enqueue Styles and Scripts
	 * Handles the Styles and Scripts needed for Membership Form front end to look like WCFM.
	 *
	 * @return void
	 */
	private function enqueue_style_scripts() {
		global $WCFM, $WCFMgs;
		$css_lib_url       = $WCFM->plugin_url . 'assets/css/';
		$upload_dir        = wp_upload_dir();
		$wcfm_style_custom = get_option( 'wcfm_style_custom' );
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



}
