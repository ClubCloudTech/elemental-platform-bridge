<?php

/**
 * Display section templates
 *
 * @package MyVideoRoomExtrasPlugin\Library
 */

namespace MyVideoRoomExtrasPlugin\Library\Templates;

use MyVideoRoomExtrasPlugin\Core\SiteDefaults;
use MyVideoRoomExtrasPlugin\Factory;
use MyVideoRoomExtrasPlugin\Shortcode as Shortcode;
use MyVideoRoomExtrasPlugin\Core\PageFilters;



/**
 * Class SectionTemplate
 */
class SecurityTemplates extends Shortcode {




	/**  Security Templates
	 * Render Main Dashboard Template for user's own account control panel
	 *
	 * @return string
	 */


	/**  Room Blocked by Site
	 */



	public static function room_blocked_by_site( $input_type = null ) {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}

		?>
		<link rel="stylesheet" href="<?php echo plugins_url( 'profile.css', __FILE__ ); ?>">
		<div class="cc-row">

			<table style="width:100%">
				<tr>
					<th style="width:50%">
					<img class="cc-user-image" src="
					<?php
											$url = Factory::get_instance( PageFilters::class )->get_picture_template( $user_id );
											echo esc_url( $url );
					?>
					" alt="Image">

					</th>
					<th>

				<?php
				if ( 'anonymous' === $input_type ) {
						echo '<h2 style="font-size: 18px;">' . esc_html( get_bloginfo( 'name' ) ) . ' - This Room is Restricted to Signed in Users Only </h2>';
				} else {
					echo '<h2 style="font-size: 18px;">' . esc_html( get_bloginfo( 'name' ) ) . ' - This Room is Offline </h2>';
				}
				?>


						<img class="cc-user-image" src="
									<?php echo get_site_url() . '/wp-content/plugins/myvideoroom-extras/noentry.jpg'; ?>" alt="Site Logo">
					</th>
				</tr>
			</table>
			<?php
			if ( 'anonymous' === $input_type ) {
					echo '<p style="font-size: 18px;">' . esc_html( get_bloginfo( 'name' ) ) . ' require all meeting rooms to be signed in users, please register and try again. </p>';
			} else {
				echo '<p style="font-size: 18px;">The administrators have disabled this room. Please contact the site owner for more assistance. </p>';
			}
			?>

		</div>
		<?php

