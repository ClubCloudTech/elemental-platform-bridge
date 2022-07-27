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
		$header       = null;
		$html_library = Factory::get_instance( HTML::class, array( 'view-management' ) );
		$tabs         = array();
		$tabs         = $this->prepare_admin_tabs();

		$render = ( include __DIR__ . '/../../sandbox/views/view-sandbox-main.php' );
		return $render( $header, $html_library, $tabs );
	}

	/**
	 * Render Content Search Results Tab.
	 *
	 * @param SandboxEntity $sandbox_object - the Sandbox Object to Convert to Menu Item.
	 *
	 * @return array - outbound menu.
	 */
	private function prepare_admin_tabs(): array {

		$return_array = array();

		$user_accounts = new MenuTabDisplay(
			esc_html__( 'Manage User Accounts', 'elementalplugin' ),
			'useraccounts',
			fn() => Factory::get_instance( MembershipShortCode::class )->render_membership_shortcode(),
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
