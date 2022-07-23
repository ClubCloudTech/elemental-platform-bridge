<?php
/**
 * Functions to provide support to WooCommerce calls in Membership application.
 *
 * @package membership/library/class-woocommercehelpers.php
 */

namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Module\WooCommerceSubscriptions\Library\SubscriptionHelpers;
use ElementalPlugin\Module\BuddyPress\XProfile;
use ElementalPlugin\Library\Ajax;

/**
 * Functions to provide support to WooCommerce calls in Membership application.
 */
class WooCommerceHelpers {

	const SETTING_ONBOARD_SLUG                 = 'elemental-onboard-slug';
	const SETTING_ONBOARD_POST_SUB_SLUG        = 'elemental-post-sub-slug';
	const SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID = 'elemental-product-archive';
	const SETTING_WCFM_STAFF_USER_CONTROL      = 'elemental-staff-user-control';

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		// Sync Checkout fields from Membership data to WooComm.
		add_filter( 'woocommerce_checkout_get_value', array( $this, 'checkout_field_redirect' ), 10, 2 );

		// Post Checkout Basket Redirect back to Onboard.
		add_action( 'woocommerce_thankyou', array( $this, 'post_subs_thankyou' ), 10, 2 );

		// Option for Onboarding Slug in Config Page (settings).
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_onboard_slug_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_onboard_slug_setting' ), 10, 2 );

		// Option for Post Onboarding Ind Subs Landing Page  in Config Page (settings).
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_indv_subs_landing_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_indv_post_subs_setting' ), 10, 2 );

		// Option for Product Archive Template.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_product_archive_settings' ), 10, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_product_archive_setting' ), 10, 2 );

