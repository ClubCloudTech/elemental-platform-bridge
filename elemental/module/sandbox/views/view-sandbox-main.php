<?php
/**
 * Sandbox Main View
 * This page appears for all Main Views of Sandbox.
 *
 * @package module/sandbox/views/view-sandbox-main.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Render the Main Template
 *
 * @param string $header - the header of the template.
 * @param object $html_library - randomizing object class.
 * @param array  $tabs -Inbound object with tabs.
 *
 * @return string
 */


return function (
	string $header,
	object $html_library,
	array $tabs
): string {
	$count_tabs = count( $tabs );
	if ( $count_tabs <= 1 ) {
		$display_style = 'display:none;';
	}
	ob_start();
	?>
<div id="elemental-sandbox-base" class="elemental-sandbox-wrap">


		<div class="mvr-header-section">
			<?php
				// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
				echo $header;

			?>
		</div>

			<?php
			if ( $count_tabs > 1 ) {
				?>
			<nav class="elemental-nav-tab-wrapper elemental-sandbox-nav-tab-wrapper">
				<ul class="sandbox-menu-header">
					<?php
							$active = ' nav-tab-active';
					foreach ( $tabs as $menu_output ) {
						$tab_display_name = $menu_output->get_tab_display_name();
						$tab_slug         = $menu_output->get_tab_slug();
						$object_id        = $menu_output->get_element_id();
						?>
					<li>
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
			</nav>
			<?php } ?>

	<div id="mvr-above-article-notification"></div>
	<div id="elemental-container-article" class="elemental-article elemental-article-container elemental-background-item">
			<?php
			$roles = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( true );

					$count = 0;
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
	</div><!-- elemental-container-article -->
</div><!-- #elemental-search-base -->

	<?php

			return \ob_get_clean();
};
