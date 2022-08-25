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
class CompanyAjax {


	/**
	 * Elemental Ajax Support.
	 * Handles login function related calls and Ajax.
	 *
	 * @return mixed
	 */

	public function company_ajax_handler() {
		$response             = array();
		$response['feedback'] = 'No Change';

		// Security Checks.
		check_ajax_referer( 'elemental_membership', 'security', false );

		$action_taken     = Factory::get_instance( Ajax::class )->get_string_parameter( 'action_taken' );
		$membership_level = Factory::get_instance( Ajax::class )->get_string_parameter( 'level' );
		$email            = Factory::get_instance( Ajax::class )->get_string_parameter( 'email' );
		$firstname        = Factory::get_instance( Ajax::class )->get_string_parameter( 'first_name' );
		$lastname         = Factory::get_instance( Ajax::class )->get_string_parameter( 'last_name' );
		$display_name     = Factory::get_instance( Ajax::class )->get_string_parameter( 'display_name' );
		$user_id          = Factory::get_instance( Ajax::class )->get_string_parameter( 'userid' );
		$nonce            = Factory::get_instance( Ajax::class )->get_string_parameter( 'security' );
		$user_role        = Factory::get_instance( Ajax::class )->get_string_parameter( 'user_role' );
		$company_name     = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_name' );
		$company_slug     = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_slug' );
		$company_phone    = Factory::get_instance( Ajax::class )->get_string_parameter( 'company_phone' );

		if ( 'update_editcompany' === $action_taken ) {
			$verify = \wp_verify_nonce( $nonce, 'elemental_membership' );
			if ( ! $verify ) {
				$response['feedback'] = \esc_html__( 'Invalid Security Nonce received', 'elementalplugin' );
				return \wp_send_json( $response );
			}
			$exists = email_exists( $email );
			if ( $exists ) {

					// write update code here --
				$response['feedback'] = \esc_html__( 'company edited', 'elementalplugin' );
			} else {
				$response['feedback'] = \esc_html__( 'User Login fail', 'elementalplugin' );
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
                
                 $user_details=   array(
                    'user_login' => $display_name,
                    'user_pass' =>wp_generate_password( 12, true, true ),
                    'email' => $email,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'display_name' => $display_name,
                    'role' => 'editor' //$user_role
                 );
                    $result = wp_insert_user( $user_details);
                    // Add invitation Mail Function here -
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

           if ('forgot_password' === $action_taken) {
                $verify = \wp_verify_nonce($nonce, 'elemental_membership');
                if (!$verify) {
                    $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                    return \wp_send_json($response);
                }

                $exists = email_exists($email);
                if ($exists) {
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $secret = '6LcnlF0hAAAAAJXHOIy0mXZUEhOfcqNb9p3AV3nh';
                    $recaptcha_response = $_POST['token_response'];


                $request = file_get_contents($url . '?secret=' . $secret . '&response=' . $recaptcha_response);
                $recaptcha = json_decode($request);

                if ($recaptcha->success == true && $recaptcha->score >= 0.5)
                    {
                    $user = get_user_by('email', $email);
                    $user_id = $user->ID;
                    $user_info = get_userdata($user_id);
                    $unique = get_password_reset_key($user_info);
                    $unique_url = network_site_url("reset-password?action=rp&key=$unique&login=" . rawurlencode($user_info->user_login) . "un=" . $user_id, 'login');

                    ob_start();
                    require __DIR__ . '/../views/emailTemplate/resetPassword.php';
                    $message = ob_get_clean();
        
                    $title = 'Reset Password'; // send here from contact form
                    $subject = 'Password Reset Form - ' . $title;
                    //set headers
                    $headers[] = 'From: Coadjute No-Reply <' . $email . '>'; // 
                    // $headers[] = 'Reply-To: ' . $title . ' <' . $email . '>';
                    $headers[] = 'Content-Type: text/html: charset=UTF-8';

                    $mailResult = false;
                    $mailResult = wp_mail($email, $subject,$message, $headers);

                    if ($mailResult) {
                        $response['feedback'] = \esc_html__('Email Sent', 'elementalplugin');
                    } else {
                        $response['feedback'] = \esc_html__('Something Went Wrong', 'elementalplugin');
                    }    
                } else {
                
                    // Score less than 0.5 indicates suspicious activity. Return an error
                    $response['feedback'] = "Something went wrong. Please try again later";
                }

                }else{
                    $response['feedback'] = \esc_html__('Email Does Not Exist', 'elementalplugin');
                }

            }

        if ('update_edituser' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $update =  wp_update_user(array(
                'ID' => $user_id,
                'display_name' => esc_attr($display_name),
                'first_name' =>  esc_attr($firstname),
                'last_name' =>  esc_attr($lastname)
            ));

            if ($update) {
                $response['feedback'] = \esc_html__('User Updated', 'elementalplugin');
            } else {
                $response['feedback'] = \esc_html__('Update Failed', 'elementalplugin');
            }
        }

        return \wp_send_json($response);
    }  
  
}
