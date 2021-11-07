<?php
/**
 * Filters to Modify Default WCFM Behaviour.
 *
 * @package elemental/wcfm/library/class-wcfmfilters.php
 */

namespace ElementalPlugin\WCFM\Library;

/**
 * Filters to Modify Default WCFM Behaviour.
 */
class WCFMFilters {

	/**
	 * Run the Filters.
	 */
	public function init() {

		// Fix for Elementor 404 Bug in WCFM Stores.
		\add_filter(
			'wcfmmp_is_allow_elementor_is_post_type_archive_reset',
			function () {
				return false;
			},
			99,
			2
		);

		// Fix for Showing Storename in BuddyPress Profile even though BP module in WCFM is Off WCFM bug.
		\add_filter(
			'wcfm_is_allow_bp_store_nav_display',
			function () {
				return false;
			},
			99,
			2
		);

	}



}
