<?php
/**
 * Handling Forum Search.
 *
 * @package elemental/search/library/class-forumsearch.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

namespace ElementalPlugin\Module\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Handling Organisation Search.
 */
class ForumSearch {

	const SEARCH_FORUM_TAB = 'elemental-forum-tab';
		/**
		 * Forum Search
		 */

		/**
		 * Render Forum Search.
		 *
		 * @param array $input   - the inbound menu.
		 * @return array
		 */
	public function render_forum_tabs( array $input = null ) :array {

		$admin_menu = new MenuTabDisplay(
			\esc_html__( 'Expert Forums', 'elemental' ),
			'forums',
			fn() => $this->render_forums(),
			'elemental-forum-result'
		);
		\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Render Forums from BBPress. Initial Page Render Template.
	 *
	 * @param string $search_term -Whether to search on a given term.
	 * @return array
	 */
	private function render_forums( string $search_term = null ) :string {
		$tab_name = self::SEARCH_FORUM_TAB;
		$page_num = Factory::get_instance( Ajax::class )->get_string_parameter( 'page' );
		$base_url = Factory::get_instance( Ajax::class )->get_string_parameter( 'base' );

		if ( $page_num ) {
			$pagedinfo = 'paged = ' . $page_num . ' ';
		}
		if ( $base_url ) {
			$baseinfo = 'baseurl = ' . $base_url . ' ';
		}

		if ( $search_term || $page_num ) {
			// TODO - finish page num.
			$main_display = \do_shortcode( '[elemental_show_forums search=' . $search_term . ']' );
		} else {
			$main_display = \do_shortcode( '[bbp-forum-index]' );
		}

		$render = include __DIR__ . '/../views/forumsearch/forum-search.php';
		return $render( $main_display, $tab_name );
	}

	/**
	 * Ajax Handler for Forum Search Response.
	 *
	 * @param array  $response - the inbound response object.
	 * @param string $search_term - the term searched for.
	 * @return array
	 */
	public function forum_search_response( array $response, string $search_term ): array {
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_FORUM_TAB );
		$refresh_tabs = Factory::get_instance( Ajax::class )->get_string_parameter( 'refresh_tabs' );
		if ( 'refresh_tabs' === $refresh_tabs ) {
			$screen            = $this->render_forums();
			$response['forum'] = $screen;
		}

		if ( self::SEARCH_FORUM_TAB === $action_taken ) {
			$response['forum'] = \do_shortcode( '[elemental_show_forums search=' . $search_term . ']' );
		}
		$response['forumtarget'] = self::SEARCH_FORUM_TAB;
		return $response;
	}

	/**
	 * Display the contents of search results in an output buffer and return to
	 * ensure that post/page contents are displayed first.
	 *
	 * @since 2.3.0 bbPress (r4579)
	 *
	 * @param string $search - the search term.
	 */
	public function elemental_display_search( $atts = array() ) {

		// Trim search attribute if it's set.
		if ( isset( $atts['search'] ) ) {
			$search = trim( $atts['search'] );
		}

		// Set passed attribute to $search_terms for clarity.
		$search_terms = empty( $search )
			? bbp_get_search_terms()
			: $search;

		// Get the rewrite ID (one time, to avoid repeated calls).
		$rewrite_id = bbp_get_search_rewrite_id();

		// Unset globals.
		$this->unset_globals();
		// Set terms for query.
		set_query_var( $rewrite_id, $search_terms );

		// Set query name.
		bbp_set_query_name( $rewrite_id );

		// Start output buffer.
		ob_start();

		// phpcs:ignore-WordPress.Security.EscapeOutput.OutputNotEscaped -- all text is escaped properly.
		echo "<div class='elemental-forum-directory-list-shortcode youzify-members-directory-shortcode youzify-directory-shortcode'>";
		include __DIR__ . '/../views/forumsearch/content-search.php';
		echo '</div>';

		$this->unset_globals();

		// Get the query name, for filter.
		$query_name = bbp_get_query_name();

		// Reset the query name.
		bbp_reset_query_name();

		// Return and flush the output buffer
		$output = ob_get_clean();

		// Filter & return
		return apply_filters( 'bbp_display_shortcode', $output, $query_name );
	}

		/** Output Buffers ********************************************************/

	/**
	 * Start an output buffer.
	 *
	 * This is used to put the contents of the shortcode into a variable rather
	 * than outputting the HTML at run-time. This allows shortcodes to appear
	 * in the correct location in the_content() instead of when it's created.
	 *
	 * @since 2.0.0 bbPress (r3079)
	 *
	 * @param string $query_name - the search string.
	 */
	private function start( $query_name = '' ) {

		// Set query name.
		bbp_set_query_name( $query_name );

		// Start output buffer.
		ob_start();
	}

	/**
	 * Return the contents of the output buffer and flush its contents.
	 *
	 * @since 2.0.0 bbPress (r3079)
	 *
	 * @return string Contents of output buffer.
	 */
	private function end(): string {

		// Unset globals.
		$this->unset_globals();

		// Get the query name, for filter.
		$query_name = bbp_get_query_name();

		// Reset the query name.
		bbp_reset_query_name();

		// Return and flush the output buffer
		$output = ob_get_clean();
		// Filter & return
		return apply_filters( 'bbp_display_shortcode', $output, $query_name );
	}

	/**
	 * Unset some globals in the $bbp object that hold query related info
	 *
	 * @since 2.0.0 bbPress (r3034)
	 */
	private function unset_globals() {
		$bbp = bbpress();

		// Unset global queries.
		$bbp->forum_query  = new \WP_Query();
		$bbp->topic_query  = new \WP_Query();
		$bbp->reply_query  = new \WP_Query();
		$bbp->search_query = new \WP_Query();

		// Unset global ID's.
		$bbp->current_view_id      = 0;
		$bbp->current_forum_id     = 0;
		$bbp->current_topic_id     = 0;
		$bbp->current_reply_id     = 0;
		$bbp->current_topic_tag_id = 0;

		// Reset the post data.
		wp_reset_postdata();
	}

}
