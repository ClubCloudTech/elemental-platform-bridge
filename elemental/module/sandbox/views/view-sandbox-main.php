<?php
/**
 * Sandbox Main View
 * This page appears for all Main Views of Sandbox.
 *
 * @package module/sandbox/views/view-sandbox-main.php
 */

/**
 * Render the Main Template
 *
 * @param string $header - the header of the template.
 * @param object $html_library - randomizing object class.
 * @param array  $tabs -Inbound object with tabs.
 * @param string $user_id - the encoded userid to use.
 *
 * @return string
 */

return function (
	object $html_library,
	array $tabs,
	string $user_id,
	string $site_url
): string {
	$count_tabs = count( $tabs );
	ob_start();
	?>
<div id="elemental-sandbox-base" class="elemental-sandbox-wrap" data-user=<?php echo esc_attr( $user_id ); ?>>

	<div id="header-div-info" class="elemental-sandbox-info-header">
		<?php
		if ( $count_tabs > 1 ) {
			?>
		<nav id="tabs" class="elemental-nav-tab-wrapper elemental-sandbox-nav-tab-wrapper">
		<div class="elemental-background-item">
			<div class="elemental-spacer"></div>
			<div class="elemental-outer-header-wrapper">
				<table class="elemental-table-info-header">

					<?php
					$first_run = true;

					foreach ( $tabs as $header_output ) {
						if ( 'Info' === $header_output->get_sandbox_object()->get_tab_name() ) continue;
						$employee_name  = ucwords( $header_output->get_sandbox_object()->get_employee_name() );
						$company_domain = $header_output->get_sandbox_object()->get_company_domain();
						$record_id      = $header_output->get_sandbox_object()->get_record_id();
						$object_id      = $header_output->get_element_id();
						$user           = \wp_get_current_user();
						$color_id       = $header_output->get_sandbox_object()->get_customfield2();

						if ( true === $first_run ) {
							$color_output = 'style=color:' . esc_textarea( $color_id ) . ';';
							$first_run    = false;
						} else {
							$color_output = 'style=color:#323064;';
						}

						if ( 4 === $record_id ) { // TODO: should be based on company type, hard-coded for lender as sandbox id = 4.
							$email     = $user->user_email;
							$full_name = $user->first_name . ' ' . $user->last_name;
						} else {
							$email     = strtolower( $employee_name . '.' . $user->last_name ) . $user->id . '@' . $company_domain;
							$full_name = $employee_name . ' ' . $user->last_name;
						}
						?>
						<th class="elemental-table-header-info"
						id="name_label_<?php echo esc_textarea( $object_id ); ?>" 
						data-color-id="<?php echo esc_textarea( $color_id ); ?>"
						data-object-id="<?php echo esc_textarea( $object_id ); ?>"
						<?php echo esc_attr( $color_output ); ?>
						>
							<?php echo '<strong>' . esc_textarea( $full_name ) . '</strong> <br>' . esc_textarea( $email ); ?>
						</th>

						<?php
						$color_output = null;
					}

					?>
				</table>
				<ul class="sandbox-menu-header">
					<?php
						$active    = ' nav-tab-active';
						$first_run = true;

					foreach ( $tabs as $menu_output ) {
						$tab_display_name = $menu_output->get_tab_display_name();
						$tab_slug         = $menu_output->get_tab_slug();
						$class            = $menu_output->get_sandbox_object()->get_class();
						$object_id        = $menu_output->get_element_id();
						$retrieved_color  = $menu_output->get_sandbox_object()->get_customfield2();
						if ( true === $first_run ) {
							$color_output = 'style=background-color:' . esc_textarea( $retrieved_color ) . ';';
							$first_run    = false;
						}
						?>

					<li data-elementid=<?php echo esc_attr( $object_id ); ?> data-color=<?php echo esc_textarea( $retrieved_color ); ?>>
						<a class="<?php echo esc_attr( $class ); ?> nav-tab<?php echo esc_attr( $active ); ?>"
						<?php echo esc_attr( $color_output ); ?> 
						<?php
						if ( $object_id ) {
							echo 'id = "obj_' . esc_attr( $object_id ) . '" ';
						}
						?>
						data-color=<?php echo esc_textarea( $retrieved_color ); ?>
						data-object-id="<?php echo esc_textarea( $object_id ); ?>"
						href="#<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>">
							<?php
								//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon is created by escaped function.
									if ( 'Info' == $tab_display_name ) {
										$icon = include __DIR__ . '/view-sandbox-info-icon.php';
										echo $icon();
									} else {
										echo $tab_display_name;
									}
							?>

						</a>
					</li>

						<?php
							$active = null;
							$color_output = null;
					}
					?>
				</ul><!--sandbox header UL-->
				</div><!-- elemental-outer-header-wrapper -->
			</div><!-- elemental-outer-background-wrapper -->

			<?php } ?>

			<div id="mvr-above-article-notification"></div>
			<div id="elemental-container-article"
				class="elemental-article elemental-article-container">
				<?php
				foreach ( $tabs as $article_output ) {

					$function_callback = $article_output->get_function_callback();
					$tab_slug          = $article_output->get_tab_slug();
					?>
				<article id="<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>"
					class="">
					<?php
					// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
						echo $function_callback;
					?>
				</article>

					<?php
				}
				?>
			</div><!-- elemental-container-article -->
		</nav>
</div><!-- #elemental-sandbox-base -->
<div id="loading">
<div class="elemental-loading">
	<div class="elemental-image-container elemental-lightbox-image">
		<img class="elemental-lightbox-image" src="<?php echo esc_url( $site_url ); ?>">
	</div>
			<h1 style="display:inline"><?php echo esc_html__( 'Loading Your Sandbox ', 'elementalplugin' ); ?></h1></div>	

</div>
	<?php

			return \ob_get_clean();
};
