<?php

$roles = \get_user_roles( $store_id );
if ( in_array( 'wcfm_vendor', $roles ) ) {
	echo 'bump';
}
/**
 * BuddyPress - Members
 */
/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_members_page' ); ?>
<div id="youzify">

<div id="<?php echo apply_filters( 'youzify_members_template_id', 'youzify-bp' ); ?>" class="youzify
<?php
if ( function_exists( 'youzify_members_directory_class' ) ) {
	echo esc_attr( \youzify_members_directory_class() );
}
?>
">

	<main class="elemental-page-main-content">

		<div id="elemental-members-directory-list">

			<?php

			/**
			 * Fires before the display of the members.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_directory_members' );
			?>

			<?php

			/**
			 * Fires before the display of the members content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_directory_members_content' );
			?>

			<?php
			/**
			 * Fires before the display of the members list tabs.
			 *
			 * @since 1.8.0
			 */
			do_action( 'bp_before_directory_members_tabs' );
			?>

			<?php if ( function_exists( 'youzify_display_md_filter_bar' ) && youzify_display_md_filter_bar() ) : ?>

			<div class="youzify-mobile-nav">
				<div id="directory-show-menu" class="youzify-mobile-nav-item"><div class="elemental-mobile-nav-container"><i class="fas fa-bars"></i><a><?php _e( 'Menu', 'youzify' ); ?></a></div></div>
				<div id="directory-show-search" class="youzify-mobile-nav-item"><div class="elemental-mobile-nav-container"><i class="fas fa-search"></i><a><?php _e( 'Search', 'youzify' ); ?></a></div></div>
				<div id="directory-show-filter" class="youzify-mobile-nav-item"><div class="elemental-mobile-nav-container"><i class="fas fa-sliders-h"></i><a><?php _e( 'Filter', 'youzify' ); ?></a></div></div>
			</div>

			<div class="elemental-directory-filter elemental-separation-header elemental-block-separation">
				<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Members directory main navigation', 'youzify' ); ?>" role="navigation">
					<ul>
						<li class="selected elemental-refresh-member-search-trigger" id="members-all"><a href="<?php bp_members_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'youzify' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

						<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
							<li id="members-personal"><a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ); ?>"><?php printf( __( 'My Friends %s', 'youzify' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
						<?php endif; ?>

						<?php

						/**
						 * Fires inside the members directory member types.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_members_directory_member_types' );
						?>

					</ul>
				</div><!-- .item-list-tabs -->

				<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Members directory secondary navigation', 'youzify' ); ?>" role="navigation">
				<ul>
						<?php

						/**
						 * Fires inside the members directory member sub-types.
						 *
						 * @since 1.5.0
						 */
						do_action( 'bp_members_directory_member_sub_types' );
						global $elemental_members_loop_arguments;
							echo $elemental_members_loop_arguments['drop_down'];
						?>


						<?php if ( apply_filters( 'yz_display_members_directory_search_bar', true ) ) : ?>
						<li id="elemental-directory-search-box">
							<div id="members-dir-search" class="dir-search" role="search">
								<?php bp_directory_members_search_form(); ?>
							</div><!-- #members-dir-search -->
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<?php endif; ?>
			<div class="elemental-clear elemental-separation-header"></div>
			<form action="" method="post" id="members-directory-form" class="dir-form">

				<div id="members-dir-list" class="members dir-list">
					<?php bp_get_template_part( 'members/members-loop' ); ?>
				</div><!-- #members-dir-list -->

				<?php

				/**
				 * Fires and displays the members content.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_content' );
				?>

				<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

				<?php

				/**
				 * Fires after the display of the members content.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_directory_members_content' );
				?>

			</form><!-- #members-directory-form -->

			<?php

			/**
			 * Fires after the display of the members.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_directory_members' );
			?>

		</div><!-- #buddypress -->

	</main>

</div>

</div>

<?php

/**
 * Fires at the bottom of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_members_page' );
