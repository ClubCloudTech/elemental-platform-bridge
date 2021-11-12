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

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

use ElementalPlugin\Factory;
use ElementalPlugin\Membership\Library\MembershipUser;

return function (
	string $user_id_inbound,
	object $registration
): string {
	global $WCFM, $WCFMvm;

	$WCFM->nocache();
	ob_start();
	?>
<div id ="elemental-registration-info"
data-
></div>
<div id="wcfm-main-contentainer">
	<div id="wcfm-content">
		<div class="wcfm-membership-wrapper">
			<h2 class="wcfm_registration_form_heading elemental-content-tab">
				<?php esc_html_e( 'Registration', 'myvideoroom' ); ?> </h2>
			<div class="elemental-clear"></div>

			<?php
			$current_step    = wcfm_membership_registration_current_step();
			$user_onboarding = Factory::get_instance( MembershipUser::class )->is_user_onboarding( $user_id_inbound );

			if ( ! wcfm_is_vendor() && ( wcfm_is_allowed_membership() || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || $user_onboarding ) ) {
				$application_status     = '';
				$member_id              = apply_filters( 'wcfm_current_vendor_id', $user_id_inbound );
					$application_status = get_user_meta( $member_id, 'wcfm_membership_application_status', true );

				if ( $application_status && ( 'pending' === $application_status ) ) {
					$WCFMvm->template->get_template( 'vendor_thankyou.php' );

				} elseif ( isset( $_REQUEST['vmstep'] ) && $current_step && ( 'thankyou' === $current_step ) ) {
					$WCFMvm->template->get_template( 'vendor_thankyou.php' );

				} else {
					//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - Registration form already escaped.
					echo $registration( $user_id_inbound );
				}
			} elseif ( isset( $_REQUEST['vmstep'] ) && $current_step && ( 'thankyou' === $current_step ) ) {

				$WCFMvm->template->get_template( 'vendor_thankyou.php' );
			} else {

				$WCFMvm->template->get_template( 'vendor_membership_block.php' );
			}
			?>
		</div>
	</div>
</div>
	<?php
	return ob_get_clean();
};
