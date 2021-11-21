<?php
/**
 * Filters to Modify Default WCFM Behaviour.
 *
 * @package elemental/wcfm/library/class-wcfmfilters.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\UltimateMembershipPro\ElementalUMP;
use \Indeed\Ihc\UserSubscriptions;

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
		add_action( 'wcfm_staffs_manage', array( $this, 'add_staff_ump_subscription' ), 10, 1 );

	}


	/**
	 * Add UMP Subscription to WCFM Staff add.
	 * Automatically adds staff subscription.
	 *
	 * @param int $atts - the shortcode attributes.
	 * @return string
	 */
	public function add_staff_ump_subscription( int $staff_id ): void {

		$level_id = get_option( ElementalUMP::SETTING_UMP_STAFF_SUBSCRIPTION_ID );
		UserSubscriptions::assign( $staff_id, $level_id );
		UserSubscriptions::makeComplete( $staff_id, $level_id, false );

	}

}
