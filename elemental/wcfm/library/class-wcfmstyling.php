<?php
/**
 * Filters to Modify Default WCFM Behaviour.
 *
 * @package elemental/wcfm/library/class-wcfmfilters.php
 */

namespace ElementalPlugin\WCFM\Library;

use ElementalPlugin\Factory;

/**
 * Filters to Modify Default WCFM Behaviour.
 */
class WCFMStyling {

	/**
	 * Run the Filters.
	 */
	public function init() {

		\add_filter( 'elemental_store_header_class', array( $this, 'elemental_header_style_filter' ), 10, 3 );

	}

	/**
	 * Run the Filters.
	 */
	public function elemental_header_style_filter( $input_class, int $store_id, $object ) {

		if ( Factory::get_instance( WCFMTools::class )->elemental_am_i_premium( $store_id ) ) {
			$input_class .= ' elemental-premium-header ';
		}
		return $input_class;
	}
}
