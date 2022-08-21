<?php
/**
 * Filters to Modify Default WCFM Behaviour.
 *
 * @package elemental/wcfm/library/class-wcfmfilters.php
 */

namespace ElementalPlugin\Module\WCFM\Library;

use ElementalPlugin\Library\Factory;
use ElementalPlugin\Module\UltimateMembershipPro\Library\UMPMemberships;

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
		add_action( 'wcfm_staffs_manage', array( Factory::get_instance( UMPMemberships::class ), 'add_tenant_admin_ump_subscription' ), 10, 1 );

	}
}
