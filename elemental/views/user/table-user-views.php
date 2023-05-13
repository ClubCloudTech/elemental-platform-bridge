<?php
/**
 * User View Frames
 *
 * @package ElementalPlugin/views/user/table-user-views.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\TemplateIcons;

/**
 * Render the Picture page
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
						<input type="submit" name="submit" id="submitpassword" class=" elemental-ul-style-menu elemental-welcome-positive " value="Update Password" style="display:none;" disabled>
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
					<a href="" class="elemental-icons elemental-dashicons dashicons-email-alt elemental-user-manager" title="Re-send Invitation Mail"></a>
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
