<?php
/**
 * Search Display WCFM Window View.
 *
 * @package search/views/wcfm-orgs.php
 */

/**
 * Search Display WCFM Window View.
 *
 * @param string $header - the header of the template.
 * @param array  $tabs -Inbound object with tabs.
 * @param object $html_library - randomizing object class.
 *
 * @return string
 */


return function (
	string $premium_display = null,
	string $main_display,
	string $tab_name
): string {
	ob_start();
	?>
<div class="elemental-wcfm-outer-wrap">
	<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">
		<?php
		if ( $premium_display ) {
			?>
		<div id="elemental-premium-wcfm" class="elemental-premium-orgs">
			<h1 class="elemental-login-button"><?php \esc_html_e( 'Featured Partnerships', 'myvideoroom ' ); ?></h1>
			<?php
				//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - output is an escaped shortcode.
				echo $premium_display;
			?>
		</div>
			<?php
		}
		?>
		<div class="elemental-main-orgs">
			<h1 class="elemental-login-button"><?php \esc_html_e( 'All UAM Organisations', 'myvideoroom ' ); ?></h1>
			<?php
				//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - output is an escaped shortcode.
				echo $main_display;
			?>
		</div>
	</div>
</div>

	<?php

			return \ob_get_clean();
};
