<?php
/**
 * Product Search Display Loop for View.
 *
 * @package search/views/productsearch/product-search-render.php
 */

/**
 * Product Search Display Products View.
 *
 * @param string $main_display - the Content Search Archive Template.
 *
 * @return string
 */


return function (
	WP_Query $query,
	string $tab_name,
	int $page = null

): string {
	ob_start();
	$total_pages = $query->max_num_pages;
	?>
<div class="elemental-wcfm-outer-wrap">
	<div id="content-tab">

		<div id="<?php echo esc_attr( $tab_name ); ?>" class="elemental-label-trigger">

			<div class="woocommerce">
						<?php
						if ( $total_pages > 1 || $page >= 1 ) {
							?>
					<nav class="woocommerce-pagination">
						<ul class="page-numbers" data-target="products">
							<?php
							$total_pages = $query->max_num_pages;

								$current_page = max( 1, $page );
								// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped -- core WP function doesn't need escaping.
								echo paginate_links(
									array(
										'base'      => get_pagenum_link( 1 ) . '%_%',
										'format'    => '/prodpage/%#%',
										'mid_size'  => 5,
										'current'   => $current_page,
										'total'     => $total_pages,
										'prev_text' => __( '« Previous' ),
										'next_text' => __( 'Next »' ),
									)
								);
							?>
						</ul>
					</nav>
							<?php
						}
						?>
				<ul class="products elementor-grid oceanwp-row clr grid tablet-col tablet-3-col"
					style="display: initial;">

					<?php if ( $query->have_posts() ) { ?>
					<div class="products container grid-wrapper clear">
						<div id="elemental-product-grid" class="row">
							<?php
							while ( $query->have_posts() ) {
								$query->the_post();
								wc_get_template_part( 'content', 'product' );
							}
							?>
						</div>

						<?php wp_reset_postdata(); ?>
						<?php
					}
					?>
				</ul>
			</div>
					<?php
					if ( $total_pages > 1 || $page >= 1 ) {
						?>
					<nav class="woocommerce-pagination">
						<ul class="page-numbers" data-target="products">
						<?php
						$total_pages = $query->max_num_pages;

							$current_page = max( 1, $page );
							// phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped -- core WP function doesn't need escaping.
							echo paginate_links(
								array(
									'base'      => get_pagenum_link( 1 ) . '%_%',
									'format'    => '/prodpage/%#%',
									'mid_size'  => 5,
									'current'   => $current_page,
									'total'     => $total_pages,
									'prev_text' => __( '« Previous' ),
									'next_text' => __( 'Next »' ),
								)
							);
						?>
						</ul>
					</nav>
						<?php
					}
					?>

			</div>
		</div>
	</div>
</div>

	<?php
			return \ob_get_clean();
};
