<?php
/**
 * Search Display Shortcode
 * This page appears from all non simple shortcode calls.
 *
 * @package ElementalPlugin/wcfm/views/maintemplate.php
 */

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\Library\WCFMTools;

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
	string $search_template,
	string $product_template
): string {
	ob_start();
	?>
<div id="elemental-search-base" class="wrap elemental-background-item elemental-shortcode-padding">
	<div id="elemental-pageinfo" data-searchid="<?php echo esc_attr( $search_template ); ?>"
	data-productid="<?php echo esc_attr( $product_template ); ?>"
	data-pagination="<?php echo esc_url_raw( $pagination_base ); ?>"
	></div>
	<div class="mvr-header-section">
	<?php
		// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
		echo $header;

	?>

	</div>
	<div id="mvr-notification-master" class="mvr-nav-shortcode-outer-wrap-clean mvr-notification-master">

		<div id="mvr-postbutton-notification" class="mvr-notification-align">
		<div id="notification" class="mvr-table-row ">
		<button id="elemental-refresh-search" class="mvr-main-button-enabled" style="display:none;">
		<a  class="mvr-main-button-enabled  myvideoroom-button-link"><span title="Search Again" class="myvideoroom-dashicons dashicons-search"></span><span title="Search Again" class="myvideoroom-dashicons dashicons-update-alt"></span></a>
		</button>
		<input id="elemental-search" type="text" placeholder="Search....."  class="myvideoroom-input-restrict-alphanumeric-space mvr-input-box myvideoroom-center">
		<div id="searchnotification" class="mvr-notification-align"></div>
			<button class="mvr-main-button-enabled " >
			<a data-room-name="" class="mvr-main-button-enabled elemental-search-trigger"><span title="Search" class="myvideoroom-dashicons dashicons-search elemental-search-trigger"></span></a>
			</button>
<div id="mvr-postbutton-notification" class="mvr-notification-align"></div>
</div>


		</div>
	</div>

	<nav class="myvideoroom-nav-tab-wrapper nav-tab-wrapper ">
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
	<div id="mvr-above-article-notification"></div>

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
</div>

	<?php

			return \ob_get_clean();
};
