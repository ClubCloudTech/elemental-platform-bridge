<?php
/**
 * Assist in Tab Rendering and Sorting.
 *
 * @package ElementalPlugin
 */

declare( strict_types=1 );

namespace ElementalPlugin\Library;

/**
 * Version
 */
class TabHelper {

	/**
	 * Sort Global Directory Tabs.
	 *
	 * @param array  $inputs - the tabs to sort.
	 * @param string $term - starting tab desired.
	 * @param bool   $single_tab - return only a single tab (dont push the rest behind it) used to dedicate a custom view.
	 * @return array - a sorted array.
	 */
	public function tab_sort( array $inputs, string $term = null, bool $single_tab = null ) {
		$return_array = array();
		foreach ( $inputs as $input ) {
			$tab = $input->get_tab_slug();
			if ( $tab === $term ) {
				\array_unshift( $return_array, $input );
			} else {
				if ( ! $single_tab ) {
					array_push( $return_array, $input );
				}
			}
		}
		return $return_array;
	}

	/**
	 * Sort Tabs by Default Directory Tab Order.
	 *
	 * @param array $inputs - the tabs to sort.
	 * @return array - a sorted array.
	 */
	public function tab_priority_sort( array $inputs ) {
		usort(
			$inputs,
			function( $a, $b ) {
				return strcmp( strval( $a->get_sort_order() ), strval( $b->get_sort_order() ) );
			}
		);
		return $inputs;
	}


}
