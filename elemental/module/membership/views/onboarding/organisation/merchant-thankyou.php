<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @package elemental/membership/views/onboarding/organisation/merchant-thankyou.php
 * @author  Club Cloud based on template from WC Lovers
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\Membership\Library\WooCommerceHelpers;
use ElementalPlugin\Module\WCFM\Library\WCFMHelpers;

return function (
	array $membership_data
): string {
	ob_start();

	?>

<div class="" id="elemental-onboard-listing">
	<div class="elemental-thankyou-logo">
		<?php echo esc_url( get_custom_logo() ); ?>
	</div>
<div class="elemental-clear"></div>

	<div class="elemental-container">
		<div id="wcfm_page_load "></div>
		<div class="">
			<h2 class="elemental-onboard-header"><?php esc_html_e( 'Thank you for Joining', 'elemental' ); ?></h2>

			<div class="elemental-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="" >
		<div id="elemental-adduser-target" class="" >
<h2 class="elemental-onboard-header">
			<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'You have successfully activated your %s membership on our platform. You can continue to use the platform as your Organisation, in which case you might like to set up your Organisation profile via the Organisation Control Panel button. Alternatively, You might wish to join in on the Forums and Groups etc, in which case you will need to activate a User Account via the Add Admin and User Account button.',
						'elemental'
					),
					esc_textarea( $membership_data[0][0]->post_title ),
				);
			?>
			</h2>


			<div class="elemental-thankyou-box">
			<h3 class="elemental-align-left elemental-thankyou-next"><?php esc_html_e( 'What would you like to do next ?', 'elemental' ); ?></h3>
			<div class = "elemental-float-left elemental-split">
			<li class="elemental-thankyou-button  menu-item menu-item-type-post_type menu-item-object-page menu-item-39579"><a href="<?php echo esc_url( get_permalink( get_option( WooCommerceHelpers::SETTING_WCFM_STAFF_USER_CONTROL ) ) ); ?>" class="elementor-item elemental-thankyou-link "><?php esc_html_e( 'Add Admin and User Accounts', 'elemental' ); ?></a></li>
			</div>
			<div class="elemental-float-right elemental-split">
			<li class="elemental-thankyou-button  menu-item menu-item-type-post_type menu-item-object-page menu-item-39579"><a href="<?php echo esc_url( Factory::get_instance( WCFMHelpers::class )->get_wcfm_control_panel_page() ); ?>" class="elementor-item elemental-thankyou-link"><?php esc_html_e( 'Organisation Control Panel', 'elemental' ); ?></a></li>
			</div>
			<div class="elemental-clear"></div>
		</div>

		<div id="elemental-notification-frame"></div>
		<div class="elemental-clearfix"></div><br />
</div>
	</div>
		<?php

		return ob_get_clean();

};
