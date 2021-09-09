<?php
/**
 * Renders the form for changing the user video preference.
 *
 * @param string|null $current_user_setting
 * @param array $available_layouts
 *
 * @package MyVideoRoomExtrasPlugin\Views
 */

use MyVideoRoomExtrasPlugin\Entity\UserVideoPreference;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\DAO\UserVideoPreference as UserVideoPreferenceDAO;


return function (
	array $available_layouts,
	array $available_receptions,
	UserVideoPreference $current_user_setting = null,
	string $room_name,
	int $id_index = 0,
	int $user_id = null
): string {
	ob_start();

	?>
	<div id="video-host-wrap"style="border: 3px solid #969696;
	background: #ebedf1;
	padding: 12px;
	margin: 5px;
		">
				<h1>Video Host Settings for
			<?php
				$output = str_replace( '-', ' ', $room_name );
				echo esc_html( ucwords( $output ) );
			?>
				</h1>

				<?php
				// room permissions info.
				$reception_enabled = Factory::get_instance( UserVideoPreferenceDAO::class )->read_user_video_settings( $user_id, $room_name, 'reception_enabled' );
				if ( $reception_enabled ) {
					echo '<a class="button button-primary" style="background-color:green">Reception Enabled</a>';
				}

				?>

				<form method="post" action="">
					<input name="myvideoroom_extras_user_room_name" type="hidden" value="<?php echo esc_attr( $room_name ); ?>" />

					<label for="myvideoroom_extras_user_layout_id_preference_<?php echo esc_attr( $id_index ); ?>">
					<hr>
						<h2 style ="display: inline">Video layout setting:</h2>
					</label>
					<select
							class="myvideoroom_extras_user_layout_id_preference"
							name="myvideoroom_extras_user_layout_id_preference"
							id="myvideoroom_extras_user_layout_id_preference_<?php echo esc_attr( $id_index ); ?>"
					>
						<?php
						if ( ! $current_user_setting || ! $current_user_setting->get_layout_id() ) {
							echo '<option value="" selected disabled> --- </option>';
						}

						foreach ( $available_layouts as $available_layout ) {
							$slug = $available_layout->slug;

							if ( ! $slug ) {
								$slug = $available_layout->id;
							}

							if ( $current_user_setting
								&& $current_user_setting->get_layout_id() === $slug
							) {
								echo '<option value="' . esc_attr( $slug ) . '" selected>' . esc_html( $available_layout->name ) . '</option>';
							} else {
								echo '<option value="' . esc_attr( $slug ) . '">' . esc_html( $available_layout->name ) . '</option>';
							}
						}
						?>
					</select>
					<p>
						Use this setting to control the Layout of your Room -
						you and your guests will see the template you select when coming for meetings. There are lots of templates to chose from.
					</p>


					<label for="myvideoroom_extras_user_show_floorplan_preference_<?php echo esc_attr( $id_index ); ?>">
					<p style ="display: inline"><b>Disable Interactive Floorplan:</b> </p>
					</label>

					<input
						type="checkbox"
						class="myvideoroom_extras_user_show_floorplan_preference"
						name="myvideoroom_extras_user_show_floorplan_preference"
						id="myvideoroom_extras_user_show_floorplan_preference_<?php echo esc_attr( $id_index ); ?>"
						<?php echo $current_user_setting && $current_user_setting->get_show_floorplan_setting() ? 'checked' : ''; ?>
					/>
					<p style ="display: inline">Disable Floorplan and use classic video without templates. Please note if you select this,
					the reception will automatically be turned on and users will be held in reception until you allow them in. </p>

					<hr />


					<label for="myvideoroom_extras_user_reception_enabled_preference_<?php echo esc_attr( $id_index ); ?>">
					<h2 style ="display: inline">Enable Reception</h2>
					</label>
					<input
						type="checkbox"
						class="myvideoroom_extras_user_reception_enabled_preference"
						name="myvideoroom_extras_user_reception_enabled_preference"
						id="myvideoroom_extras_user_reception_enabled_preference_<?php echo esc_attr( $id_index ); ?>"
						<?php echo $current_user_setting && $current_user_setting->is_reception_enabled() ? 'checked' : ''; ?>
					/>
					<p>
						Enable this if you want to have guests wait in a secure location that you must allow into your space,
						or disable if you want people to pop in or out of your room. This setting is automatically applied if you
						chose the "Disable Floorplan" feature which automatically turns on Reception.<br>
					</p>

					<br>

					<label for="myvideoroom_extras_user_reception_id_preference_<?php echo esc_attr( $id_index ); ?>">
						<b>Reception Appearance</b>
					</label>
					<select
							class="myvideoroom_extras_user_reception_id_preference"
							name="myvideoroom_extras_user_reception_id_preference"
							id="myvideoroom_extras_user_reception_id_preference_<?php echo esc_attr( $id_index ); ?>"
					>
						<?php
						if ( ! $current_user_setting || ! $current_user_setting->get_reception_id() ) {
							echo '<option value="" selected disabled> --- </option>';
						}

						foreach ( $available_receptions as $available_reception ) {
							$slug = $available_reception->slug;

							if ( ! $slug ) {
								$slug = $available_reception->id;
							}

							if ( $current_user_setting
								&& $current_user_setting->get_reception_id() === $slug
							) {
								echo '<option value="' . esc_attr( $slug ) . '" selected>' . esc_html( $available_reception->name ) . '</option>';
							} else {
								echo '<option value="' . esc_attr( $slug ) . '">' . esc_html( $available_reception->name ) . '</option>';
							}
						}
						?>
					</select>
					<br><br>
					<p>
						Use this setting to decide what you want your Video Space reception to look like. This will be shown for all guests while they wait for
						admission into the room. The enable reception setting must be turned on for this setting to take effect.
					</p>

			<h2 style ="display: inline">Reception Waiting Room Video</h2><br>

					<label for="myvideoroom_extras_user_reception_video_enabled_preference_<?php echo esc_attr( $id_index ); ?>">
					<b>Enable Custom Video for Reception :</b>
					</label>
					<input
						type="checkbox"
						class="myvideoroom_extras_user_reception_video_enabled_preference"
						name="myvideoroom_extras_user_reception_video_enabled_preference"
						id="myvideoroom_extras_user_reception_video_enabled_preference_<?php echo esc_attr( $id_index ); ?>"
						<?php echo $current_user_setting && $current_user_setting->get_reception_video_enabled_setting() ? 'checked' : ''; ?>
					/>

					<label for="myvideoroom_extras_user_reception_waiting_video_url_<?php echo esc_attr( $id_index ); ?>">
					Video URL:
					</label>
					<input
								type="text"
								id="myvideoroom_extras_user_reception_waiting_video_url"
								name="myvideoroom_extras_user_reception_waiting_video_url"
								style= "    width: 50%;    background: #e3e7e8; "
								value="<?php /* phpcs:ignore -- is escaped properly.*/ echo trim( esc_url_raw( Factory::get_instance( UserVideoPreferenceDAO::class )->read_user_settings( $user_id, $room_name, 'reception_video_url' ) ) ); ?>">

					<br><br>
					<p>
						This setting controls whether you want your guests to see a video or movie channel if Reception is enabled.
						Enter a url in the form of <b> https://youvideoservice.com/yourvideofolder/video.mp4 </b>- and this video will be displayed to your guests in your Dynamic
						reception areas if you have enabled a guest reception template option that can show video.
					</p>
					<hr>

					<?php wp_nonce_field( 'myvideoroom_extras_update_user_video_preference', 'nonce' ); ?>
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  />
				</form>
	</div>
	<?php
	return ob_get_clean();
};
