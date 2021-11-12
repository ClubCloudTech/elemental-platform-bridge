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
	/**
	 * Add Product to Cart.
	 *
	 * @param integer $product_id - the WC Product ID.
	 * @return void
	 */
	public function add_product_to_cart( int $product_id ) {

		$found = false;
			// check if product already in cart.
		if ( count( WC()->cart->get_cart() ) > 0 ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->get_id() === $product_id ) {
					$found = true;
				}
			}
			// if product not found, add it.
			if ( ! $found ) {
				WC()->cart->add_to_cart( $product_id );
			}
		} else {
			// if no products in cart, add it.
			WC()->cart->add_to_cart( $product_id );
		}
	}

	/**
	 * Verify Subscription by Order Number
	 *
	 * @param int $order_id - the WC Order ID.
	 * @param int $subscription_id - the subscription product to confirm.
	 * @return bool
	 */
	public function verify_subscription_in_order( int $order_id, int $subscription_id ):bool {
		if ( ! \function_exists( 'wc_get_order' ) ) {
			return false;
		}
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return false;
		}
		$items = $order->get_items();

		foreach ( $items as $item ) {
			$product_id = $item->get_product_id();
			if ( $product_id === $subscription_id ) {
				return true;
			}
		}
		return false;
	}
}
