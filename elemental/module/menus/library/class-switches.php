<?php
/**
 * Menu Handlers Switches Elemental.
 *
 * @package elemental/menus/library/class-switches.php
 */

namespace ElementalPlugin\Module\Menus\Library;

use ElementalPlugin\Factory;
use ElementalPlugin\Library\UserRoles;

/**
 * Class WCFM Connect
 */
class Switches {

	const SHORTCODE_HEADER_SWITCH = 'elemental_headerswitch';
	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		\add_shortcode( self::SHORTCODE_HEADER_SWITCH, array( $this, 'site_header_switch' ) );
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

}
