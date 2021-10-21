<?php
/**
 * Search Display Shortcode
 * This page appears from all non simple shortcode calls.
 *
 * @package ElementalPlugin/wcfm/views/maintemplate.php
 */

use ElementalPlugin\Factory;
use ElementalPlugin\WCFM\WCFMTools;
use MyVideoRoomPlugin\Module\WooCommerce\WooCommerce;

/**
 * Render the Main Template
 *
 * @param string $header - the header of the template.
 * @param array  $tabs -Inbound object with tabs.
 * @param object $html_library - randomizing object class.
 *
 * @return string
 */


return function (
	string $header,
	object $html_library,
	array $tabs
): string {
	ob_start();
	?>
<div class="mvr-nav-shortcode-outer-wrap" style="max-width: 1250px;">

	<div class="mvr-header-section">
	<?php
		// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
		echo $header;

	?>

	</div>
	<div id="mvr-notification-master" class="mvr-nav-shortcode-outer-wrap-clean mvr-notification-master">
		<?php
			$output = \apply_filters( 'myvideoroom_notification_master', '', $room_name );
			// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
			echo $output;
		?>
		<div id="mvr-postbutton-notification" class="mvr-notification-align"></div>
	</div>

	<nav class="myvideoroom-nav-tab-wrapper nav-tab-wrapper elemental-side-tab">
		<ul class="mvr-ul-style-side-menu">
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

	//echo var_dump( Factory::get_instance( WCFMTools::class )->elemental_get_store_memberships( 134 ) );



			$count = 0;
	foreach ( $tabs as $article_output ) {

		$function_callback = $article_output->get_function_callback();
		$tab_slug          = $article_output->get_tab_slug();
		?>
	<article id="<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>"
		class="elemental-content-tab mvr-article-separation">
		<?php

		if ( WooCommerce::SETTING_SHOPPING_BASKET !== $tab_slug ) {
			// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
				echo $function_callback;
		}

		?>
	</article>
		<?php
		if ( WooCommerce::SETTING_SHOPPING_BASKET === $tab_slug ) {
			?>
	<article id="<?php echo \esc_textarea( WooCommerce::SETTING_SHOPPING_BASKET ); ?>" class="mvr-article-separation">
			<?php
				// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - callback escaped within itself.
				echo $function_callback; 
			?>

	</article>
			<?php
		}
	}
	?>
</div>
	
<?php
	

			return \ob_get_clean();
};