		// Option for Staff Control Panel PostID.
		\add_filter( 'myvideoroom_maintenance_result_listener', array( $this, 'update_staffuser_cp_settings' ), 9, 2 );
		\add_filter( 'elemental_page_option', array( $this, 'add_staffuser_cp_setting' ), 9, 2 );
	}

	/**
	 * WooCommerce Checkout Field Fillout based on User and Xprofile data.
	 *
	 * @param array|null $input - Inbound from Filter.
	 * @param string     $key - the Key to modify.
	 *
	 * @return ?string
	 */
	public function checkout_field_redirect( $input, string $key ) {

		if ( ! is_user_logged_in() ) {
			return null;
		}
		global $current_user;

		switch ( $key ) :
			case 'billing_first_name':
			case 'shipping_first_name':
				return $current_user->first_name;

			case 'billing_last_name':
			case 'shipping_last_name':
				return $current_user->last_name;

			case 'billing_email':
				return $current_user->user_email;

			case 'billing_phone':
				return $current_user->phone;
			case 'billing_company':
				if ( \function_exists( 'xprofile_get_field_data' ) ) {
					$user_id = $current_user->id;
					$field   = \get_option( XProfile::SETTING_XPROFILE_COMPANY );
					$company = xprofile_get_field_data( $field, $user_id );
					return $company;
				} else {
					return null;
				}
			case 'billing_city':
				if ( \function_exists( 'xprofile_get_field_data' ) ) {
					$user_id = $current_user->id;
					$field   = \get_option( XProfile::SETTING_XPROFILE_CITY );
					$city    = xprofile_get_field_data( $field, $user_id );
					return $city;
				} else {
					return null;
				}

		endswitch;
	}

	/**
	 * Redirect Filter Post WooCommerce Checkout.
	 *
	 * @param int $order_id - the WC Order ID.
	 * @return null|void
	 */
	public function post_subs_thankyou( int $order_id ) {
		if (
			! \function_exists( 'wc_get_order' ) ||
			! is_user_logged_in() ||
			! Factory::get_instance( MembershipUser::class )->is_user_subscription_onboarding( \get_current_user_id() )

			) {
			return null;
		}
		$order = wc_get_order( $order_id );
		$url   = \get_site_url() . '/' . get_option( self::SETTING_ONBOARD_SLUG ) . '/?order=' . $order_id;
		if ( ! $order->has_status( 'failed' ) ) {
			wp_safe_redirect( $url );
			exit;
		}
	}

	/**
	 * Add Onboard Slug Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_onboard_slug_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Onboarding Slug', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="text" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( self::SETTING_ONBOARD_SLUG ) . '" value="' . get_option( self::SETTING_ONBOARD_SLUG ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Location of the Onboarding Center Shortcode (slug is path after www.sitename.com/ )', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. Onboard Slug.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_onboard_slug_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_ONBOARD_SLUG );
		\update_option( self::SETTING_ONBOARD_SLUG, $field );
		$response['feedback'] = \esc_html__( 'Slug Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Add Product Archive Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_product_archive_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Product Archive Page ID', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID ) . '"
		value="' . get_option( self::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( ' Post ID Template Switch to Call for a Product Archive', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. Product Archive Update Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_product_archive_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID );
		\update_option( self::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID, $field );
		$response['feedback'] = \esc_html__( 'Product Archive Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Add Post Subscription Thank you Slug Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_indv_post_subs_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Post Payment Individual Sub Slug', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="text" class="mvr-main-button-enabled myvideoroom-maintenance-setting"
			id="' . esc_attr( self::SETTING_ONBOARD_POST_SUB_SLUG ) . '" value="' . get_option( self::SETTING_ONBOARD_POST_SUB_SLUG ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( 'Onboard Post Subscription Thank You Page. This is the page to send a successful individual subscription to (slug is path after www.sitename.com/ )', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}
	/**
	 * Process Update Result. Individual Post Payment Landing.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_indv_subs_landing_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_ONBOARD_POST_SUB_SLUG );
		\update_option( self::SETTING_ONBOARD_POST_SUB_SLUG, $field );
		$response['feedback'] = \esc_html__( 'Destination Saved', 'myvideoroom' );
		return $response;
	}

	/**
	 * Process Order Number from Checkout Thank You.
	 *
	 * @param int $order_id - the WC Order ID.
	 * @return null|void
	 */
	public function process_order_num( int $order_id ) {
		// Check Basics.
		if (
			! \function_exists( 'wc_get_order' ) ||
			! is_user_logged_in() ||
			! Factory::get_instance( MembershipUser::class )->is_user_subscription_onboarding( \get_current_user_id() )

			) {
			return null;
		}
		// Check Order Valid and Paid.
		$order           = wc_get_order( $order_id );
		$order_user_id   = $order->get_user_id();
		$payment_status  = $order->get_payment_method();
		$user_id         = \get_current_user_id();
		$subscription_id = \get_user_meta( $user_id, MembershipUser::USERSUBS_META_KEY_MEMBERSHIP_PRODUCT );
		if ( ! $subscription_id ) {
			return null;
		}
		$verify_subscription = Factory::get_instance( SubscriptionHelpers::class )->verify_subscription_in_order( $order_id, $subscription_id[0] );

		if (
			! $order ||
			$user_id !== $order_user_id ||
			'stripe' !== $payment_status ||
			! $verify_subscription
		) {
			return null;
		}
		// Update Meta Key Status to Data Pending for User.
		$meta_key = MembershipUser::USERSUBS_META_KEY_PENDING;
		delete_user_meta(
			$order_user_id,
			$meta_key
		);
		// Delete Subscription Number.
		$meta_key = MembershipUser::USERSUBS_META_KEY_MEMBERSHIP_PRODUCT;
		delete_user_meta(
			$order_user_id,
			$meta_key
		);
	}

	/**
	 * Add Staff CP Setting to Plugin Menu
	 *
	 * @param array $input - the filter input.
	 * @return array
	 */
	public function add_staffuser_cp_setting( array $input ): array {
		$input_add = ' 
		<td>
		<span>' . esc_html__( 'Staff and User Control Panel Page ID', 'myvideoroom' ) . '</span>
		</td>
		<td>
		<input type="number" size="32"
		class="mvr-main-button-enabled myvideoroom-maintenance-setting"
		id="' . esc_attr( self::SETTING_WCFM_STAFF_USER_CONTROL ) . '"
		value="' . get_option( self::SETTING_WCFM_STAFF_USER_CONTROL ) . '">
			<i class="myvideoroom-dashicons mvr-icons dashicons-editor-help" title="' . \esc_html__( ' Post ID that holds the staff and user add and manage page (use page ID and not slug)', 'myvideoroom' ) . '"></i>
		</td>';
		\array_push( $input, $input_add );
		return $input;
	}

	/**
	 * Process Update Result. Staff CP Setting.
	 *
	 * @param array $response -  Inbound response Elements that will go back to the Ajax Script.
	 * @return array
	 */
	public function update_staffuser_cp_settings( array $response ): array {
		$field = Factory::get_instance( Ajax::class )->get_string_parameter( self::SETTING_WCFM_STAFF_USER_CONTROL );
		\update_option( self::SETTING_WCFM_STAFF_USER_CONTROL, $field );
		$response['feedback'] = \esc_html__( 'PostID Saved', 'myvideoroom' );
		return $response;
	}
}

