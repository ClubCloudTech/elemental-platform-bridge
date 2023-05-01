<?php
/**
 * Search Display Shortcode
 * This page appears from all non simple shortcode calls.
 *
 * @package ElementalPlugin/wcfm/views/maintemplate.php
 */

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\WCFM\Library\WCFMTools;

/**
 * Render the Main Template
 *
 * @param string $header - the header of the template.
 * @param object $html_library - randomizing object class.
 * @param array  $tabs -Inbound object with tabs.
 * @param string $search_template - the templateID of the search template.
 * @param string $product_template - the templateID of the product template.
 *
 * @return string
 */


return function (
	string $header,
	string $pagination_base,
	object $html_library,
	array $tabs,
	string $search_template = null,
	string $product_template = null
): string {
	$count_tabs = count( $tabs );
	if ( $count_tabs <= 1 ) {
		$display_style = 'display:none;';
	}
	ob_start();
	?>
<div id="elemental-search-base" class="elemental-search-wrap">

	<div id="elemental-pageinfo" data-searchid="<?php echo esc_attr( $search_template ); ?>"
	data-productid="<?php echo esc_attr( $product_template ); ?>"
	data-pagination="<?php echo esc_url_raw( $pagination_base ); ?>"
	></div>
		<div class="elemental-header-section">
			<?php
				// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
				echo $header;

			?>
		</div>
	<div style="<?php echo esc_attr( $display_style ); ?>" class="elemental-header-searchbar elemental-background-item">	
		<div id="elemental-notification-master" class="elemental-nav-shortcode-outer-wrap-clean elemental-notification-master">

			<div id="elemental-postbutton-notification" class="elemental-notification-align">
				<div id="notification" class="elemental-search-bar">
					<button id="elemental-refresh-search" class="elemental-main-button-enabled" style="display:none;">
					<a  class="elemental-main-button-enabled  elemental-button-link"><span title="Search Again" class="elemental-dashicons dashicons-search"></span><span title="Search Again" class="elemental-dashicons dashicons-update-alt"></span></a>
					</button>
					<input id="elemental-search" type="text" placeholder="Search..... (Results will appear in Tabs Below)"  class="elemental-input-restrict-alphanumeric-space elemental-input-box elemental-center">
					<div id="searchnotification" class="elemental-notification-align"></div>
					<button class="elemental-main-button-enabled " >
					<a data-room-name="" class="elemental-main-button-enabled elemental-search-trigger"><span title="Search" class="elemental-dashicons dashicons-search elemental-search-trigger"></span></a>
					</button>
					<div id="elemental-postbutton-notification" class="elemental-notification-align"></div>
				</div>
			</div>
		</div>
			<div><h2 class="elemental-align-centre"><?php esc_html_e( 'Platform Categories', 'elemental' ); ?></h2></div>
			<?php
			if ( $count_tabs > 1 ) {
				?>
			<nav class="elemental-nav-tab-wrapper elemental-search-nav-tab-wrapper">
				<ul class="search-menu-header">
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

	<div id="elemental-above-article-notification"></div>
	<div id="elemental-container-article" class="elemental-article-container elemental-background-item">
			<?php
			$roles = Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( true );

					$count = 0;
			foreach ( $tabs as $article_output ) {

				$function_callback = $article_output->get_function_callback();
				$tab_slug          = $article_output->get_tab_slug();
				?>
			<article id="<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>"
				class="elemental-article-separation">
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
