<?php
/**
 * Class Shortcode Tabs - renders a shortcode with multiple tabs inside it.
 *
 * @package MyVideoRoomPlugin\Modules\SiteVideo
 */

namespace ElementalPlugin\ShortCode;

use \MyVideoRoomPlugin\Entity\MenuTabDisplay;


/**
 * Class Shortcode Tabs - renders a shortcode with multiple tabs inside it.
 */
class ShortCodeTab {

	const SHORTCODE_TAG = 'elemental_tabs';

	/**
	 * Initializer function, returns a instance of the plugin
	 *
	 * @return void
	 */
	public function init() {
		\add_shortcode( self::SHORTCODE_TAG, array( $this, 'elemental_tab' ) );
	}

	/**
	 * Provides Shortcode support for SiteVideo Conference Center Rooms.
	 *
	 * @param mixed $params - ID - the PostID that comes from Shortcode.
	 *
	 * @return string
	 */
	public function elemental_tab( $params = array() ) {

		$array_output = array();

		foreach ( $params as $param ) {
			$out_pair = null;

			$shortcode   = substr( $param, strpos( $param, ',' ) + 1 );
			$display_tab = strtok( $param, ',' );

			$out_pair = array(
				'displaytab' => $display_tab,
				'shortcode'  => $shortcode,
			);

			\array_push( $array_output, $out_pair );
		}

		return $this->tab_render_fuction( $array_output );
	}

	/**
	 * A Shortcode for the Site Video Room - Host
	 * This is used for the Member admin entry pages to access their preferred Video Layout - it is paired with the sitevideoroomguest function and accessed by the relevant video switch
	 *
	 * Usage - tab1= "Tab_Name, shortcode (without square brackets)", tab2= "Tab_Name2, shortcode2"
	 * Example [elemental_tabs tab= "First Tab Name, First_Shortcode" tab2= "Second Tab Name, products category=\"category\" tab3= "Third Shortcode, woocommerce_my_account" ]
	 *
	 * @param array $output_tabs - Output Tabs to send to tab view.
	 *
	 * @return string
	 */
	public function tab_render_fuction( array $output_tabs ): string {
		wp_enqueue_script( 'myvideoroom-admin-tabs' );
		$menutab = array();
		foreach ( $output_tabs as $tab ) {
			$short_code_build = '[' . $tab['shortcode'] . ']';
			$menu             = new MenuTabDisplay(
				$tab['displaytab'],
				preg_replace( '/\s+/', '', $tab['displaytab'] ),
				fn() => \do_shortcode( $short_code_build )
			);
			array_push( $menutab, $menu );
		}
		$render = require __DIR__ . '/../views/shortcode/shortcode-tab.php';
		return $render( $menutab );
	}
}
