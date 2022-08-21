<?php
/**
 * Menu Handlers Elemental.
 *
 * @package elemental/menus/class-elementalmenus.php
 */

namespace ElementalPlugin\Module\Menus;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\Membership\Library\LoginHandler;
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
		if ( $attributes['user_id'] && intval( $attributes['user_id'] ) > 1 ) {
			$user_id = intval( $attributes['user_id'] );
		} elseif ( $attributes['user_id'] && intval( $attributes['user_id'] ) < 0 ) {
			return esc_html__( 'All', 'elementalplugin' );
		} else {
			$user_id = \get_current_user_id();
		}

		$store_id = Factory::get_instance( WCFMTools::class )->staff_to_parent( $user_id );

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

		$user       = \get_user_by( 'id', $user_id );
		$attributes = array(
			'force_default' => true,
		);
		if ( 'avatar' === $attributes['image'] && $user_id ) {
			$picture_url = get_avatar_url( $user, $attributes );
		} else {
			$picture_url = \plugins_url( '../../assets/img/user-icon.png', __FILE__ );
		}

		$is_vendor = Factory::get_instance( UserRoles::class )->is_wcfm_vendor();

			// Case Store Owner.
		if ( $is_vendor ) {
			$user_id    = \get_current_user_id();
			$store_name = get_user_meta( $user_id, 'store_name', true );
			$output     = $store_name;
			// @TODO Only whilst in sandbox only mode - as a new control panel page will be built and this url can lose the extension
			$profile_control_url = \get_permalink( 12508 ) . '/ihc/?ihc_ap_menu=profile';

			// Case Not Org Admin Account but signed in.
		} elseif ( $user_id ) {
			$output = $user->display_name;
			// @TODO Only whilst in sandbox only mode - as a new control panel page will be built and this url can lose the extension
			$profile_control_url = \get_permalink( 12508 ) . '/ihc/?ihc_ap_menu=profile';

			// Case Logged Out.
		} else {
			$output              = \esc_html__( 'Sign In', 'elementalplugin' );
			$profile_control_url = '/login';
		}

		if ( 'text' === $attributes['type'] ) {
			return $output;
		}

		ob_start();
		?>
		<div class="elemental-button-primary-nav-area dropdown">
			<a href="<?php echo esc_url( $profile_control_url ); ?>" class="elemental-host-link">
			<div class="elemental-primary-nav-settings">
				<div class="elemental-primary-nav-img" style="background-image: url(<?php echo esc_url( $picture_url ); ?> )"></div>
				<span><?php echo esc_attr( $output ); ?><i class="dropdown myvideoroom-dashicons mvr-icons dashicons-arrow-down-alt2 "></i></span>

			</div></a>
			<div class="dropdown-content">
				<?php
				if ( $user_id ) {
						//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped
						echo Factory::get_instance( LoginHandler::class )->elemental_login_out( 'role' );

					?>
				<a href="/control/account-settings/ihc/?ihc_ap_menu=profile" class="elemental-host-link"><?php echo \esc_html__( 'Account Settings', 'elementalplugin' ); ?></a>
					<?php
				}
				?>


				<?php
				//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped
				echo Factory::get_instance( LoginHandler::class )->elemental_login_out( 'login' );?>
			</div>
		</div>

		<?php
		return ob_get_clean();

	}




}
