<?php
/**
 * Membership Package
 * Application for Child Account Management
 *
 * @package ElementalPlugin\Membership
 */

namespace ElementalPlugin\UltimateMembershipPro;

use ElementalPlugin\Factory;
use ElementalPlugin\UltimateMembershipPro\Library\ShortCodesUMP;

/**
 * Class ElementalUMP
 * Supports Ultimate Membership Pro Functions.
 */
class ElementalUMP {


	/**
	 * Runtime Shortcodes and Setup
	 * Required for Normal Runtime.
	 */
	public function init() {
		\add_shortcode( 'elemental_ump', array( Factory::get_instance( ShortCodesUMP::class ), 'render_level_name' ) );
	}
	/**
	 * Activate Functions for Membership.
	 */
	public function activate() {

	}
	/**
	 * Render Membership Config Page
	 * Renders configuration of Membership Management Plugin
	 */
	public function render_ump_membership_page() {

	}
}


