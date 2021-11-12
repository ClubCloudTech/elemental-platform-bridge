<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @packageelemental/membership/views/onboarding/organisation/merchant-thankyou.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
	array $membership_data
): string {
	ob_start();
	?>

<div class="" id="wcfm_shop_listing">

	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load "></div>
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php esc_html_e( 'Thank you for Joining', 'myvideoroom' ); ?></h2>

			<div class="wcfm-clearfix"></div>
		</div>
		<div id="elemental-adduser-frame" class="wcfm-container wcfm-top-element-container" >
		<div id="elemental-adduser-target" class="wcfm-container wcfm-top-element-container" >
<h2>
			<?php
				echo \sprintf(
					/* translators: %s is the text "Modules" and links to the Module Section */
					\esc_html__(
						'You have activated a %s membership on our platform.',
						'myvideoroom'
					),
					esc_textarea( $membership_data[0][0]->post_title ),
				);
			?>
			</h2>
			<h3 class="elemental-align-left"><?php esc_html_e( 'What would you like to do next ?', 'myvideoroom' ); ?></h3>

			<div class="elemental-thankyou-box">
			<div class = "elemental-float-left elemental-split">
			<li class="myvideoroom-login-button menu-item menu-item-type-post_type menu-item-object-page menu-item-39579"><a href="" class="elementor-item elemental-login-button "><?php esc_html_e( 'Add Organisation Accounts', 'myvideoroom' ); ?></a></li>
			</div>
			<div class="elemental-float-right elemental-split">
			<li class="myvideoroom-login-button menu-item menu-item-type-post_type menu-item-object-page menu-item-39579"><a href="/control" class="elementor-item elemental-login-button "><?php esc_html_e( 'Control Panel', 'myvideoroom' ); ?></a></li>
			</div>
			<div class="elemental-clear"></div>
		</div>

		<div id="elemental-notification-frame"></div>
		<div class="wcfm-clearfix"></div><br />
</div>
	</div>
		<?php

		return ob_get_clean();

};
