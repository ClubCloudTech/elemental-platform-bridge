<?php
/**
 * Membership Function Handlers for Elemental.
 *
 * @package membership/library/class-membershipshortcode.php
 */

namespace ElementalPlugin\Module\Membership\Library;

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Module\Membership\Membership;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class MembershipShortCode {


	/**
	 * Render shortcode to Manage Sponsored Accounts
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_sponsored_account_shortcode( $attributes = array() ): ?string {
		$user_id = $attributes['user'] ?? null;

		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		return $this->sponsored_account_shortcode_worker( $user_id );
	}


	/**
	 * Sponsored Account Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function sponsored_account_shortcode_worker( int $user_id = null ): ?string {

		$this->enqueue_style_scripts();
		$child_account_table = $this->generate_sponsored_account_table();
		$login_form          = null;
		if ( ! \is_user_logged_in() ) {

			$args       = array(
				'echo'     => false,
				'redirect' => get_permalink( get_the_ID() ),
				'remember' => true,
			);
			$login_form = wp_login_form( $args );
		}
		$render              = ( require __DIR__ . '/../views/membership/manage-child.php' );
		$manage_account_form = ( require __DIR__ . '/../views/membership/add-new-user.php' );
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
		return $render( $manage_account_form(), $child_account_table, $login_form );
	}
	/**
	 * Get ALL Sponsored Account Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management.
	 *
	 * @return ?string
	 */
	public function all_sponsored_accounts_shortcode_worker(): ?string {

		$this->enqueue_style_scripts();
		$child_account_table = $this->generate_all_sponsored_accounts_table();
		$login_form          = null;
		if ( ! \is_user_logged_in() ) {

			$args       = array(
				'echo'     => false,
				'redirect' => get_permalink( get_the_ID() ),
				'remember' => true,
			);
			$login_form = wp_login_form( $args );
		}
		$render              = ( require __DIR__ . '/../views/membership/manage-child.php' );
		$manage_account_form = ( require __DIR__ . '/../views/membership/add-new-user.php' );
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
		return $render( $manage_account_form(), $child_account_table, $login_form );
	}

	/**
	 * Sponsored Account User Table
	 * Handles the rendering of the User tables for Sponsored Child Accounts.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function generate_sponsored_account_table( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$accounts_remaining = $this->render_remaining_account_count( $user_id );
		$sponsored_accounts = Factory::get_instance( MembershipUser::class )->get_sponsored_users_by_parent( $user_id );
		$render             = ( include __DIR__ . '/../views/membership/table-sponsored-accounts.php' );
		wp_enqueue_style( 'dashicons' );
		return $render( $sponsored_accounts, $accounts_remaining );

	}
	/**
	 * Sponsored Account User Table
	 * Handles the rendering of the User tables for Sponsored Child Accounts.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function generate_all_sponsored_accounts_table( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$accounts_remaining = $this->render_remaining_account_count( $user_id );
		$sponsored_accounts = Factory::get_instance( MembershipUser::class )->get_all_sponsored_users();
		$render             = ( include __DIR__ . '/../views/membership/table-sponsored-accounts.php' );
		wp_enqueue_style( 'dashicons' );
		$admin_nonce = \wp_create_nonce( MembershipUser::VERIFICATION_NONCE );
		return $render( $sponsored_accounts, $accounts_remaining, $admin_nonce );

	}

	/**
	 * Render shortcode for Tenant Admin Accounts
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_tenant_admin_account_shortcode( $attributes = array() ): ?string {
		$user_id = $attributes['user'] ?? null;

		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}

		return $this->tenant_admin_account_shortcode_worker( $user_id );
	}


	/**
	 * Tenant Admin Account Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management for Tenant Admin Accounts.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function tenant_admin_account_shortcode_worker( int $user_id = null ): ?string {

		$this->enqueue_style_scripts();
		$admin_account_table = $this->generate_tenant_admin_account_table();
		if ( ! \is_user_logged_in() ) {

			$args       = array(
				'echo'     => false,
				'redirect' => get_permalink( get_the_ID() ),
				'remember' => true,
			);
			$login_form = wp_login_form( $args );
		}
		$render              = ( require __DIR__ . '/../views/membership/manage-child.php' );
		$manage_account_form = ( require __DIR__ . '/../views/membership/add-new-user.php' );
		// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
		return $render( $manage_account_form(), $admin_account_table, $login_form );
	}

	/**
	 * Tenant Admin Account User Table
	 * Handles the rendering of the User tables for Sponsored Child Accounts.
	 *
	 * @param  int $user_id The WP User ID.
	 * @return ?string
	 */
	public function generate_tenant_admin_account_table( int $user_id = null ): ?string {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$accounts_remaining = $this->render_remaining_account_count( $user_id, Membership::MEMBERSHIP_ROLE_TENANT_ADMIN, Membership::MEMBERSHIP_ROLE_TENANT_ADMIN_DESCRIPTION );
		$sponsored_accounts = Factory::get_instance( MembershipUser::class )->get_sponsored_users_by_parent( $user_id );
		$render             = ( include __DIR__ . '/../views/membership/table-sponsored-accounts.php' );

		return $render( $sponsored_accounts, $accounts_remaining );

	}


	/**
	 * Enqueue Styles and Scripts
	 * Handles the Styles and Scripts needed for Membership Form front end to look like WCFM.
	 *
	 * @return void
	 */
	public function enqueue_style_scripts() {

		$css_lib_url       = plugins_url() . 'assets/wc/css/';
		$version           = Factory::get_instance( Version::class )->get_plugin_version();
		$upload_dir        = wp_upload_dir();
		$wcfm_style_custom = get_option( 'wcfm_style_custom' );
		\wp_enqueue_script( 'elementalplugin-iframe-handler' );
		wp_enqueue_style( 'wcfm_capability_css', $css_lib_url . 'capability/wcfm-style-capability.css', false, 1 );
		wp_enqueue_style( 'collapsible_css', $css_lib_url . 'wcfm-style-collapsible.css', false, $version );
		wp_enqueue_style( 'wcfmgs_staffs_manage_css', $css_lib_url . 'assets/css/wcfmgs-style-staffs-manage.css', false, $version );
		wp_enqueue_style( 'wcfm_menu_css', $css_lib_url . 'menu/wcfm-style-menu.css', array(), $version );
		wp_enqueue_style( 'wcfm_settings_css', $css_lib_url . 'settings/wcfm-style-settings.css', array(), $version );
		wp_enqueue_style( 'wcfm_messages_css', $css_lib_url . 'messages/wcfm-style-messages.css', array(), $version );
		wp_enqueue_style( 'collapsible_css', $css_lib_url . 'wcfm-style-collapsible.css', array(), $version );
		wp_enqueue_style( 'wcfm_notice_view_css', $css_lib_url . 'notice/wcfm-style-notice-view.css', array(), $version );
		wp_enqueue_style( 'wcfm_dashboard_css', $css_lib_url . 'dashboard/wcfm-style-dashboard.css', array(), $version );
		wp_enqueue_style( 'wcfm_dashboard_welcomebox_css', $css_lib_url . 'dashboard/wcfm-style-dashboard-welcomebox.css', array(), $version );
		wp_enqueue_style( 'wcfm_template_css', $css_lib_url . 'templates/classic/template-style.css', array(), $version );
		wp_enqueue_style( 'wcfm_no_menu_css', $css_lib_url . 'menu/wcfm-style-no-menu.css', array( 'wcfm_menu_css' ), $version );
		wp_enqueue_style( 'wcfm_menu_css', $css_lib_url . 'min/menu/wcfm-style-menu.css', array(), $version );
		wp_enqueue_style( 'wcfm_products_manage_css', $css_lib_url . 'products-manager/wcfm-style-products-manage.css', array(), $version );
		wp_enqueue_style( 'wcfm_custom_css', trailingslashit( $upload_dir['baseurl'] ) . 'wcfm/' . $wcfm_style_custom, array( 'wcfm_menu_css' ), $version );
		wp_enqueue_style( 'elementalplugin-menutab-header' );

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
	 * @param  int    $user_id The WP User ID.
	 * @param  string $account_type - The Account type to query.
	 * @param  string $account_description - The Description to show the user of account type.
	 * @return string
	 */
	public function render_remaining_account_count( int $user_id = null, string $account_type = null, string $account_description = null ): ?string {
		if ( ! $user_id ) {
			$user_id = \get_current_user_id();
		}
		if ( ! $account_type ) {
			$account_type = Membership::MEMBERSHIP_ROLE_SPONSORED;
		}
		if ( ! $account_description ) {
			$account_description = Membership::MEMBERSHIP_ROLE_SPONSORED_DESCRIPTION;
		}

		$accounts_remaining = Factory::get_instance( MembershipUMP::class )->child_account_available_number( $user_id, $account_type );
		if ( $accounts_remaining > 999 ) {
			$accounts_remaining_display = \esc_html__( 'unlimited ', 'elementalplugin' );
		} else {
			$accounts_remaining_display = $accounts_remaining;
		}

		if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
			return '<div id="div-holder-temp" class="elemental-initial"> <div id="elemental-remaining-counter" class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You have unlimited invitations remaining ', 'elementalplugin' ) . '</div></div>';
		} elseif ( $accounts_remaining > 0 ) {
			return '<div id="div-holder-temp" class="elemental-initial"><div id="elemental-remaining-counter" class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You have ', 'elementalplugin' ) . esc_textarea( $accounts_remaining_display ) . ' ' . esc_textarea( $account_description ) . esc_html__( ' invitations remaining', 'elementalplugin' ) . '</div></div>';
		} elseif ( 0 === $accounts_remaining ) {
			return '<div id="div-holder-temp" class="elemental-initial"><div id="elemental-remaining-counter" class="elemental-accounts-remaining" data-remaining="' . esc_textarea( $accounts_remaining ) . '">' . esc_html__( 'You have No ', 'elementalplugin' ) . esc_textarea( $account_description ) . esc_html__( ' invitations remaining', 'elementalplugin' ) . '</div></div>';
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

		$cancel_button = $this->cancel_nav_bar_button( 'cancel', esc_html__( 'Cancel', 'elemental' ), null, 'elemental-main-button-cancel' );

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
			$style = 'elemental-main-button-enabled';
		}

		return '
		<button  class="' . $style . ' elementalplugin-woocommerce-basket-ajax" data-input-type="' . $button_type . '" data-target="' . $target_id . ' " ' . $id_text . ' data-auth-nonce="' . $nonce . '">
		<a  data-input-type="' . $button_type . '" data-auth-nonce="' . $nonce . '" data-room-name="' . $room_name . '"' . $id_text . ' elementalplugin-woocommerce-basket-ajax ' . $href_class . '">' . $button_label . '</a>
		</button>
		';
	}
}
