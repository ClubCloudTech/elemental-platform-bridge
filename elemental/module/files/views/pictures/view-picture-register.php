<?php
/**
 * Outputs the configuration settings for the video plugin
 *
 * @package MyVideoRoomPlugin\Module\SiteVideo\Views\Login\
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
	object $room_object,
	string $user_encrypted_id
): string {
	$user_picture = $room_object->get_user_picture_url();
	$output       = '<div id="elemental-name-greeting" >';
	$output      .= Factory::get_instance( TemplateIcons::class )->format_button_icon( 'photo' );
	$output      .= '</div>';
	ob_start();

	?>

<div id="elemental-welcome-page" class=" "
	data-checksum="<?php echo esc_attr( $user_encrypted_id ); ?>">

	<div id="elemental-top-notification" class="elemental-button-notification">
		<?php
		// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - already escaped above.
		echo  $output ;
		?>
	</div>

	<div id="elemental-picture" class="elemental-center" style="display:none;">

		<table class="elemental-picture-table elemental-welcome-page">
			<thead class="elemental-table-row">
				<tr>
					<th colspan="2">
						<?php

						if ( $user_picture ) {
							echo '<h2>' . esc_html__( ' Update Your Picture?', 'elementalplugin' ) . '</h2>';

						} else {
							echo '<h2>' . esc_html__( ' Set a Picture?', 'elementalplugin' ) . '</h2>';
						}
						?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr class="elemental-table-row">
					<td class="elemental-left">
						<p id="elemental-picturedescription" class="elemental-table-adjust">
							<?php esc_html_e( 'Update your user profile image', 'elementalplugin' ); ?>
						</p>
						<p id="elemental-text-description-current" class="elemental-hide">
							<?php esc_html_e( 'Current', 'elementalplugin' ); ?></p>
					</td>
					<td class="elemental-right">
						<input type="file" accept=".gif,.jpg,.jpeg,.png" id="elemental-picture-input" />

					</td>
				</tr>
				<tr class="elemental-table-row">
					<td class="elemental-left">
						<?php
						if ( $user_picture ) {
							?>
						<img class="elemental-image-result" src="
							<?php echo esc_url( $user_picture ); ?>" alt="Powered by MyVideoRoom">
							<?php
						} else {
							?>
						<p id="elemental-text-description-current2">
							<?php esc_html_e( 'No Picture yet', 'elementalplugin' ); ?></p>
							<?php
						}
						?>

					</td>
					<td class="elemental-right">
						
						<p id="elemental-text-description-23" class="elemental-hide">
							<?php esc_html_e( 'New', 'elementalplugin' ); ?></p>
						<video id="vid-live" autoplay class="elemental-header-section "></video>
						<div id="vid-result" class="elemental-header-section"></div>




					</td>
				</tr>
				<tr class="elemental-table-row">
					<td class="elemental-left">
						<?php
				// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped -  icon already  escaped.
				echo Factory::get_instance( TemplateIcons::class )->format_button_icon( 'forgetme' );
						?>

					</td>
					<td class="elemental-right">

						<input id="vid-retake" type="button" value="Retake"
							class="elemental-hide " />
						<input id="vid-take" type="button" value="Take Picture"
							class=" elemental-hide " />
						<input id="vid-up" type="button" value="Use This"
							class="elemental-hide" />
						<input id="vid-picture" type="button" value="Use Camera" class="elemental-button-override" />

						<input id="upload-picture" type="button" value="Upload Picture"
							class="elemental-hide" />
					</td>
				</tr>
			</tbody>
		</table>




		<div class="elemental-flex elemental-clear">

		</div>
	</div>

</div>
	<?php
		return ob_get_clean();
};