		return ' ';
	}


	/**Blocked By User Template.
	 */


	public function room_blocked_by_user( $user_id = null, $room_type = null ) {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}

		?>
	<link rel="stylesheet" href="<?php echo plugins_url( 'profile.css', __FILE__ ); ?>">

		<div class="cc-row">

			<table class="cc-table" >
				<tr>
					<th style="width:50%">
					<img class="cc-user-image" src="
					<?php
											$url = Factory::get_instance( PageFilters::class )->get_picture_template( $user_id );
											echo esc_url( $url );
					?>
					" alt="Image">
					</th>
					<th>
						<h2 class="cc-reception-header"><?php echo esc_html( esc_html( get_bloginfo( 'name' ) ) ); ?>- This Room is Offline</h2>

						<img class="cc-access-image" src="
									<?php echo get_site_url() . '/wp-content/plugins/myvideoroom-extras/noentry.jpg'; ?>" alt="Site Logo">
					</th>
					<p class="cc-header-text">
				<?php

				$new_user   = get_userdata( $user_id );
				$first_name = $new_user->user_firstname;
				$nicename   = $new_user->user_nicename;
				if ( $first_name ) {
					echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
				has disabled this room. Please contact the site owner, or 
				<?php
				if ( $first_name ) {
					echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
				for more assistance. </p>
			</table>

		</div>
		<?php

		return ' ';
	}






	public function anonymous_blocked_by_user( $user_id = null, $room_type = null ) {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}

		?>
		<link rel="stylesheet" href="<?php echo plugins_url( 'profile.css', __FILE__ ); ?>">

<div class="cc-row">

	<table class="cc-table" >
		<tr>
			<th style="width:50%">
			<img class="cc-user-image" src="
			<?php
									$url = Factory::get_instance( PageFilters::class )->get_picture_template( $user_id );
									echo esc_url( $url );
			?>
			" alt="Image">
			</th>
			<th>
				<h2 class="cc-reception-header"><?php echo esc_html( esc_html( get_bloginfo( 'name' ) ) ); ?>- This room is set to Signed in Users Only</h2>

				<img class="cc-access-image" src="
							<?php echo get_site_url() . '/wp-content/plugins/myvideoroom-extras/noentry.jpg'; ?>" alt="Site Logo">
			</th>
			<p class="cc-header-text">
		<?php

		$new_user   = get_userdata( $user_id );
		$first_name = $new_user->user_firstname;
		$nicename   = $new_user->user_nicename;
		if ( $first_name ) {
			echo esc_html( $first_name );
		} else {
			echo esc_html( $nicename );
		}
		?>
		only allows signed in/registered users to access their video room. To be able to access this room, you must have an account on this site. Please Register for access or ask  
		<?php
		if ( $first_name ) {
			echo esc_html( $first_name );
		} else {
			echo esc_html( $nicename );
		}
		?>
		for assistance.
	</table>

</div>
		<?php

		return ' ';
	}


	public function blocked_by_role_template( $user_id = null, $room_type = null ) {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}

		?>
		<link rel="stylesheet" href="<?php echo plugins_url( 'profile.css', __FILE__ ); ?>">
		<div class="cc-row">
		



			<table style="width:100%">
				<tr>
					<th style="width:50%">
					<img class="cc-user-image" src="
					<?php
											$url = Factory::get_instance( PageFilters::class )->get_picture_template( $user_id );
											echo esc_url( $url );
					?>
					" alt="Image">

					</th>
					<th>
						<h2 class="cc-reception-header"><?php echo esc_html( get_bloginfo( 'name' ) ); ?>- This room is set to Specific Roles Only</h2>

						<img class="cc-user-image" src="
										<?php echo get_site_url() . '/wp-content/plugins/myvideoroom-extras/noentry.jpg'; ?>" alt="Site Logo">
					</th>
				</tr>
			</table>
			<p class="cc-header-text">
				<?php

				$new_user   = get_userdata( $user_id );
				$first_name = $new_user->user_firstname;
				$nicename   = $new_user->user_nicename;
				if ( $first_name ) {
					echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
				has enabled this room only for specific roles of users. You are not in a group that 
				<?php
				if ( $first_name ) {
																										echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
																									has allowed. <br>
				Please contact the site owner or 
				<?php
				if ( $first_name ) {
														echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
													for more assistance. </p>
		</div>
		<?php

		return ' ';
	}


	public function blocked_by_group_membership( $user_id = null, $room_type = null ) {
		ob_start();
		if ( Factory::get_instance( SiteDefaults::class )->is_mvr() ) {
		}
		?>

 <link rel="stylesheet" href="<?php echo plugins_url( 'profile.css', __FILE__ ); ?>">

		<div class="cc-row">

			<table class="cc-table" >
				<tr>
					<th style="width:50%">
					<img class="cc-user-image" src="
					<?php
											$url = Factory::get_instance( PageFilters::class )->get_picture_template( $user_id );
											echo esc_url( $url );
					?>
					" alt="Image">
					</th>
					<th>
						<h2 class="cc-reception-header"><?php echo esc_html( esc_html( get_bloginfo( 'name' ) ) ); ?>- This room is set to Group Members Only</h2>

						<img class="cc-access-image" src="
									<?php echo get_site_url() . '/wp-content/plugins/myvideoroom-extras/noentry.jpg'; ?>" alt="Site Logo">
					</th>
					</table>
					<p class="cc-header-text">
				<?php

				$new_user   = get_userdata( $user_id );
				$first_name = $new_user->user_firstname;
				$nicename   = $new_user->user_nicename;
				if ( $first_name ) {
					echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
				or one of the moderators have enabled this room only for specific membership of the group. You are not in a class of user that 
				<?php
				if ( $first_name ) {
					echo esc_html( $first_name );
				} else {
					echo esc_html( $nicename );
				}
				?>
				or the group moderators have allowed. <br> Please contact any of the group admins or moderators for assistance.

		</div>
		<?php

		return ' ';
	}


} // end class
