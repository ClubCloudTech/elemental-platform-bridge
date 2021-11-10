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
use ElementalPlugin\Library\HTML;
use ElementalPlugin\Library\Version;
use ElementalPlugin\WCFM\Library\WCFMHelpers;
use \MyVideoRoomPlugin\Library\Ajax;

/**
 * Member Search Functions
 */
class MemberSearch {

	const SEARCH_MEMBER_TAB = 'elemental-member-tab';

	/**
	 * Render WCFM Organisations Tabs.
	 *
	 * @param array $input   - the inbound menu.
	 * @return array
	 */
	public function render_organisations_tabs( array $input = null ) :array {

		$admin_menu = new MenuTabDisplay(
			\esc_html__( 'Members', 'myvideoroom' ),
			\esc_html__( 'Members', 'myvideoroom' ),
			fn() => $this->render_member_search(),
			self::SEARCH_MEMBER_TAB
		);
		\array_push( $input, $admin_menu );

		return $input;
	}

	/**
	 * Render Organisations from WCFM. Initial Page Render Template.
	 *
	 * @param string $search_term -Whether to search on a given term.
	 * @return array
	 */
	private function render_member_search( string $search_term = null ) :string {
		$tab_name = self::SEARCH_MEMBER_TAB;
		$page_num = Factory::get_instance( Ajax::class )->get_string_parameter( 'page' );
		$base_url = Factory::get_instance( Ajax::class )->get_string_parameter( 'base' );

		if ( $page_num ) {
			$pagedinfo = 'paged = ' . $page_num . ' ';
		}
		if ( $base_url ) {
			$baseinfo = 'baseurl = ' . $base_url . ' ';
		}

		if ( $search_term || $page_num ) {
			$main_display = \do_shortcode( '[elemental_show_members ' . $pagedinfo . 'search_term="' . $search_term . '" ' . $baseinfo . ' ]' );
		} else {
			$main_display = \do_shortcode( '[elemental_show_members ]' );
		}

		$render = include __DIR__ . '/../views/membersearch/member-search.php';
		return $render( $main_display, $tab_name );
	}




	/**
	 * Add Member Directory Shortcode.
	 * Developed from Youzer Source Code and Modified from ClubCloud
	 * 
	 **/
	function elemental_members_shortcode( $atts ) {
		$plugin_version = Factory::get_instance( Version::class )->get_plugin_version();
		define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		define( 'TEMPLATE_PATH', PLUGIN_PATH . '/../emplates/' );
		define( 'ASSETS', plugin_dir_url( __FILE__ ) . 'includes/public/assets/' );
		define( 'VERSION_PLUGIN', $plugin_version . wp_rand( 1, 2000 ) );

		//add_filter( 'bp_is_current_component', 'yz_enable_shortcode_md', 10, 2 );
		add_filter( 'bp_is_directory', '__return_true' );

		// Scripts.
		wp_enqueue_style( 'elemental-directories-css', plugins_url( '/../css/directories.css', __FILE__ ), array( 'dashicons' ), VERSION_PLUGIN );
		wp_enqueue_script( 'elemental-directories-js', plugins_url( '/../js/directories.js', __FILE__ ), array( 'jquery' ), VERSION_PLUGIN, true );

		global $yz_md_shortcode_atts;

		// Get Args.
		$yz_md_shortcode_atts = wp_parse_args(
			$atts,
			array(
				'per_page'    => 12,
				'member_type' => false,
				'show_filter' => 'false',
			)
		);

		// Add Filter.
		add_filter( 'bp_after_has_members_parse_args', 'yz_set_members_directory_shortcode_atts' );

		if ( $yz_md_shortcode_atts['show_filter'] == false ) {
			add_filter( 'yz_display_members_directory_filter', '__return_false' );
		}

		ob_start();

		echo "<div class='elemental-members-directory-list-shortcode'>";
			include __DIR__ . '/../views/membersearch/member-template.php';
		echo '</div>';

		// Remove Filter.
		remove_filter( 'bp_after_has_members_parse_args', 'yz_set_members_directory_shortcode_atts' );

		if ( $yz_md_shortcode_atts['show_filter'] == false ) {
			remove_filter( 'yz_display_members_directory_filter', '__return_false' );
		}

		// Unset Global Value.
		unset( $yz_md_shortcode_atts );

		remove_filter( 'bp_is_directory', '__return_true' );
		remove_filter( 'bp_is_current_component', 'yz_enable_shortcode_md', 10, 2 );

		return ob_get_clean();
	}



}
