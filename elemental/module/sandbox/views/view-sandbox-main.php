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
	string $user_id
): string {
	$count_tabs = count( $tabs );
	ob_start();
	?>
<div id="elemental-sandbox-base" class="elemental-sandbox-wrap" data-user=<?php echo esc_attr( $user_id ); ?>>

<div id ="header-div-info" class="elemental-sandbox-info-header">
<div class="elemental-outer-header-wrapper">		
<table class="elemental-table-info-header">

			<?php

			foreach ( $tabs as $header_output ) {

				$employee_name  = ucwords( $header_output->get_sandbox_object()->get_employee_name() );
				$company_domain = $header_output->get_sandbox_object()->get_company_domain();
				$record_id      = $header_output->get_sandbox_object()->get_record_id();
				$user           = \wp_get_current_user();
				if ($record_id === 4) { // TODO: should be based on company type, hard-coded for lender as sandbox id = 4
					$email     = $user->user_email;
					$full_name = $user->first_name . ' ' . $user->last_name;
				} else {
					$email     = strtolower( $employee_name . '.' . $user->last_name) . $user->id . '@' . $company_domain;
					$full_name = $employee_name . ' ' . $user->last_name;
				}
				?>
			<th class="elemental-table-header-info">
				<?php echo $full_name.'<br>'. $email ;
				?>
			</th>

			<?php
			}

			?>
		</table>
	</div>
		<?php
			if ( $count_tabs > 1 ) {
				?>
			<nav id="tabs" class="elemental-nav-tab-wrapper elemental-sandbox-nav-tab-wrapper">
				<ul class="sandbox-menu-header">
					<?php
							$active = ' nav-tab-active';
					foreach ( $tabs as $menu_output ) {
						$tab_display_name = $menu_output->get_tab_display_name();
						$tab_slug         = $menu_output->get_tab_slug();
						$object_id        = $menu_output->get_element_id();
						//echo var_dump( $menu_output->get_sandbox_object()->get_destination_url());
						?>

					<li data-elementid=<?php echo esc_attr( $object_id ); ?>>
					<a class="nav-tab<?php echo esc_attr( $active ); ?>" 
													<?php
													if ( $object_id ) {
														echo 'id = "' . esc_attr( $object_id ) . '" ';
													}
													?>
													href="#<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>">
							<?php
							//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon is created by escaped function.
							echo $tab_display_name;
							?>

						</a>
					</li>

						<?php
						$active = null;
					}
					?>
				</ul>
			<?php } ?>

	<div id="mvr-above-article-notification"></div>
	<div id="elemental-container-article" class="elemental-article elemental-article-container elemental-background-item">
			<?php
			foreach ( $tabs as $article_output ) {

				$function_callback = $article_output->get_function_callback();
				$tab_slug          = $article_output->get_tab_slug();
				?>
			<article id="<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>"
				class="mvr-article-separation">
				<?php
					// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
						echo $function_callback;
				?>
			</article>

				<?php
			}
			?>
			</nav>
		</div><!--end out
	</div><!-- elemental-container-article -->
</div><!-- #elemental-search-base -->

	<?php

			return \ob_get_clean();
};
