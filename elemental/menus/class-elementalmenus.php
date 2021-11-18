<?php
/**
 * Menu Handlers Elemental.
 *
 * @package elemental/menus/class-elementalmenus.php
 */

namespace ElementalPlugin\Menus;

use ElementalPlugin\Factory;
use ElementalPlugin\Menus\Library\Switches;
use ElementalPlugin\WCFM\Library\WCFMTools;

/**
 * Class WCFM Connect
 */
class ElementalMenus {

	const SHORTCODE_TEAM_MENU = 'elemental_teamlogoname';
	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		Factory::get_instance( Switches::class )->init();
		\add_shortcode( self::SHORTCODE_TEAM_MENU, array( $this, 'header_logo' ) );
	}

	/**
	 * Activate Functions.
	 */
	public function activate() {

	}

	/**
	 * De-Activate Functions.
	 */
	public function de_activate() {

	}
	/**
	 * Shortcode to create Store Details for Displaying in Visitor Templates
	 * It is different from Storelink - as it renders from the perspective of the target store and not the logged in user
	 *
	 * @return string
	 */
	public function header_logo(): ?string {

		// Staff to parent handles sponsored, staff, and Store owner accounts.
		$store_id = Factory::get_instance( WCFMTools::class )->staff_to_parent( \get_current_user_id() );
		if ( ! $store_id ) {
			return null;
		}

		$store_user = \wcfmmp_get_store( $store_id );

		$store_info     = $store_user->get_shop_info();
		$store_gravatar = $store_user->get_avatar();
		$picture_url    = $store_gravatar;
		$output         = ucwords( $store_info['store_name'] );
		$store_url      = Factory::get_instance( WCFMTools::class )->get_store_url( $store_id );

		ob_start();
		?>

		<div class="youzify-primary-nav-area">
			<a href="<?php echo esc_url( $store_url ); ?>" class="elemental-host-link">
			<div class="youzify-primary-nav-settings">
				<div class="youzify-primary-nav-img" style="background-image: url(<?php echo esc_url( $picture_url ); ?> )"></div>
				<span><?php echo esc_attr( $output ) . \esc_html__( ' Team', 'myvideoroom' ); ?></span>
			</div></a>

		</div>
		<?php
		return ob_get_clean();

	}




}
