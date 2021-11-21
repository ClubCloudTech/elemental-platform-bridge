<?php

/**
 * Search Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */


?>

<div id="bbpress-forums" class="bbpress-wrapper">

	<?php bbp_breadcrumb(); ?>

	<?php bbp_set_query_name( bbp_get_search_rewrite_id() ); ?>

	<?php do_action( 'bbp_template_before_search' ); ?>

	<?php if ( bbp_has_search_results() ) : ?>
		<div id="bbpsearchcount" style="display:none;"><?php echo esc_textarea( bbp_search_pagination_count() ); ?></div>
		<?php include __DIR__ . '/pagination-search.php'; ?>

		<?php include __DIR__ . '/loop-search.php'; ?>

		<?php include __DIR__ . '/pagination-search.php'; ?>

	<?php elseif ( bbp_get_search_terms() ) : ?>

		<?php include __DIR__ . '/feedback-no-search.php'; ?>


	<?php else : ?>

		<?php include __DIR__ . '/form-search.php'; ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_search_results' ); ?>

</div>

