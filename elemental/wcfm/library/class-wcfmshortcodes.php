<?php
/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 *
 * @package wcfm/library/class-wcfmshortcodes.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Library\WordPressUser;
use ElementalPlugin\WCFM\Library\WCFMHelpers;
use ElementalPlugin\WCFM\Library\WCFMTools;

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

/**
 * Class WCFMShortcodes - Display Shortcodes for WCFM Integration
 */
class WCFMShortcodes {

	const SHORTCODE_SHOW_STAFF = 'elemental_show_staff';
	const SHORTCODE_STORELINK  = 'elemental_storelink';
	const SHORTCODE_GETNAME    = 'elemental_wcfm_storename';

	// Backward Compatible legacy shortcode names.
	const SHORTCODE_BACK_COMPAT_GETNAME = 'ccmenu';

	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		add_shortcode( self::SHORTCODE_SHOW_STAFF, array( $this, 'show_wcfm_staff' ) );
		add_shortcode( self::SHORTCODE_STORELINK, array( $this, 'store_link_shortcode' ) );
		add_shortcode( self::SHORTCODE_GETNAME, array( $this, 'elemental_getname' ) );

		// Backward Compatible legacy shortcode declarations.
		add_shortcode( self::SHORTCODE_BACK_COMPAT_GETNAME, array( $this, 'elemental_getname' ) );
	}

	/**
	 * Show WCFM Staff Shortcode Handler (or Staff and Owner).
	 *
	 * @param array $attr - Shortcode Attributes.
	 * @return string
	 */
	public function show_wcfm_staff( $attr ): string {
		global $WCFM;
		if ( isset( $attr['show_owner'] ) ) {
			$show_owner = $attr['show_owner'];
		}

		return $this->show_wcfm_staff_worker( $show_owner );
	}

	/**
	 * Show WCFM Staff (and optionally Owner).
	 *
	 * @param bool $show_owner - Whether to Return the Owner as well as team.
	 * @return string
	 */
	public function show_wcfm_staff_worker( bool $show_owner = null ): string {

			$vendor_id = Factory::get_instance( WCFMTools::class )->get_wcfm_page_owner();

			$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
			$args            = array(
				'role__in'    => array( $staff_user_role ),
				'orderby'     => 'ID',
				'order'       => 'ASC',
				'offset'      => 0,
				'number'      => -1,
				'meta_key'    => '_wcfm_vendor',
				'meta_value'  => $vendor_id,
				'count_total' => false,
			);

			$wcfm_staff   = get_users( $args );
			$result_count = count( $wcfm_staff );

			if ( $show_owner ) {
				$shop_staff_html = $vendor_id;
				$is_first        = false;
			} else {
				$shop_staff_html = '';
				$is_first        = true;
			}

			if ( $result_count >= 1 ) {

				foreach ( $wcfm_staff as $wcfm_staff_member ) {
					if ( ! $is_first ) {
						$shop_staff_html .= ', ';
					}
					$shop_staff_html .= $wcfm_staff_member->ID;
					$is_first         = false;
				}

				return do_shortcode( '[youzer_members type="alphabetical" include="' . $shop_staff_html . '" ]' );
			}
			return '<h1>' . esc_html__( 'This Organisation has no Member Accounts', 'myvideoroom' ) . '</h1>';
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
	 * @param string $type - the type of shortcode.
	 *
	 * @return string
	 */
	public function store_link( string $type = null ): string {
		// Get Logged-IN User ID.
		$user = wp_get_current_user();

		$user_roles = Factory::get_instance( UserRoles::class );

		if ( 'ownerstorelogo' === $type ) {
			$user_id = Factory::get_instance( WCFMHelpers::class )->staff_to_parent( get_current_user_id() );
			$user    = Factory::get_instance( WordPressUser::class )->get_wordpress_user_by_id( $user_id );
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
				$output = Factory::get_instance( SiteDefaults::class )->displayname();

			} else {

				$url    = \bp_core_fetch_avatar(
					array(
						'item_id' => \get_current_user_id(),
						'type'    => 'full',
						'html'    => false,
					)
				);
				$output = Factory::get_instance( SiteDefaults::class )->displayname();
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
			$output = Factory::get_instance( SiteDefaults::class )->displayname();
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

				var settings_box = jQuery(this).closest( '.yz-primary-nav-area' );
					settings_box.toggleClass( 'open-settings-menu' );
					settings_box.find( '.yz-settings-menu' ).fadeToggle(400);
			});

		</script>

		<?php

		return ob_get_clean();

	}
	/**
	 * Elemental Get User Name for Merchants and Staff Shortcode.
	 *
	 * @param array $attr - shortcode attributes.
	 * @return string|null
	 */
	public function elemental_getname( $attr = array() ): ?string {
		if ( isset( $attr['user_id'] ) ) {
			$user_id = $attr['user_id'];
		}
		return $this->elemental_getname_worker( $user_id );
	}
	/**
	 * Elemental Get User Name for Merchants and Staff.
	 *
	 * @param int $user_id - the user id( can be left null to try current logged in user ).
	 * @return string|null
	 */
	public function elemental_getname_worker( int $user_id = null ): ?string {

		if ( ! \function_exists( 'wcfmmp_get_store' ) ) {
			return null;
		}

		if ( $user_id ) {
			$user = get_user_by( 'id', $user_id );
		} else {
			$user = wp_get_current_user();
		}

		if ( ! $user ) {
			return null;
		}

		$user_roles = Factory::get_instance( UserRoles::class );

		if ( $user_roles->is_wcfm_vendor() ) {
			return ucwords( $user->user_nicename );

		} elseif ( $user_roles->is_wcfm_shop_staff() ) {

			$parentID   = $user->_wcfm_vendor;
			$store_user = wcfmmp_get_store( $parentID );
			$store_info = $store_user->get_shop_info();

			return ucwords( $store_info['store_slug'] );
		}

		return ucwords( $user->user_nicename );
	}
}
