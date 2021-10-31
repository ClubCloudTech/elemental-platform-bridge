<?php
/**
 * Ajax for Membership Sponsored Accounts.
 *
 * @package elemental/membership/library/class-onboardajax.php
 */

namespace ElementalPlugin\Membership\Library;

/**
 * Class OnboardAjax - Provides the Onboard Ajax Control.
 */
class OnboardWooComm {

	/**
	 * Elemental Ajax Support.
	 * Handles membership function related calls and Ajax.
	 *
	 *  @param  int   $user_id The WP User ID.
	 *  @param  array $update_array - the array containing update information.
	 *  @return void
	 */
	public function update_woocommerce_meta( int $user_id, array $update_array ) {
		$response            = array();
		$response['message'] = 'No Change';

		$data = array(
			'billing_city'          => $city_value,
			'billing_postcode'      => $postcode_value,
			'billing_email'         => $email_value,
			'billing_phone'         => $phone_value,
		);
		foreach ($data as $meta_key => $meta_value ) {
			update_user_meta( $user_id, $meta_key, $meta_value );
		}
	}

	/**
	 * Action Hook Callback function for WooCommerce Processing
	 */
	function wcfmvm_registration_process_on_order_completed( int $order_id ) {
		global $WCFM, $WCFMvm, $wpdb;
		$wcfm_subcription_products = get_option( 'wcfm_subcription_products', array() );

		$wcfm_order_inprogress = get_post_meta( $order_id, '_wcfm_membership_order_processed', true );
		if ( $wcfm_order_inprogress ) {
			echo 'bulldog';
			return null;
		}
/*
		if( !empty( $wcfm_subcription_products ) ) {
			$order         = new WC_Order( $order_id );
			$line_items    = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
			foreach ( $line_items as $item_id => $item ) {
				$product_id = $item->get_product_id();
				
				// WPML Support
				if ( $product_id && defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE && class_exists( 'SitePress' ) ) {
				  global $sitepress;
				  $default_language = $sitepress->get_default_language();
				  $product_id = icl_object_id( $product_id, 'product', false, $default_language );
				}
				
				if( in_array( $product_id , $wcfm_subcription_products ) ) {

*/

	}
}
