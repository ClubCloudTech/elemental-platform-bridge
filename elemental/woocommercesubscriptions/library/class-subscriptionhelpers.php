<?php
/**
 * Subscription Helper Functions for WooCommerce
 *
 * @package elemental/woocommercesubscriptions/library/class-subscriptionhelpers.php
 */

namespace ElementalPlugin\WooCommerceSubscriptions\Library;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class SubscriptionHelpers {

	/**
	 * Render shortcode to allow user to update their settings
	 *
	 * @param int $product_id List of shortcode params.
	 *
	 * @return ?string
	 */
	public function is_woocommerce_subscription( int $product_id ) {
		if ( \function_exists( 'wc_get_product' ) ) {
			$product = \wc_get_product( $product_id );
			if ( ! $product ) {
				return false;
			}
		} else {
			return null;
		}
		if ( $product instanceof \WC_Product_Subscription ) {
			return true;
		} else {
			return false;
		}
	}
}
