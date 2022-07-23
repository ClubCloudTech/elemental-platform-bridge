<?php
/**
 * Search Forums in BBPress.
 *
 * @package elemental/search/views/forumsearch/forum-search.php
 */

/**
 * Search Forums in BBPress.
 *
 * @param string $main - the header of the template.
 * @param array  $tabs -Inbound object with tabs.
 *
 * @return string
 */


return function (
	string $main_display,
	string $tab_name
): string {
	ob_start();
	?>
<div class="elemental-forumsearch-outer-wrap elemental-header-searchbar elemental-background-item">
	<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">

		<div class="elemental-main-forum elemental-forum-search">
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
