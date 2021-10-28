<?php
/**
 * WCFM plugin view
 *
 * Manage Onboarding.
 *
 * @author  Club Cloud based on template from WC Lovers
 * @package elemental/membership/views/manage-child.php
 * @version 1.0.0
 *
 * @param string $add_account_form - add an account form
 */

return function (
	string $user_id_inbound
): string {
	global $WCFM, $WCFMvm, $wp, $WCFM_Query;
		$WCFM->nocache();

		echo '<div id="wcfm-main-contentainer"> <div id="wcfm-content"><div class="wcfm-membership-wrapper"> ';

		echo "<h2 class='wcfm_registration_form_heading'>" . __( 'Registration', 'wc-multivendor-membership' ) . '</h2>';

		$current_step = wcfm_membership_registration_current_step();

		if ( ! wcfm_is_vendor() && ( wcfm_is_allowed_membership() || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) ) ) {
			$application_status = '';

				$member_id          = apply_filters( 'wcfm_current_vendor_id', $user_id_inbound );
				$application_status = get_user_meta( $member_id, 'wcfm_membership_application_status', true );

			if ( $application_status && ( $application_status == 'pending' ) ) {
				$WCFMvm->template->get_template( 'vendor_thankyou.php' );
			} elseif ( isset( $_REQUEST['vmstep'] ) && $current_step && ( $current_step == 'thankyou' ) ) {
				$WCFMvm->template->get_template( 'vendor_thankyou.php' );
			} else {
				$render = ( require __DIR__ . '/vendor_registration.php' );
				echo $render( $user_id_inbound );
			}
		} elseif ( isset( $_REQUEST['vmstep'] ) && $current_step && ( $current_step == 'thankyou' ) ) {
				$WCFMvm->template->get_template( 'vendor_thankyou.php' );
		} else {
			$WCFMvm->template->get_template( 'vendor_membership_block.php' );
		}


		echo '</div></div></div>';
	return ob_get_clean();
};
