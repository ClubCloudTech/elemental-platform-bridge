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

        if (isset($_POST['action_taken'])) {
            $action_taken = sanitize_text_field(wp_unslash($_POST['action_taken']));
        }

        if (isset($_POST['cEmail'])) {
            $cEmail = \sanitize_email(wp_unslash($_POST['cEmail']));
        }
        if (isset($_POST['cName'])) {
            $cName = sanitize_text_field(wp_unslash($_POST['cName']));
        }
        if (isset($_POST['lastname'])) {
            $lastname = sanitize_text_field(wp_unslash($_POST['lastname']));
        }
        if (isset($_POST['userid'])) {
            $user_id = sanitize_text_field(wp_unslash($_POST['userid']));
        }
        if (isset($_POST['security'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST['security']));
        }
        if (isset($_POST['cSlug'])) {
            $cSlug = sanitize_text_field(wp_unslash($_POST['cSlug']));
        }
        if (isset($_POST['user_role'])) {
            $user_role = sanitize_text_field(wp_unslash($_POST['user_role']));
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
        if (isset($_POST['display_name'])) {
            $display_name = sanitize_text_field(wp_unslash($_POST['display_name']));
        }
     

        if ('update_editcompany' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($cEmail);
            if ($exists) {

                if ($cEmail != '') {
                    $creds = array();
                    $creds['remember'] = false;
                    $user = wp_signon($creds, false);


                    // if (is_wp_error($user)) {
                    //     $errors_login = $user->get_error_message();
                    // } else {
                    //    // wp_redirect(site_url());
                    //   //  exit;
                    // }
                $response['feedback'] = \esc_html__('User LoggedIn', 'elementalplugin');
            } else {
                $response['feedback'] = \esc_html__('User Login fail', 'elementalplugin');
            }
            }else{
                $response['feedback'] = \esc_html__('Email not found', 'elementalplugin');
            }
        }

                if ('add_user' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($user_email);
            if ($exists) {

                if ($user_email != '') {
               //  $result = wp_create_user($user_name,$this->randomPassword(),$user_email);
                 $user_details=   array(
                    'user_login' => $display_name,
                    'user_pass' => $this->randomPassword(),
                    'user_email' => $user_email,
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

               
            } else {
                $response['feedback'] = \esc_html__('User add fail', 'elementalplugin');
            }
            }else{
                $response['feedback'] = \esc_html__('Email already used', 'elementalplugin');
            }
        }

           public function randomPassword() {
            $alphabet = "abcdefghijklmnopqrs)-(@&!tuwxyzABCDEFGHIJKLM)-(@&!tNOPQRSTUWXYZ01234)-(@&!t56789";
            $pass = array(); 
            $alphaLength = strlen($alphabet) - 1; 
            for ($i = 0; $i < 12; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            return implode($pass);
        }





      echo json_encode($response);
    }  
  
}