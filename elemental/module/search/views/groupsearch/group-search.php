<?php
/**
 * Search Group Search.
 *
 * @package search/views/groupsearch/group-search.php
 */

/**
 * Search Group Search.
 *
 * @param string $main - the header of the template.
 * @param array  $tabs -Inbound object with tabs.
 * @param object $premium_display - premium shortcode.
 *
 * @return string
 */


return function (
	string $main_display,
	string $tab_name
): string {
	ob_start();
	?>
<div class="elemental-groupsearch-outer-wrap">
	<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">

		<div class="elemental-main-orgs">
			<h1 class="elemental-login-button"><?php \esc_html_e( 'Groups and Communities', 'myvideoroom ' ); ?></h1>
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
