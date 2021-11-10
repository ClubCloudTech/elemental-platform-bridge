<?php
/**
 * Search Display Products View.
 *
 * @package search/views/products-initial-render.php
 */

/**
 * Search Display WCFM Window View.
 *
 * @param string $main_display - the Products Archive Template.
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
	<div id="products-tab">

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
