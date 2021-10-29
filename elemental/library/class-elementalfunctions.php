<?php
/**
 * Display Functions PHP legacy
 *
 * @package ElementalPlugin\Library
 */

namespace ElementalPlugin\Library;

use ElementalPlugin\WCFM\WCFMTools;

/**
 * Class SectionTemplate
 */
class ElementalFunctions {

	/**
	 * Initialise Module
	 *
	 * @return void
	 */
	public function init() {
		//Factory::get_instance( WCFMTools::class )->elemental_get_wcfm_memberships( $id_only ) ;
		//$this->alextest();
		add_filter( 'wcfm_login_redirect', array ( $this, 'custom_wcfm_login_redirect' ), 10, 2 );
		//add_filter( 'wcfm_login_redirect', array ( Factory::get_instance( testclass::class), 'custom_wcfm_login_redirect' ), 10, 2 );
        

	private function alextest() {
		echo 'Alex';
	}

	public function custom_wcfm_login_redirect( $redirect_to, $user){
		$redirect_to = $site_url;
	return $redirect_to . '/control-centre';
	}

    public function cc_loungeswitch() 
{	$post = get_post();//getting current page information to compare parent owners
	$user = wp_get_current_user();//Fetch User Parameters and Roles
 	$roles = ( array ) $user->roles;
 	//Handling Admin Roles - sending them to Admin Lounge
	if($user->roles[0]=='administrator'){
	return do_shortcode ('[elementor-template id="20006"]');		}
	//If user is non-admin Then get membership level and Re-create Array from Wordpress text input
	$membership_level = get_user_meta ($user->id,'ihc_user_levels');
	$memlev = explode(',',$membership_level[0]);
	$array_count = count($memlev);
	//Template Selection Switch- There are Array of subscription options, so we run this once for each major position in Array.
	echo $memlev[$x];
	for ($x = 0; $x <= $array_count-1; $x++) 
	{	switch ($memlev[$x]) 												{
				case "6": //Industry Level 3
		       return do_shortcode ('[elementor-template id="17076"]');				   break;
		  	case "5"://Industry Level 2 17078
		       	return do_shortcode ('[elementor-template id="17076"]');		       break;
		    case "4"://Industry Level 1 17081
		       	return do_shortcode ('[elementor-template id="17076"]');               break;
			case "9"://LA Individual 
		       	return do_shortcode ('[elementor-template id="17230"]');   			   break;
			case "10"://LA Corporate
		       	return do_shortcode ('[elementor-template id="17225"]');		       break;
		    case "11"://Site Admin
		       	return do_shortcode ('[elementor-template id="20006"]');		       break;
			case "12"://Associate Partner
		       	return do_shortcode ('[elementor-template id="17502"]');		       break;
			case "16"://Staff Credentials
		       	return do_shortcode ('[elementor-template id="22906"]');			   break;
			case "17"://Individual Account
				return do_shortcode ('[elementor-template id="17076"]');			   break;
			case "18"://Knowledge Partner
				return do_shortcode ('[elementor-template id="29578"]');			   break;														}				
	}	//sets default case in case no selection by merchant
	if ($switchdef==true){
		echo do_shortcode ('[elementor-template id="17081"]');			}
}
add_shortcode('ccloungeswitch', 'cc_loungeswitch');

}
