<?php
/**
 * User View Frames - Deep Dive
 *
 * @package ElementalPlugin/views/user/table-user-views.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\TemplateIcons;
use ElementalPlugin\Module\Membership\Library\MembershipAjax;
use ElementalPlugin\Module\Membership\Membership;

/**
 * Render the User View page
 *
 * @param ?string $details_section Optional details section.
 * @param ?string $room_name Room Name.
 *
 * @return string
 */
return function (
	string $user_encrypted_id,
	object $user,
	?string $user_picture
): string {

	$save_nonce = wp_create_nonce( Membership::MEMBERSHIP_NONCE_PREFIX_DU . strval( $user->ID ) );
	ob_start();

	?>

<div id="elemental-welcome-page" class=" " data-checksum="<?php echo esc_attr( $user_encrypted_id ); ?>">


	<div id="elemental-picture" class="">

		<table class="elemental-picture-table elemental-welcome-page">
			<tbody>
				<tr class="elemental-table-row">
					<td class="elemental-left">

						<?php
							echo '<h2>' . esc_attr( $user->display_name ) . '</h2><br>';
						?>
					</td>
					<td class="elemental-left">

						<?php
							echo '<h4>' . esc_attr( $user->user_email ) . '</h4>';
						?>
</td>
					<td class="elemental-right">
					<?php
				// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped -  icon already  escaped.
				echo Factory::get_instance( TemplateIcons::class )->format_button_icon( 'forgetme' );
						?>
					</td>
				</tr>
				<tr class="elemental-table-row">
					<td style="width:30%; float: left;">
						<?php
						if ( $user_picture ) {
							?>
						<img class="elemental-image-result" src="
							<?php echo esc_url( $user_picture ); ?>" alt="Powered by Elemental">
							<?php
						} else {
							?>
						<p id="elemental-text-description-current2">
							<?php esc_html_e( 'No Picture yet', 'elementalplugin' ); ?></p>
							<?php
						}
						?>
						<input type="submit" name="submit" id="submitemail" class=" elemental-ul-style-menu elemental-welcome-positive " value="Update Email" style="display:none;" disabled>
						<input type="submit" name="submit" id="submitdisplay" class=" elemental-ul-style-menu elemental-welcome-positive " value="Update Display Name" style="display:none;" disabled>
						<input type="submit" name="submit" id="submitpassword" class=" elemental-ul-style-menu elemental-welcome-positive " value="Update Password" style="display:none;" data-source-multi="multi" disabled>
					<div id="elemental-email-status"></div>
					</td>
					<td style="width:70%; float: left;">
					<form autocomplete="off">
					<label for="picture-input"><?php echo \esc_html__( 'Update Picture', 'elementalplugin' ); ?></label><br>	
					<input type="file" name="picture-input" accept=".gif,.jpg,.jpeg,.png" id="elemental-picture-input-remote" autocomplete="false" /><br>
					<label for="password-input"><?php echo \esc_html__( 'Update Password', 'elementalplugin' ); ?></label><br>	
					<input type="password" name="password-input" size="32" id="elemental-password-input" autocomplete="new-password" /><br>
					<label for="email-input"><?php echo \esc_html__( 'Update E-mail', 'elementalplugin' ); ?></label><br>
					<input type="string" name="email-input" size="32" id="elemental-inbound-email" autocomplete="false" /><br>
					<label for="display-name-input"><?php echo \esc_html__( 'Update Display Name', 'elementalplugin' ); ?></label><br>
					<input type="string" name="display-name-input" size="32" id="elemental-display-name-input" class="elemental-input-restrict-alphanumeric" autocomplete="false" /><br>
					</form>
					</td>
				</tr>
				<tr class="elemental-table-row"><td></td>
				<td><label for="re-invite"><?php echo \esc_html__( 'Re-Send Invite', 'elementalplugin' ); ?></label>
					<a id ="<?php echo esc_textarea( MembershipAjax::REINVITE_USER ); ?>"href="" class="elemental-icons elemental-dashicons dashicons-email-alt" title="Re-send Invitation Mail"></a></td>
					<td><label for="reset-password"><?php echo \esc_html__( 'Reset Password', 'elementalplugin' ); ?></label>
					<a id ="reset-password"href="" class="elemental-icons elemental-dashicons dashicons-admin-network " title="Reset Password"></a>
					<label for="delete-user-click"><?php echo \esc_html__( 'Delete User', 'elementalplugin' ); ?></label>
					<a id="delete-user-click" href="" class="elemental-icons elemental-dashicons dashicons-dismiss elemental-delete-user-account"
							data-userid="<?php echo esc_attr( $user_encrypted_id ); ?>"
							data-nonce="<?php echo esc_attr( $save_nonce ); ?>"
							title="<?php echo esc_html__( 'Delete User', 'elementalplugin' ); ?>"
							></a>
					</td>
				</tr>
			</tbody>
		</table>
		<div id="elemental-user-manager-frame-id" class="elemental-flex elemental-clear">

		</div>
	</div>

</div>
	<?php
		return ob_get_clean();
};
