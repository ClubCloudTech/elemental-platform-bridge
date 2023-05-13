<?php
/**
 * Menu Handlers Elemental.
 *
 * @package elemental/menus/class-elementalmenus.php
 */

namespace ElementalPlugin\Module\Menus;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\UserHelpers;
use ElementalPlugin\Library\UserRoles;
use ElementalPlugin\Module\Files\Library\FileManagement;
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
			<div class="elemental-primary-nav-img"
				style="background-image: url(<?php echo esc_url( $picture_url ); ?> )"></div>
			<span><?php echo esc_attr( $output ); ?></span>
		</div>
	</a>
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
		wp_enqueue_style( 'dashicons' );
		if ( ! isset( $attributes['user_id'] ) ) {
			$user_id = \get_current_user_id();
		}

		$user       = \get_user_by( 'id', $user_id );
		$attributes = array(
			'force_default' => true,
		);
		$ump_image  = get_user_meta( $user_id, 'ihc_avatar' );
		if ( isset( $attributes['image'] ) && 'avatar' === $attributes['image'] && $user_id ) {
			$picture_url = get_avatar_url( $user, $attributes );
		} elseif ( $ump_image ) {
			$picture_url = $ump_image[0];
		} else {
			$picture_url = \plugins_url( '../../assets/img/user-icon.png', __FILE__ );
		}
		$is_vendor         = Factory::get_instance( UserRoles::class )->is_wcfm_vendor();
		$file_notification = Factory::get_instance( FileManagement::class )->check_user_notification( $user_id );
		$docvault_url      = \get_site_url() . get_option( UserHelpers::DOCVAULT_MENU_CP_SETTING );
			// Case Store Owner.
		if ( $is_vendor ) {
			$user_id    = \get_current_user_id();
			$store_name = get_user_meta( $user_id, 'store_name', true );
			$output     = $store_name;
			// @TODO Only whilst in sandbox only mode - as a new control panel page will be built and this url can lose the extension
			$profile_control_url = \get_site_url() . get_option( UserHelpers::PROFILE_MENU_CP_SETTING );
			// Case Not Org Admin Account but signed in.
		} elseif ( $user_id ) {
			$output              = $user->display_name;
			$profile_control_url = \get_site_url() . get_option( UserHelpers::PROFILE_MENU_CP_SETTING );

			// Case Logged Out.
		} else {
			$output              = \esc_html__( 'Sign In', 'elementalplugin' );
			$profile_control_url = '/login';
		}

		if ( isset( $attributes['type'] ) && 'text' === $attributes['type'] ) {
			return $output;
		}

		ob_start();
		?>
<div class="elemental-button-primary-nav-area dropdown">
	<div class="elemental-primary-nav-settings">
		<a href="<?php echo esc_url( $profile_control_url ); ?>" class="elemental-host-link">
			<div class="elemental-primary-nav-img"
				style="background-image: url(<?php echo esc_url( $picture_url ); ?> )"></div>
			<span class="elemental-name-shortcode"><?php echo esc_attr( $output ); ?><i
					class="dropdown elemental-dashicons elemental-icons dashicons-arrow-down-alt2 "></i></span>
		</a>
		<?php
		if ( $file_notification ) {
			?>
		<a href="<?php echo esc_url( $docvault_url ); ?>" class="elemental-host-link">
			<i class="elemental-dashicons elemental-name-shortcode-icon dashicons-media-document"
				title="<?php echo \esc_html__( 'You have a new file in your vault. Click to access', 'elemental' ); ?>"></i></a>

			<?php
		}
		?>
	</div>
	<div class="dropdown-content">
		<?php
		if ( $file_notification ) {
			?>
		<a href="<?php echo esc_url( $docvault_url ); ?>" class="elemental-host-link">
			<i class="elemental-dashicons elemental-name-shortcode-icon dashicons-media-document"
				title="<?php echo \esc_html__( 'You have a new file in your vault. Click to access', 'elemental' ); ?>"></i>
			<?php esc_html_e( 'New Documents to View', 'elementalplugin' ); ?>
		</a>

			<?php
		} elseif ( \is_user_logged_in() ) {
			?>
		<a href="<?php echo esc_url( $docvault_url ); ?>"
			class="elemental-host-link"><?php echo \esc_html__( 'Document Vault', 'elementalplugin' ); ?></a>
			<?php
		}

		if ( $user_id ) {
				//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped
				echo Factory::get_instance( LoginHandler::class )->elemental_login_out( 'role' );

			?>
		<a href="<?php echo esc_url( $profile_control_url ); ?>"
			class="elemental-host-link"><?php echo \esc_html__( 'Account Settings', 'elementalplugin' ); ?></a>
			<?php
		}

				//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped
				echo Factory::get_instance( LoginHandler::class )->elemental_login_out( 'login' );
		?>

	</div>
</div>

		<?php
		return ob_get_clean();

	}




}
