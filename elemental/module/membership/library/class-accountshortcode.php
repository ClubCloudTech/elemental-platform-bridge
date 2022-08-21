<?php
/**
 * Onboarding Shortcode for Site.
 *
 * @package elemental/membership/library/class-onboardshortcode.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.
namespace ElementalPlugin\Module\Membership\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\HTML;

/**
 * Class Account Shortcode - Renders the Account Management Screen.
 */
class AccountShortcode {

	/**
	 * Render Account Shortcode .
	 *
	 * @return ?string
	 */
	public function render_account_shortcode(): string {
		wp_enqueue_script( 'elemental-admin-tabs' );
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = $this->prepare_admin_tabs();
		$user_id      = \get_current_user_id();

		$render = ( include __DIR__ . '/../../sandbox/views/view-sandbox-main.php' );
		return $render( $html_library, $tabs, $user_id );
	}

	/**
	 * Render Content Search Results Tab.
	 *
	 * @return array - outbound menu.
	 */
	private function prepare_admin_tabs(): array {

		$return_array = array();

		$user_accounts = new MenuTabDisplay(
			esc_html__( 'Manage User Accounts', 'elementalplugin' ),
			'useraccounts',
			fn() => Factory::get_instance( MembershipShortCode::class )->render_sponsored_account_shortcode(),
			'useraccounts'
		);
		\array_push( $return_array, $user_accounts );

		$admin_accounts = new MenuTabDisplay(
			esc_html__( 'Manage Admin Accounts', 'elementalplugin' ),
			'adminaccounts',
			fn() => \do_shortcode( '[wcfm endpoint="wcfm-staffs"]' ),
			'adminaccounts'
		);
		\array_push( $return_array, $admin_accounts );

		return $return_array;
	}
}
