<?php
/**
 * Menu Handlers Switches Elemental.
 *
 * @package elemental/menus/library/class-switches.php
 */

namespace ElementalPlugin\Module\Menus\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\UltimateMembershipPro\DAO\ElementalUMPDAO;

/**
 * Class WCFM Connect
 */
class Switches {

	const SHORTCODE_HEADER_SWITCH        = 'elemental_headerswitch';
	const SHORTCODE_CONTROL_PANEL_SWITCH = 'elemental_controlpanelswitch';
	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		\add_shortcode( self::SHORTCODE_HEADER_SWITCH, array( $this, 'site_header_switch' ) );
		\add_shortcode( self::SHORTCODE_CONTROL_PANEL_SWITCH, array( $this, 'control_panel_switch' ) );
	}

	/**
	 * Switch Site Header by Role Type
	 *
	 * @return string
	 */
	public function site_header_switch(): string {

		$user_roles = Factory::get_instance( UserRoles::class );

		if ( ! \is_user_logged_in() ) {
			return do_shortcode( '[elementor-template id="54290"]' );
		}
		if ( $user_roles->is_wordpress_administrator() ) {
			return do_shortcode( '[elementor-template id="44805"]' );
		} elseif ( $user_roles->is_wcfm_vendor() ) {
			return do_shortcode( '[elementor-template id="44793"]' );
		} elseif ( $user_roles->is_wcfm_shop_staff() ) {
			return do_shortcode( '[elementor-template id="44807"]' );
		} else {
			return do_shortcode( '[elementor-template id="44795"]' );
		}
	}

	/**
	 * Switch Control Panel Page by Role Type
	 *
	 * @return string
	 */
	public function control_panel_switch(): ?string {
		$user_id       = \get_current_user_id();
		$my_membership = Factory::get_instance( ElementalUMPDAO::class )->get_active_user_membership_levels( $user_id );
		$keys          = \array_keys( $my_membership );

		if ( 'sandboxlifetime' === $my_membership[ $keys[0] ]['level_slug'] ) {
			return do_shortcode( '[elementor-template id="54888"]' );
		} else {
			return do_shortcode( '[elementor-template id="54373"]' );
		}
		return '';
	}

}
