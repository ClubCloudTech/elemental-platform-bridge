<?php
/**
 * Search Member Search.
 *
 * @package search/views/membersearch/member-search.php
 */

/**
 * Search Member Search.
 *
 * @param string $main - the header of the template.
 * @param array  $tabs -Inbound object with tabs.
 * @param object $premium_display - premium shortcode.
 *
 * @return string
 */


return function (
	string $main_display,
	string $tab_name,
	string $premium_display = null
): string {
	ob_start();
	?>
<div class="elemental-membersearch-outer-wrap">
	<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">
		<?php
		if ( $premium_display ) {
			?>
		<div id="elemental-premium-wcfm" class="elemental-premium-orgs">
			<h1 class="elemental-login-button"><?php \esc_html_e( 'Elite Partnerships', 'elemental ' ); ?></h1>
			<?php
				//phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped - output is an escaped shortcode.
				echo $premium_display;
			?>
		</div>
			<?php
		}
		?>
		<div class="elemental-main-orgs">
			<h1 class="elemental-login-button"><?php \esc_html_e( 'Platform Users', 'elemental ' ); ?></h1>
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
