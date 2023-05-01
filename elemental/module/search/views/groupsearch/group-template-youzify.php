<?php
/**
 * BuddyPress - Groups
 *
 * @package View search/views/groupsearch/group-template.php
 */

/**
 * Fires at the top of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_groups_page' ); ?>

		<?php
		if ( function_exists( 'youzify_option' ) ) {
			$icons_style = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );
		}
		?>

<div id="youzer">

	<div id="<?php echo esc_attr( apply_filters( 'youzify_group_template_id', 'youzify-bp' ) ); ?>" class="youzer
		<?php
		if ( function_exists( 'youzify_groups_directory_class' ) ) {
			echo esc_attr( youzify_groups_directory_class() );
		}
		?>
		">

				<div id="elemental-members-directory-list">

					<?php if ( apply_filters( 'youzify_display_groups_directory_filter', true ) ) : ?>
					<div class="youzify-mobile-nav">
						<div id="directory-show-menu" class="youzify-mobile-nav-item">
							<div class="youzify-mobile-nav-container"><i
									class="fas fa-bars"></i><a><?php esc_html_e( 'Menu', 'youzer' ); ?></a></div>
						</div>
						<div id="directory-show-search" class="youzify-mobile-nav-item">
							<div class="youzify-mobile-nav-container"><i
									class="fas fa-search"></i><a><?php esc_html_e( 'Search', 'youzer' ); ?></a></div>
						</div>
						<div id="directory-show-filter" class="youzify-mobile-nav-item">
							<div class="youzify-mobile-nav-container"><i
									class="fas fa-sliders-h"></i><a><?php esc_html_e( 'Filter', 'youzer' ); ?></a></div>
						</div>
					</div>
					<div class="elemental-directory-filter elemental-separation-header elemental-block-separation">

						<div class="item-list-tabs"
							aria-label="<?php esc_attr_e( 'Groups directory Primary navigation', 'youzify' ); ?>"
							role="navigation">
							<ul>
								<?php /* translators: %s: search term is the number of group count */ ?>
								<li class="selected elemental-refresh-group-search-trigger" id="groups-all"><a
										href="<?php bp_groups_directory_permalink(); ?>"><?php printf( esc_html__( 'All Groups %s', 'youzer' ), '<span>' . esc_attr( bp_get_total_group_count() ) . '</span>' ); ?></a>
								</li>

								<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
								<?php	//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - Function already builds every parameter safely.  ?>
								<li id="groups-personal"><a
										href="<?php echo bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/'; ?>"><?php printf( esc_html__( 'My Groups %s', 'youzer' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a>
								</li>
								<?php endif; ?>

								<?php

								/**
								 * Fires inside the groups directory group filter input.
								 *
								 * @since 1.5.0
								 */
								do_action( 'bp_groups_directory_group_filter' );
								?>


						<?php

						/**
						 * Fires inside the groups directory group types.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_groups_directory_group_types' );
						?>

							</ul>
						</div><!-- .item-list-tabs -->
							<div class="item-list-tabs" id="subnav"
								aria-label="<?php esc_attr_e( 'Members directory secondary navigation', 'youzify' ); ?>"
								role="navigation">
								<ul>
									<?php
									global $elemental_group_loop_arguments;
									//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - Function already builds every parameter safely.
									echo $elemental_group_loop_arguments['drop_down'];
									/**
									 * Fires inside the groups directory group order options.
									 *
									 * @since 1.2.0
									 */
									do_action( 'bp_groups_directory_order_options' );
									?>
									<li id="elemental-directory-search-box">
									<input type="text" name="elemental-groupsearch" class="elemental-groupsearch elemental-input-restrict-alphanumeric-space" id="elemental-group-search" placeholder="Refine Search">
									<input type="submit" id="elemental-group-search-submit" name="group-submit" value="Search">
									</li>
								</ul>
							</div><!-- .item-list-tabs subnav -->
							<div class="elemental-clear"></div>
						</div>
					</div>

					<?php endif; ?>
					<div class="elemental-clear elemental-separation-header"></div>
					<form action="" method="post" id="groups-directory-form" class="dir-form">

						<div id="template-notices" role="alert" aria-atomic="true">
							<?php

							/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
							do_action( 'template_notices' );
							?>

						</div>

						<div id="groups-dir-list" class="groups dir-list">
							<?php bp_get_template_part( 'groups/groups-loop' ); ?>
						</div><!-- #groups-dir-list -->

						<?php

						/**
						 * Fires and displays the group content.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_groups_content' );
						?>
						<div class="elemental-clear"></div>
						<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

						<?php

						/**
						 * Fires after the display of the groups content.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_after_directory_groups_content' );
						?>

					</form><!-- #groups-directory-form -->
					<?php

					/**
					 * Fires after the display of the groups.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_directory_groups' );
					?>

				</div><!-- #elemental-members-directory-list -->

	</div><!-- Outer div -->

</div> <!-- YZ div -->

<?php

/**
 * Fires at the bottom of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_groups_page' );
