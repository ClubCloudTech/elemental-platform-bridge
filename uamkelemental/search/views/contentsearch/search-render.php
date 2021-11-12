<?php
/**
 * Search Display Products View.
 *
 * @package elemental/search/views/contentsearch/search-render.php
 */

/**
 * Search Display Content View.
 *
 * @param string $main_display - the Content Search Archive Template.
 *
 * @return string
 */


return function (
	string $main_display,
	string $tab_name
): string {
	ob_start();
	?>
<div class="elemental-wcfm-outer-wrap">
	<div id="content-tab">

		<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">

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
