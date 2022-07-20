<?php
/**
 * Handling Membersearch.
 *
 * @package search/library/class-membersearch.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.

namespace ElementalPlugin\Search\Library;

use ElementalPlugin\Entity\MenuTabDisplay;
use ElementalPlugin\Factory;
use ElementalPlugin\Library\Version;
use ElementalPlugin\Library\Ajax;

/**
 * Member Search Functions
 */
class MemberSearch {

	const SEARCH_MEMBER_TAB = 'elemental-member-tab';

	/**
	 * Render Membership Search Tabs.
	 *
	 * @param array $input   - the inbound menu.
	 * @return array
	 */
	public function render_members_tabs( array $input = null ) :array {

		$admin_menu = new MenuTabDisplay(
			\esc_html__( 'Platform Users', 'myvideoroom' ),
			'members',
			fn() => $this->render_member_search(),
			'elemental-member-result'
		);
		\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Render Member Search. Initial Page Render Template.
	 *
	 * @param string $search_term -Whether to search on a given term.
	 * @param string $search_type -What Filter By Field.
	 * @return array
	 */
	private function render_member_search( string $search_term = null, string $search_type = null ) :string {
		$tab_name = self::SEARCH_MEMBER_TAB;
		$page_num = Factory::get_instance( Ajax::class )->get_string_parameter( 'page' );

		if ( $page_num ) {
			$pagedinfo = 'page= ' . $page_num . ' ';
		}
		if ( $search_type ) {
			$type_info = 'type="' . $search_type . '" ';
		}
		if ( $search_term ) {
			$search_info = 'search_terms="' . $search_term . '" ';
		}
		if ( $search_term || $page_num || $search_type ) {
			$shortcode    = '[elemental_show_members ' . $pagedinfo . $search_info . $type_info . ']';
			$main_display = \do_shortcode( $shortcode );

		} else {
			$main_display = \do_shortcode( '[elemental_show_members]' );
		}

		$render = include __DIR__ . '/../views/membersearch/member-search.php';
		return $render( $main_display, $tab_name );
	}

	/**
	 * Ajax Handler for Member Search Response.
	 *
	 * @param array  $response - the inbound response object.
	 * @param string $search_term - the term searched for.
	 * @return array
	 */
	public function member_search_response( array $response, string $search_term ): array {
		$action_taken = Factory::get_instance( Ajax::class )->get_string_parameter( self::SEARCH_MEMBER_TAB );
		$refresh_tabs = Factory::get_instance( Ajax::class )->get_string_parameter( 'refresh_tabs' );

		if ( 'refresh_tabs' === $refresh_tabs || self::SEARCH_MEMBER_TAB === $refresh_tabs ) {
			$screen             = $this->render_member_search();
			$response['member'] = $screen;

		}

		if ( self::SEARCH_MEMBER_TAB === $action_taken ) {
			$record_type        = Factory::get_instance( Ajax::class )->get_string_parameter( 'type' );
			$response['member'] = $this->render_member_search( $search_term, $record_type );
		}
		$response['membertarget'] = self::SEARCH_MEMBER_TAB;
		return $response;
	}


	/**
	 * Member Directory Shortcode.
	 * Inspired by Youzify Source Code and Rewritten from ClubCloud
	 *
	 * @param array $atts - the shortcode attributes.
	 **/
	public function elemental_members_shortcode( $atts = array() ) {
		$youzify_loaded = ! $this->is_youzer_available();
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version() . wp_rand( 1, 2000 );

		add_filter( 'bp_is_current_component', array( $this, 'elemental_enable_shortcode' ), 10, 2 );
		add_filter( 'bp_is_directory', '__return_true' );

		// Scripts.

		if ( $youzify_loaded ) {
			wp_enqueue_style( 'elemental-directoriesyz-css', plugins_url( '/../css/youzify-directories.css', __FILE__ ), array( 'dashicons' ), $plugin_version );
			wp_enqueue_script( 'elemental-directoriesyz-js', plugins_url( '/../js/directoriesyz.js', __FILE__ ), array( 'jquery' ), $plugin_version, true );
			wp_enqueue_script( 'masonry' );
		} else {
			wp_enqueue_style( 'elemental-directories-css', plugins_url( '/../css/directories.css', __FILE__ ), array( 'dashicons' ), $plugin_version );
			wp_enqueue_script( 'elemental-directories-js', plugins_url( '/../js/directories.js', __FILE__ ), array( 'jquery' ), $plugin_version, true );
		}

		global $elemental_members_loop_arguments;

		if ( isset( $atts['type'] ) ) {
			$drop_down = $this->generate_sort_dropdown( $atts['type'] );
		} else {
			$drop_down = $this->generate_sort_dropdown();
		}

		$defaults = array(
			'per_page'     => 21,
			'member_type'  => false,
			'show_filter'  => 'false',
			'page'         => '1',
			'type'         => 'alphabetical',
			'search_terms' => '',
			'drop_down'    => $drop_down,
			'role__not_in' => array(
				'wcfm_vendor',
				'Store Vendor',
			),
		);

		$elemental_members_loop_arguments = wp_parse_args( $atts, $defaults );

		// Add Filter.
		add_filter( 'bp_after_has_members_parse_args', array( $this, 'elemental_set_loop_query' ) );

		if ( false === $elemental_members_loop_arguments['show_filter'] ) {
			if ( $youzify_loaded ) {
				add_filter( 'youzify_display_members_directory_filter', '__return_false' );
			} else {
				add_filter( 'yz_display_members_directory_filter', '__return_false' );
			}
		}
			$directory_data = '';
		if ( ! empty( $elemental_members_loop_arguments ) ) {
			foreach ( $elemental_members_loop_arguments as $key => $value ) {
				$directory_data .= "data-$key='$value'";
			}
		}

		ob_start();
		// phpcs:ignore-WordPress.Security.EscapeOutput.OutputNotEscaped -- all text is escaped properly.
		echo "<div class='elemental-members-directory-list-shortcode youzify-members-directory-shortcode youzify-directory-shortcode'>";
		if ( $youzify_loaded ) {
			include __DIR__ . '/../views/membersearch/member-template-youzify.php';
		} else {
			include __DIR__ . '/../views/membersearch/member-template.php';
		}

		echo '</div>';

		// Remove Filter.
		remove_filter( 'bp_after_has_members_parse_args', array( $this, 'elemental_set_loop_query' ) );

		if ( false === $elemental_members_loop_arguments['show_filter'] ) {
			if ( $youzify_loaded ) {
				remove_filter( 'youzify_display_members_directory_filter', '__return_false' );
			} else {
				remove_filter( 'yz_display_members_directory_filter', '__return_false' );
			}
		}

		// Unset Global Value.
		unset( $elemental_members_loop_arguments );

		remove_filter( 'bp_is_directory', '__return_true' );
		remove_filter( 'bp_is_current_component', array( $this, 'elemental_enable_shortcode' ), 10, 2 );

		return ob_get_clean();
	}
	/**
	 * Members Directory - Loop Modification in BP.
	 *
	 * @param array $loop - the inbound group loop array.
	 */
	public function elemental_set_loop_query( $loop ) {

		global $elemental_members_loop_arguments;

		$loop = shortcode_atts( $loop, $elemental_members_loop_arguments, 'elemental_members_atts' );

		return $loop;
	}

	/**
	 * Enable Members Directory Component For Shortcode.
	 *
	 * @param array  $active - passed in filter value of what is active in BP.
	 * @param string $component - passed in filter value of Component.
	 */
	public function elemental_enable_shortcode( $active, $component ) {

		if ( 'members' === $component ) {
			return true;
		}

		return $active;

	}
	/**
	 * Generate Sort Drop Down- used in sort filtering.
	 *
	 * @param string $active_type - the current query type filter.
	 */
	private function generate_sort_dropdown( string $active_type = null ) {

		$alphabetical = array( 'alphabetical', '<option value="alphabetical">' . esc_html__( 'Alphabetical', 'myvideoroom' ) . '</option>' );
		$active       = array( 'active', '<option value="active">' . esc_html__( 'Last Active', 'myvideoroom' ) . '</option>' );
		$newest       = array( 'newest', '<option value="newest">' . esc_html__( 'Newest Members', 'myvideoroom' ) . '</option>' );
		$random       = array( 'random', '<option value="random">' . esc_html__( 'Random Selection', 'myvideoroom' ) . '</option>' );
		$popular      = array( 'popular', '<option value="popular">' . esc_html__( 'Popular and Regular', 'myvideoroom' ) . '</option>' );
		$online       = array( 'online', '<option value="online">' . esc_html__( 'Online', 'myvideoroom' ) . '</option>' );

		if ( ! $active_type ) {
			$active_type = 'alphabetical';
		}

		$options       = array( $random, $online, $newest, $active, $popular, $alphabetical );
		$output_string = '';
		foreach ( $options as $option ) {

			if ( $active_type === $option[0] ) {
				$option[1]    .= $output_string;
				$output_string = $option[1];
			} else {
				$output_string .= $option[1];
			}
		}
		ob_start();
		?>
		<li id="members-order-select" class="last filter">
		<?php echo esc_html__( 'Displaying ', 'myvideoroom' ) . '<span id="elemental-capitalise" class="elemental-capitalise">' . \esc_attr( $active_type ) . ' </span>'; ?>
		<label for="members-order-by"><?php esc_html_e( 'Order By:', 'myvideoroom' ); ?></label>
			<select id="members-order-by">
			<?php
				//phpcs:ignore --WordPress.Security.EscapeOutput.OutputNotEscaped - text comes from lines above and is safe.
				echo $output_string;
			do_action( 'bp_members_directory_order_options' );
			?>
			</select>
		</li>

		<?php
		return ob_get_clean();
	}
	/**
	 * Is Youzer Active - checks if Youzer is enabled.
	 *
	 * @return bool
	 */
	public function is_youzer_available(): bool {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		return is_plugin_active( 'youzer/youzer.php' );
	}
}
