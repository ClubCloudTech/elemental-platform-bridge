<?php
/**
 * Outputs the Tabbed Shortcode View Settings.
 *
 * @package ElementalPlugin\Views\Shortcode\shortcode-tab.php
 */

declare( strict_types=1 );

namespace ElementalPlugin;

use ElementalPlugin\Factory;
use MyVideoRoomPlugin\Library\HTML;

/**
 * Render the Default Settings Admin page
 *
 * @param array $inbound_tabs A list of WordPress roles. @see global $wp_roles->roles.
 */
return function (
	array $inbound_tabs
): string {
	\ob_start();
	$string_randomizer_input = 'elementaltab';
	$html_library            = Factory::get_instance( HTML::class, array( $string_randomizer_input ) );
	$tab_count               = count( $inbound_tabs );

	?>
	<div class="mvr-tab-wrap">
	<?php
	if ( $tab_count >= 1 ) {
		?>
		<h2><?php esc_html_e( 'MyVideoRoom Default Settings', 'my-video-room' ); ?></h2>
		<p>
			<?php esc_html_e( 'This section allows you manage the default room appearance as well as permissions, guest/host decisions, and room security settings across all of your rooms.', 'myvideoroom' ); ?>
		</p>
		<nav class="myvideoroom-nav-tab-wrapper nav-tab-wrapper">
			<ul class="mvr-ul-style-top-menu">
				<?php
				$active = 'nav-tab-active';
				foreach ( $inbound_tabs as $menu_output ) {
					$tab_display_name = $menu_output->get_tab_display_name();
					$tab_slug         = $menu_output->get_tab_slug();
					?>

					<li>
						<a class="nav-tab <?php echo \esc_attr( $active ); ?>" href="#<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>">
						<?php echo esc_html( $tab_display_name ); ?>
					</a>
					</li>
					<?php
					$active = null;
				}
				?>
			</ul>
		</nav>
		<?php
	}
	?>

	<?php
	foreach ( $inbound_tabs as $article_output ) {

		$tab_slug = $article_output->get_tab_slug();
		?>
		<article id="<?php echo esc_attr( $html_library->get_id( $tab_slug ) ); ?>" class="">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $article_output->get_function_callback();
			?>
		</article>
		<?php
	}
	?>
	</div>
	<?php
	return \ob_get_clean();
};
