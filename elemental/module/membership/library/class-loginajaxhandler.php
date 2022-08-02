<?php

/**
 * Ajax for Membership  Accounts.
 *
 * @package ElementalPlugin\Module\Membership\Library\LoginAjaxHandler.php
 */

namespace ElementalPlugin\Module\Membership\Library;


/**
 * Class LoginAjaxHandler - Provides the Membership Ajax Control.
 */
class LoginAjaxHandler
{

    /**
     * Elemental Ajax Support.
     * Handles membership function related calls and Ajax.
     *
     * @return mixed
     */
    public function login_ajax_handler()
    {
        $response    = array();
        $response['feedback'] = 'No Change';

        // Security Checks.
        check_ajax_referer('elemental_membership', 'security', false);

        if (isset($_POST['action_taken'])) {
            $action_taken = sanitize_text_field(wp_unslash($_POST['action_taken']));
        }

        if (isset($_POST['user_email'])) {
            $user_email = \sanitize_email(wp_unslash($_POST['user_email']));
        }
        if (isset($_POST['firstname'])) {
            $firstname = sanitize_text_field(wp_unslash($_POST['firstname']));
        }
        if (isset($_POST['lastname'])) {
            $lastname = sanitize_text_field(wp_unslash($_POST['lastname']));
        }
        if (isset($_POST['userid'])) {
            $user_id = sanitize_text_field(wp_unslash($_POST['userid']));
        }
        if (isset($_POST['nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));
        }
        if (isset($_POST['display_name'])) {
            $display_name = sanitize_text_field(wp_unslash($_POST['display_name']));
        }
        if (isset($_POST['user_status'])) {
            $user_status = sanitize_text_field(wp_unslash($_POST['user_status']));
        }

        /*
		* Update User details  section.
		*
		*/
    
        if ('update_edituser' === $action_taken) {
          
               $update =  wp_update_user(array(
                     'ID' => $user_id, 
                     'user_email' => esc_attr($user_email),
                    'display_name' => esc_attr($display_name),
                    'user_status' => esc_attr($user_status)
                    ));
       
                update_user_meta($user_id, 'first_name', esc_attr($firstname));
                update_user_meta($user_id, 'last_name', esc_attr($lastname));
                  
               if($update){
                $response['feedback'] = \esc_html__('User Updated', 'elementalplugin');
               }else{
                   	$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $user_id;
               }
           
        }

        return \wp_send_json($response);
           
       
    }
}
