<?php

/**
 * Ajax for Membership  Accounts.
 *
 * @package ElementalPlugin\Module\Membership\Library\LoginAjaxHandler.php
 */

namespace ElementalPlugin\Module\Membership\Library;
use ElementalPlugin\Library\Factory;
use ElementalPlugin\Library\Ajax;

/**
 * Class LoginAjaxHandler - Provides the Membership Ajax Control.
 */
class CompanyAjax
{

    /**
     * Elemental Ajax Support.
     * Handles login function related calls and Ajax.
     *
     * @return mixed
     */

    public function company_ajax_handler()
    {
        $response    = array();
        $response['feedback'] = 'No Change';
        
        // Security Checks.
        check_ajax_referer('elemental_membership', 'security', false);

        $action_taken     = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$membership_level = Factory::get_instance( Ajax::class )->get_string_parameter( 'level' );
		$email       = Factory::get_instance( Ajax::class )->get_string_parameter( 'email' );
		$firstname        = Factory::get_instance( Ajax::class )->get_string_parameter( 'first_name' );
		$lastname         = Factory::get_instance( Ajax::class )->get_string_parameter( 'last_name' );
        $display_name        = Factory::get_instance( Ajax::class )->get_string_parameter( 'display_name' );
		$user_id          = Factory::get_instance( Ajax::class )->get_string_parameter( 'userid' );
		$nonce            = Factory::get_instance( Ajax::class )->get_string_parameter( 'security' );
        $user_role = Factory::get_instance( Ajax::class )->get_string_parameter( 'user_role' );
        $company_name = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_name' );
        $company_slug = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_slug' );
        $company_phone = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_phone' );

        if ('update_editcompany' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($email);
            if ($exists) {

                    // write update code here --
                $response['feedback'] = \esc_html__('company edited', 'elementalplugin');
            } else {
                $response['feedback'] = \esc_html__('User Login fail', 'elementalplugin');
            }
         
        }

                if ('add_user' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($email);
            if ($exists) {
                
                $response['feedback'] = \esc_html__('Email already used', 'elementalplugin');
                  
            } else {
                      //  $result = wp_create_user($user_name,$this->randomPassword(),$user_email);
                 $user_details=   array(
                    'user_login' => $display_name,
                    'user_pass' =>'some',// $this->randomPassword(),
                    'user_email' => $email,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'display_name' => $display_name,
                    'role' => 'editor' //$user_role
                 );
                      $result = wp_insert_user( $user_details);
                    if(is_wp_error($result)){
                     $response['feedback'] = $result->get_error_message();
                    //handle error here
                    }else{
                    $user = get_user_by('id', $result);
                    //handle successful creation here
                     $response['feedback'] = \esc_html__('User Created', 'elementalplugin');
                    }
            }
        
        }

        //    public function randomPassword() {
        //     $alphabet = "abcdefghijklmnopqrs)-(@&!tuwxyzABCDEFGHIJKLM)-(@&!tNOPQRSTUWXYZ01234)-(@&!t56789";
        //     $pass = array(); 
        //     $alphaLength = strlen($alphabet) - 1; 
        //     for ($i = 0; $i < 12; $i++) {
        //         $n = rand(0, $alphaLength);
        //         $pass[] = $alphabet[$n];
        //     }
        //     return implode($pass);
        // }





      echo json_encode($response);
    }  
  
}