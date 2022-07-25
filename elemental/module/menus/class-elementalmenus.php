<?php
/**
 * Menu Handlers Elemental.
 *
 * @package elemental/menus/class-elementalmenus.php
 */

namespace ElementalPlugin\Module\Menus;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Menus\Library\Switches;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Class WCFM Connect
 */
class ElementalMenus {

	const SHORTCODE_TEAM_MENU = 'elemental_teamlogoname';
	const SHORTCODE_USER_MENU = 'elemental_userlogoname';
	/**
	 * Runtime Shortcodes and Setup
	 */
	public function init() {
		Factory::get_instance( Switches::class )->init();
		\add_shortcode( self::SHORTCODE_TEAM_MENU, array( $this, 'render_header_logo_shortcode' ) );
		\add_shortcode( self::SHORTCODE_USER_MENU, array( $this, 'render_user_logo_shortcode' ) );
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
	 * Render shortcode to show menu item with Parent team logo, and team name.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_user_logo_shortcode( $attributes = array() ): ?string {
		if ( ! $attributes ) {
			$attributes = array();
		}
		$attributes['user-avatar'] = true;

		return $this->render_user_logo_worker( $attributes );
	}

	/**
	 * Render shortcode to show menu item with Parent team logo, and team name.
	 *
	 * @param array|string $attributes List of shortcode params.
	 *
	 * @return ?string
	 */
	public function render_header_logo_shortcode( $attributes = array() ): ?string {
		if ( ! $attributes ) {
			$attributes = array();
		}
		return $this->render_header_logo_worker( $attributes );
	}

	/**
	 * Controller to render team logo and name Shortcode
	 * It is different from Storelink - as it renders from the perspective of the target store and not the logged in user
	 *
	 * @param array $attributes - the type of shortcode to return (formatted or not).
	 * @return string
	 */
	private function render_header_logo_worker( array $attributes = null ): ?string {

		$store_id = Factory::get_instance( WCFMTools::class )->staff_to_parent( \get_current_user_id() );

		// Staff to parent handles sponsored, staff, and Store owner accounts.

		if ( ! $store_id ) {
			return null;
		}

		$store_user = \wcfmmp_get_store( $store_id );

		$store_info     = $store_user->get_shop_info();
		$store_gravatar = $store_user->get_avatar();
		$picture_url    = $store_gravatar;
		$output         = $store_info['store_name'];
		$store_url      = Factory::get_instance( WCFMTools::class )->get_store_url( $store_id );

		if ( 'text' === $attributes['type'] ) {
			return $output;
		}
			ob_start();
		?>
		<div class="elemental-button-primary-nav-area">
			<a href="<?php echo esc_url( $store_url ); ?>" class="elemental-host-link">
			<div class="elemental-primary-nav-settings">
				<div class="elemental-primary-nav-img" style="background-image: url(<?php echo esc_url( $picture_url ); ?> )"></div>
				<span><?php echo esc_attr( $output ); ?></span>
			</div></a>
		</div>
		<?php
			return ob_get_clean();
	}

	/**
	 * Controller to render team logo and name Shortcode
	 * It is different from Storelink - as it renders from the perspective of the target store and not the logged in user
	 *
	 * @param array $attributes - the type of shortcode to return (formatted or not).
	 * @return string
	 */
	private function render_user_logo_worker( array $attributes = null ): ?string {

		if ( ! $attributes['user_id'] ) {
			$user_id = \get_current_user_id();
		}

		// Staff to parent handles sponsored, staff, and Store owner accounts.

		if ( ! $user_id ) {
			return null;
		}

		$user                = \get_user_by( 'id', $user_id );
		$attributes          = array(
			'force_default' => true,
		);
		$picture_url         = get_avatar_url( $user, $attributes );
		$output              = $user->display_name;
		$profile_control_url = \get_permalink( 12508 );

		if ( 'text' === $attributes['type'] ) {
			return $output;
		}

		ob_start();
		?>
		<div class="elemental-button-primary-nav-area">
		<a href="<?php echo esc_url( $profile_control_url ); ?>" class="elemental-host-link">
			<div class="elemental-primary-nav-settings">
				<span><i class="myvideoroom-dashicons mvr-icons dashicons-admin-users"></i><?php echo esc_attr( $output ); ?></span>
			</div></a>
		</div>
		<?php
		return ob_get_clean();

	}




}
