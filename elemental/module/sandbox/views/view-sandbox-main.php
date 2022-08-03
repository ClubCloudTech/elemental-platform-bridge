<?php
/**
 * Sandbox Main View
 * This page appears for all Main Views of Sandbox.
 *
 * @package module/sandbox/views/view-sandbox-main.php
 */

use ElementalPlugin\Library\Encryption;
use ElementalPlugin\Library\Factory;

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
	</div><!-- elemental-container-article -->
</div><!-- #elemental-search-base -->

	<?php

			return \ob_get_clean();
};
