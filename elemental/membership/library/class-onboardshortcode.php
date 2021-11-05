<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-onboardshortcode.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.
namespace ElementalPlugin\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\WCFMTools;
use ElementalPlugin\WooCommerceSubscriptions\Library\SubscriptionHelpers;
use \MyVideoRoomPlugin\Library\HttpGet;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class OnboardShortcode {

	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_onboarding_shortcode( $attributes = array() ): ?string {
		$http_get_library = Factory::get_instance( HttpGet::class );
		$membership_id    = intval( $http_get_library->get_string_parameter( 'membership' ) );

		if ( ! $membership_id ) {
			$membership_id = $attributes['membership'] ?? null;
		}

		return $this->onboarding_shortcode_worker( $membership_id );
	}

	/**
	 * Render WCFM Step to allow Level 2 Registration.
	 *
	 * @param int $user_id - the User ID.
	 * @return ?string
	 */
	public function render_wcfm_step( int $user_id ): ?string {
		$render       = ( require __DIR__ . '/../views/wcfm/manage-wcfm.php' );
		$registration = ( require __DIR__ . '/../views/wcfm/vendor-registration.php' );
		return $render( $user_id, $registration );
	}


	/**
	 * Membership Shortcode Worker Function
	 * Handles the rendering of the shortcode for membership management.
	 *
	 * @param  int $membership_id The Membership ID.
	 * @return ?string
	 */
	public function onboarding_shortcode_worker( int $membership_id = null ): ?string {
		// Setup.
		$http_get_library = Factory::get_instance( HttpGet::class );
		$user_logged_in   = is_user_logged_in();
		if ( $user_logged_in ) {
			$user_id = \get_current_user_id();
		}
		if ( ! $membership_id ) {
			$membership_id = intval( $http_get_library->get_string_parameter( 'membership' ) );
		}
		$order_num         = $http_get_library->get_string_parameter( 'order' );
		$thank_you_status  = $http_get_library->get_string_parameter( 'vmstep' );
		$individual_status = $http_get_library->get_string_parameter( 'membership' );
		$this->enqueue_style_scripts( true );

		// Case Thank you Subscription Order.
		if ( $order_num ) {
			Factory::get_instance( WooCommerceHelpers::class )->process_order_num( $order_num );
			$redirect_slug = get_option( WooCommerceHelpers::SETTING_ONBOARD_POST_SUB_SLUG );
			$redirect_url  = \get_site_url() . '/' . $redirect_slug;
			$render        = ( require __DIR__ . '/../views/onboarding/individual/manage-individual-paid.php' );
			return $render( null, $redirect_url );
		}
		// Case Free Individual.
		if ( 'individual' === $individual_status ) {
			$render = ( require __DIR__ . '/../views/onboarding/individual/manage-individual-free.php' );
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
			return $render();

			// Case Individual Membership Classes.
		} elseif ( $individual_status && Factory::get_instance( SubscriptionHelpers::class )->is_woocommerce_subscription( $individual_status ) ) {
			// Is user onboard pending.
			if ( $user_logged_in && Factory::get_instance( MembershipUser::class )->is_user_subscription_onboarding( $user_id ) ) {
				$redirect_url = wc_get_checkout_url();
			}
			// New User.
			$manage_user_form = ( require __DIR__ . '/../views/onboarding/individual/add-new-paidindividual.php' );
			$render           = ( require __DIR__ . '/../views/onboarding/individual/manage-individual-paid.php' );
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
			return $render( $manage_user_form( $membership_id ), $redirect_url );
		}

		// Valid User Check.
		if ( $user_logged_in ) {
			if ( 'thankyou' === $thank_you_status ) {
				$membership_data = Factory::get_instance( WCFMTools::class )->elemental_get_membership_data( $user_id );
				$render          = ( require __DIR__ . '/../views/onboarding/organisation/merchant-thankyou.php' );
				return $render( $membership_data );
			}
			$is_merchant_check = Factory::get_instance( WCFMTools::class )->am_i_merchant( $user_id );

			if ( $is_merchant_check ) {
				$membership_data = Factory::get_instance( WCFMTools::class )->elemental_get_membership_data( $user_id );
				$render          = ( require __DIR__ . '/../views/onboarding/organisation/merchant-already.php' );
				return $render( $membership_data );

			} elseif ( Factory::get_instance( MembershipUser::class )->is_user_onboarding( $user_id ) ) {
				$render    = ( require __DIR__ . '/../views/onboarding/manage-onboarding.php' );
				$info_form = $this->render_wcfm_step( $user_id );
				return $render( $info_form );
			}
		}
		// Membership Validity Check.
		$valid_memberships = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( true );
		$valid             = \in_array( $membership_id, $valid_memberships, true );

		if ( $valid ) {
			$membership_data     = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( null, $membership_id );
			$render              = ( require __DIR__ . '/../views/onboarding/manage-onboarding.php' );
			$manage_account_form = ( require __DIR__ . '/../views/onboarding/organisation/add-new-organisation.php' );
			// phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
			return $render( $manage_account_form( $membership_id), $membership_data );

		} else {
			$render = ( require __DIR__ . '/../views/onboarding/error/reject-onboarding.php' );
			return $render();
		}

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
		$render             = ( include __DIR__ . '/../views/onboard/table-sponsored-accounts.php' );

		return $render( $sponsored_accounts );

	}

	/**
	 * Enqueue Styles and Scripts
	 * Handles the Styles and Scripts needed for Membership Form front end to look like WCFM.
	 *
	 * @param bool $register_only - flag to register only the user.
	 * @return void
	 */
	private function enqueue_style_scripts( bool $register_only = null ) {
		global $WCFM, $WCFMgs, $WCFMvm;
		$css_lib_url       = $WCFM->plugin_url . 'assets/css/';
		$upload_dir        = wp_upload_dir();
		$wcfm_style_custom = get_option( 'wcfm_style_custom' );

		if ( true === $register_only ) {
			wp_enqueue_style( 'wcfm_membership_steps_css', $WCFMvm->library->css_lib_url_min . 'wcfmvm-style-membership-steps.css', array(), $WCFMvm->version );
			wp_enqueue_style( 'wcfm_menu_css', $WCFM->library->css_lib_url_min . 'menu/wcfm-style-menu.css', array(), $WCFM->version );
			wp_enqueue_style( 'wcfm_settings_css', $css_lib_url . 'settings/wcfm-style-settings.css', array(), $WCFM->version );
			wp_enqueue_style( 'wcfm_template_css', $WCFM->plugin_url . 'templates/classic/template-style.css', array(), $WCFM->version );
			wp_enqueue_style( 'wcfm_dashboard_css', $css_lib_url . 'dashboard/wcfm-style-dashboard.css', array(), $WCFM->version );
			wp_enqueue_style( 'wcfm_custom_css', trailingslashit( $upload_dir['baseurl'] ) . 'wcfm/' . $wcfm_style_custom, array( 'wcfm_menu_css' ), $WCFM->version );

		} else {
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
		}
		// Render WCFM Partner AJax.
		wp_enqueue_script( 'select2_js', $WCFM->plugin_url . 'includes/libs/select2/select2.js', array( 'jquery' ), $WCFM->version, true );
		wp_enqueue_style( 'select2_css', $WCFM->plugin_url . 'includes/libs/select2/select2.css', array(), $WCFM->version );
		wp_enqueue_script( 'wc-country-select' );
		add_action( 'wp_ajax_wcfmvm_store_slug_verification', array( $this, 'wcfmvm_store_slug_verification' ) );
		add_action( 'wp_ajax_nopriv_wcfmvm_store_slug_verification', array( $this, 'wcfmvm_store_slug_verification' ) );
		wp_enqueue_style( 'wcfm_membership_steps_css', $WCFMvm->library->css_lib_url_min . 'wcfmvm-style-membership-steps.css', array(), $WCFMvm->version );
		wp_enqueue_style( 'wcfm_membership_registration_css', $WCFMvm->library->css_lib_url_min . 'wcfmvm-style-membership-registration.css', array(), $WCFMvm->version );
		wp_enqueue_script( 'wcfm_membership_registration_js' );

		// Render Core Control Ajax.
		\wp_enqueue_script( 'elemental-onboard-js' );
		// Localize script Ajax Upload.
		$script_data_array = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'elemental_onboard' ),
		);

		wp_localize_script(
			'elemental-onboard-js',
			'elemental_onboardadmin_ajax',
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

	/**
	 * Store Name Validation
	 */
	public function wcfmvm_store_slug_verification() {
	//phpcs:ignore --WordPress.Security.NonceVerification.Missing - already done in parent form.
		$store_name = wc_clean( $_POST['store_name'] );

		if ( $store_name ) {
			$store_slug = sanitize_title( wc_clean( $store_name ) );
			$store_slug = apply_filters( 'wcfm_generated_store_slug', $store_slug );

			if ( ! is_user_logged_in() && ( username_exists( $store_slug ) || get_user_by( 'slug', $store_slug ) || ! apply_filters( 'wcfm_validate_store_slug', true, $store_slug ) ) ) {
				echo '{"status": false, "message": "' . esc_html__( 'Organisation Name not available.', 'wc-multivendor-membership' ) . '"}';
			} elseif ( is_user_logged_in() ) {
				$member_id           = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
				$the_user            = get_user_by( 'id', $member_id );
				$user_login          = sanitize_title( $the_user->user_login );
				$previous_store_slug = $the_user->user_nicename;
				if ( ( ( $previous_store_slug !== $store_slug ) && ( $user_login !== $store_slug ) && username_exists( $store_slug ) ) || ! apply_filters( 'wcfm_validate_store_slug', true, $store_slug ) ) {
					echo '{"status": false, "message": "' . esc_html__( 'Organisation Name not available.', 'wc-multivendor-membership' ) . '"}';
				} else {
					$store_slug_user = get_user_by( 'slug', $store_slug );
					if ( ! $store_slug_user || ( $store_slug_user && ( $store_slug_user->ID === $member_id ) ) ) {
						echo '{"status": true, "store_slug": "' . esc_textarea( $store_slug ) . '"}';
					} else {
						echo '{"status": false, "message": "' . esc_html__( 'Organisation Name not available.', 'wc-multivendor-membership' ) . '"}';
					}
				}
			} else {
				echo '{"status": true, "store_slug": "' . esc_textarea( $store_slug ) . '"}';
			}
		} else {
			echo '{"status": false, "message": "' . esc_html__( 'Organisation Name not available.', 'wc-multivendor-membership' ) . '"}';
		}

		die;
	}
}
