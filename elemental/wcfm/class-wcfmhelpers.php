<?php
/**
 * Helpers for WCFM
 *
 * @package ElementalPlugin\WCFM
 */

namespace ElementalPlugin\WCFM;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\Shortcode as Shortcode;
use \ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Membership\Library\WooCommerceHelpers;

/**
 * Class WCFMHelpers
 */
class WCFMHelpers {

	const SHORTCODE_ARCHIVE_TEMPLATE_REDIRECT = 'elemental_wcfm_archive_switch';
	const SHORTCODE_STORELINK                 = 'elemental_storelink';

	/**
	 * Install the shortcode
	 */
	public function init() {

		add_shortcode( self::SHORTCODE_STORELINK, array( $this, 'store_link_shortcode' ) );
		add_shortcode( self::SHORTCODE_ARCHIVE_TEMPLATE_REDIRECT, array( $this, 'switch_product_archive' ) );
	}


	public function display_products_shortcode( $params = array() ) {
		$id = $params['id'] ?? null;

		return $this->display_products( $id );
	}




	/**
	 * Store_link_shortcode - returns formatted title information for templates
	 *
	 * @param  mixed $params Type== shortcode type to render.
	 * @return string
	 */
	public function store_link_shortcode( $params = array() ): string {
		$type = $params['type'] ?? null;
		return $this->store_link( $type );
	}

	/**
	 * Shortcode to create Store Details for Menu Item
	 * Create Store Shortcode.
	 *
	 * @param string|null $type
	 *
	 * @return string
	 */
	public function store_link( string $type = null ): string {
		// Get Logged-IN User ID.
		$user = wp_get_current_user();

		$user_roles = $this->get_instance( UserRoles::class );

		if ( 'ownerstorelogo' === $type ) {
			$user_id = $this->get_instance( self::class )->staff_to_parent( get_current_user_id() );
			$user    = $this->get_instance( WordPressUser::class )->get_wordpress_user_by_id( $user_id );
		}

		$parent_id = $user->ID;

		if ( 'stafflogo' === $type ) {
			if ( $user_roles->is_wcfm_vendor() ) {
				$store_user     = \wcfmmp_get_store( $user_id );
				$store_gravatar = $store_user->get_avatar();
				$url            = $store_gravatar;
			} else {
				if ( Factory::get_instance( SiteDefaults::class )->is_buddypress_available() ) {
					$url = \bp_core_fetch_avatar(
						array(
							'item_id' => get_current_user_id(),
							'type'    => 'full',
							'html'    => false,
						)
					);
				}
			}
			$store_user = \wcfmmp_get_store( $parent_id );
			$store_info = $store_user->get_shop_info();
			$output     = $store_info['store_name'];

		} elseif ( 'childaccount' === $type || 'breakout' === $type ) {
			// parent is for returning Parent Accounts, Childaccount is for returning childaccount .
			$user_id = \get_current_user_id();

			if ( $user_roles->is_wcfm_vendor() ) {
				$store_user     = wcfmmp_get_store( $user_id );
				$store_info     = $store_user->get_shop_info();
				$store_gravatar = $store_user->get_avatar();
				$url            = $store_gravatar;
				$output         = $store_info['store_name'];
			} elseif ( $user_roles->is_wcfm_shop_staff()
				&& 'breakout' !== $type
			) {
				$url    = bp_core_fetch_avatar(
					array(
						'item_id' => get_current_user_id(),
						'type'    => 'full',
						'html'    => false,
					)
				);
				$output = $this->get_instance( SiteDefaults::class )->displayname();

			} else {

				$url    = \bp_core_fetch_avatar(
					array(
						'item_id' => \get_current_user_id(),
						'type'    => 'full',
						'html'    => false,
					)
				);
				$output = $this->get_instance( SiteDefaults::class )->displayname();
			}
		} elseif ( $user_roles->is_wcfm_shop_staff() || $user_roles->is_wcfm_vendor() ) {

			$store_user = \wcfmmp_get_store( $parent_id );

			$store_info     = $store_user->get_shop_info();
			$store_gravatar = $store_user->get_avatar();
			$url            = $store_gravatar;
			$output         = $store_info['store_name'];
		} else {
			$url    = \bp_core_fetch_avatar(
				array(
					'item_id' => \get_current_user_id(),
					'type'    => 'full',
					'html'    => false,
				)
			);
			$output = $this->get_instance( SiteDefaults::class )->displayname();
		}

		ob_start();

		?>

		<div class="yz-primary-nav-area">

			<div class="yz-primary-nav-settings">
				<div class="yz-primary-nav-img" style="background-image: url(<?php echo $url; ?>)"></div>
				<span>
		<?php
		echo $output
		?>
			</span>

		<?php if ( 'on' == yz_option( 'yz_disable_wp_menu_avatar_icon', 'on' ) ) : ?>
<i class="fas fa-angle-down yz-settings-icon"></i><?php endif; ?>
			</div>

		</div>

		<script type="text/javascript">

			// Show/Hide Primary Nav Message
			jQuery( '.yz-primary-nav-settings' ).click(function (e) {
				// e.preventDefault();
				// Get Parent Box.
				var settings_box = jQuery(this).closest( '.yz-primary-nav-area' );
				// Toggle Menu.
				settings_box.toggleClass( 'open-settings-menu' );
				// Display or Hide Box.
				settings_box.find( '.yz-settings-menu' ).fadeToggle(400);
			});

		</script>

		<?php

		return ob_get_clean();

	}

	/**
	 * OK from Here.
	 * For Clean
	 */

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

	public function switch_product_archive() {
		$is_wcfm_shop = Factory::get_instance( WCFMTools::class )->is_wcfm_store();
		if ( $is_wcfm_shop ) {
			return \do_shortcode( '[elementor-template id="' . \get_option( WooCommerceHelpers::SETTING_WCFM_ARCHIVE_SHORTCODE_ID ) . '"]' );
		} else {
			return \do_shortcode( '[elementor-template id="' . \get_option( WooCommerceHelpers::SETTING_PRODUCT_ARCHIVE_SHORTCODE_ID ) . '"]' );
		}

	}

}
