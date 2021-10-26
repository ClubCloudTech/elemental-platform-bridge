<?php

/**
 * Renders the form for changing the user video preference.
 *
 * @param string|null $current_user_setting
 * @param array $available_layouts
 *
 * @package ElementalPlugin\Views
 */

use ElementalPlugin\Entity\SecurityVideoPreference;
use ElementalPlugin\Factory;
use ElementalPlugin\DAO\SecurityVideoPreference as SecurityVideoPreferenceDAO;
use ElementalPlugin\Core\SiteDefaults;
use ElementalPlugin\DAO\ModuleConfig;
use ElementalPlugin\BuddyPress\BuddyPressConfig;
use ElementalPlugin\Library\Templates\SecurityButtons;


return function (
	SecurityVideoPreference $current_user_setting = null,
	string $room_name,
	int $id_index = 0,
	int $user_id = null,
	string $group_name = null
): string {
	ob_start(); ?>
	<div id="security-video-host-wrap" style="border: 3px solid #969696;
	background: #ebedf1;
	padding: 12px;
	margin: 5px;">
	<h1>Security Settings for
	<?php
	$output = str_replace( '-', ' ', $room_name );
	echo esc_attr( ucwords( $output ) );
	?>
	</h1>

				<?php
				// room permissions info.
				$site_override              = Factory::get_instance( SecurityVideoPreferenceDao::class )->read_security_settings( SiteDefaults::USER_ID_SITE_DEFAULTS, SiteDefaults::ROOM_NAME_SITE_DEFAULT, 'site_override_enabled' );
				$room_disabled              = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $user_id, $room_name, 'room_disabled' );
				$anonymous_enabled          = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $user_id, $room_name, 'anonymous_enabled' );
				$allow_role_control_enabled = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $user_id, $room_name, 'allow_role_control_enabled' );

				if ( ! $site_override ) {

					if ( Factory::get_instance( \ElementalPlugin\Core\SiteDefaults::class )->is_buddypress_available() ) {
						$restrict_group_to_members_enabled = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_security_settings( $user_id, $room_name, 'restrict_group_to_members_enabled' );
						if ( $restrict_group_to_members_enabled ) {
							echo '<a class="button button-primary" style="background-color:blue">Restricted to Members</a>';
						}
					}

					if ( ! $room_disabled ) {
						echo '<a class="button button-primary" style="background-color:green">Room Enabled</a>';
					} else {
						echo '<a class="button button-primary" style="background-color:red">Room Disabled</a>';
					}
					if ( $allow_role_control_enabled ) {
						echo '<a class="button button-primary" style="background-color:blue">Restricted to Roles</a>';
					}

					if ( $anonymous_enabled || $allow_role_control_enabled ) {
						echo '<a class="button button-primary" style="background-color:blue">Anonymous Disabled</a>';
					}
				} else {
					echo esc_attr( Factory::get_instance( SecurityButtons::class )->site_wide_enabled( 'nourl' ) );
					echo '<p>An Administrator is overriding local settings with ones applied centrally. Certains Settings stored here may not apply</p>';
				}

	?>
		<form method="post" action="">
			<input name="myvideoroom_extras_security_room_name" type="hidden" value="<?php echo esc_attr( $room_name ); ?>" />
			<input name="myvideoroom_extras_security_user_id" type="hidden" value="
								<?php
								if ( bp_is_groups_component() ) {
									global $bp;
									$group_id = $bp->groups->current_group->id;
									echo esc_attr( $group_id );
								}
								?>
					" />
			<hr>
			<h2 style="display: inline">Disable Room</h2>
			</label>
			<input type="checkbox" class="myvideoroom_extras_security_room_disabled_preference" name="myvideoroom_extras_security_room_disabled_preference" id="myvideoroom_extras_security_room_disabled_preference_<?php echo esc_attr( $id_index ); ?>" <?php echo $current_user_setting && $current_user_setting->is_room_disabled() ? 'checked' : ''; ?> />
			<p>
				Enable this setting to switch off your room. No one will be able to join it.
			</p>

			<hr />

			<label for="myvideoroom_extras_security_anonymous_enabled_preference_<?php echo esc_attr( $id_index ); ?>">
				<h2 style="display: inline">Restrict Anonymous Access ( Force Users to Register )</h2>
			</label>
			<input type="checkbox" class="myvideoroom_extras_security_anonymous_enabled_preference" name="myvideoroom_extras_security_anonymous_enabled_preference" id="myvideoroom_extras_security_anonymous_enabled_preference_<?php echo esc_attr( $id_index ); ?>" <?php echo $current_user_setting && $current_user_setting->is_anonymous_enabled() ? 'checked' : ''; ?> />
			<p>
				If you enable this setting, anonymous users from the Internet WILL NOT be able to enter your room. The only way
				someone can enter your room is if they have an account on your website. This means that external users, will have
				to go through whatever registration process exists for your website. Default is DISABLED= anonymous access is allowed.
			</p>

			<hr />


			<label for="myvideoroom_extras_security_allow_role_control_enabled_preference_<?php echo esc_attr( $id_index ); ?>">
				<h2 style="display: inline">Enable Role Control - For Allowed Roles</h2>
			</label>
			<input type="checkbox" class="myvideoroom_extras_security_allow_role_control_enabled_preference" name="myvideoroom_extras_security_allow_role_control_enabled_preference" id="myvideoroom_extras_security_allow_role_control_enabled_preference_<?php echo esc_attr( $id_index ); ?>" <?php echo $current_user_setting && $current_user_setting->is_allow_role_control_enabled() ? 'checked' : ''; ?> />

			<br>
			<p>
				If you enable this setting only the following roles will be allowed to access your rooms. If you want to reverse the setting, then click
				'block these roles instead' which will allow all roles - except for the ones you select.
			</p>

			<label for="myvideoroom_extras_security_allowed_roles_preference_<?php echo esc_attr( $id_index ); ?>">
				Allowed Roles setting:
			</label>
			<select multiple="multiple" class="myvideoroom_extras_security_allowed_roles_preference" name="myvideoroom_extras_security_allowed_roles_preference[]" style="width:50%" id="myvideoroom_extras_security_allowed_roles_preference">
				<?php

				$output = Factory::get_instance( SecurityVideoPreferenceDAO::class )->read_multi_checkbox_admin_roles( $user_id, $room_name, 'allowed_roles' );
				echo esc_attr( $output );

				?>
			</select>

			<label for="myvideoroom_extras_security_block_role_control_enabled_preference_<?php echo esc_attr( $id_index ); ?>">
				<b>Block</b> These Roles Instead
			</label>
			<input type="checkbox" class="myvideoroom_extras_security_block_role_control_enabled_preference" name="myvideoroom_extras_security_block_role_control_enabled_preference" id="myvideoroom_extras_security_block_role_control_enabled_preference_<?php echo esc_attr( $id_index ); ?>" 
																																																																	   <?php
																																																																								echo $current_user_setting && $current_user_setting->is_block_role_control_enabled() ? 'checked' : '';
																																																																		?>
			 />
			<br>
			<br>
			<p>
				Use this setting to determine what user roles you want to explicitly allow or - the reverse ( block all users but a specific role ) if you tick the Block Role option.
			</p>
			<hr>
	<?php
	global $bp;
	$is_group_page   = $bp->groups->current_group->slug;
	$is_profile_page = \bp_is_my_profile();
	// Group setting from BP.
	if ( ( Factory::get_instance( ModuleConfig::class )->module_activation_status( SiteDefaults::MODULE_BUDDYPRESS_GROUP_ID ) )
		&& ( Factory::get_instance( ModuleConfig::class )->module_activation_status( SiteDefaults::MODULE_BUDDYPRESS_ID ) ) && $is_group_page
	) {
		echo esc_attr( Factory::get_instance( BuddyPressConfig::class )->render_group_menu_options( $bp->groups->current_group->creator_id, $room_name, $id_index ) );
	}
	// Friends Setting from BP.
	if ( ( Factory::get_instance( ModuleConfig::class )->module_activation_status( SiteDefaults::MODULE_BUDDYPRESS_FRIENDS_ID ) )
		&& ( Factory::get_instance( ModuleConfig::class )->module_activation_status( SiteDefaults::MODULE_BUDDYPRESS_ID ) ) && $is_profile_page
	) {
		echo esc_attr( Factory::get_instance( BuddyPressConfig::class )->render_friends_menu_options( $user_id, $room_name, $id_index ) );
	}

	?>


	<?php wp_nonce_field( 'myvideoroom_extras_update_security_video_preference', 'nonce' ); ?>

			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
		</form>
	</div>

	<?php
	return ob_get_clean();
};
