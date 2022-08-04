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
     * Handles login function related calls and Ajax.
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
        if (isset($_POST['security'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST['security']));
        }
        if (isset($_POST['display_name'])) {
            $display_name = sanitize_text_field(wp_unslash($_POST['display_name']));
        }
        if (isset($_POST['user_status'])) {
            $user_status = sanitize_text_field(wp_unslash($_POST['user_status']));
        }
        if (isset($_POST['user_password'])) {
            $user_password = sanitize_text_field(wp_unslash($_POST['user_password']));
        }

        if ('admin_login' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($user_email);
            if ($exists) {

                if ($user_email != '' && $user_password != '') {
                    $creds = array();
                    $creds['user_login'] = $user_email;
                    $creds['user_password'] = $user_password;
                    $creds['remember'] = false;
                    $user = wp_signon($creds, false);
                    if (is_wp_error($user)) {
                        $errors_login = $user->get_error_message();
                    } else {
                        wp_redirect(site_url());
                      //  exit;
                    }
                $response['feedback'] = \esc_html__('User LoggedIn', 'elementalplugin');
            } else {
                $response['feedback'] = \esc_html__('User Login fail', 'elementalplugin');
            }
            }else{
                $response['feedback'] = \esc_html__('Email not found', 'elementalplugin');
            }
        }
        /*
		* Update User details  section.
		*
		*/
       
        if ('check_email' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            $exists = email_exists($user_email );
            if ( $exists ) {
                $response['feedback'] = \esc_html__('Email Found', 'elementalplugin');
            } else {
                $response['feedback'] = \esc_html__('Email NotFound', 'elementalplugin');
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
                     'user_email' => esc_attr($user_email),
                    'display_name' => esc_attr($display_name),
                    'user_status' => esc_attr($user_status)
                    ));
       
                update_user_meta($user_id, 'first_name', esc_attr($firstname));
                update_user_meta($user_id, 'last_name', esc_attr($lastname));
                    //      wp_set_password($user_password, $user_id);
                    // $userID = get_userdata($user_id);
                    //             wp_set_auth_cookie($userID);
                    //             wp_set_current_user($userID);
                    //          //    do_action('wp_login', $userID->user_login, $user_id);
                    //         $redirect_to = "https://wordpress.test/sand-2/";
                    //             wp_safe_redirect($redirect_to);
                    //exit();
                  
               if($update){
                $response['feedback'] = \esc_html__('User Updated', 'elementalplugin');
               }else{
                   	$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $user_id;
               }
           
        }
        if ('reset_mail' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
            print_r($_POST);
           $message = '    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700);" font-family: "Open Sans, sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                       <td style="text-align:center;">
                          <a href="https://coadjute.com" title="logo" target="_blank">
                            <img width="260" src="https://www.reapit.com/wp-content/uploads/Coadjute-Masterbrand-Logo-Full-Colour-Landscape-RTM.png" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik,sans-serif;">You have
                                            requested to reset your password</h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            We cannot simply send you your old password. A unique link to reset your
                                            password has been generated for you. To reset your password, click the
                                            following link and follow the instructions.
                                        </p>
                                        <a href="javascript:void(0);"
                                            style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset
                                            Password</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>www.coadjute.com</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
            $title = 'Sid Test1'; // send here from contact form
            // $email = 'demo@demo.com'; // send here from contact form
            //init
           // $message = wp_strip_all_tags("Hi message");
            $to = "siddharth@coadjute.com"; //get_bloginfo('admin_email');
            $subject = 'My Password Reset Form - ' . $title;
            //set headers
            $headers[] = 'From: Sid <' . $to . '>'; // 'From: Alex <me@alecaddd.com>'
            $headers[] = 'Reply-To: ' . $title . ' <' . $user_email . '>';
            $headers[] = 'Content-Type: text/html: charset=UTF-8';

            print_r($headers);
            //send email
            // wp_mail($to, $subject, $message, $headers);
            // echo "Done";
            $mailResult = false;
            $mailResult = wp_mail($user_email, $subject, $message, $headers);
            var_dump($mailResult);
            if (\wp_mail($user_email, $subject, $message, $headers)) {
                echo "sending mail test - ".$mailResult;
            } else {
                echo "not";
            }
            //exit
          //  exit(1);
        }

        return \wp_send_json($response);
           
       
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

    // public function smtp_ajax_handler()
    // {
    //     $response    = array();
    //     $response['feedback'] = 'No Change';

    //     // Security Checks.
    //     check_ajax_referer('elemental_membership', 'security', false);

    //     if (isset($_POST['action_taken'])) {
    //         $action_taken = sanitize_text_field(wp_unslash($_POST['action_taken']));
    //     }

    //     if (isset($_POST['user_email'])) {
    //         $user_email = \sanitize_email(wp_unslash($_POST['user_email']));
    //     }
    //     if (isset($_POST['nonce'])) {
    //         $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));
    //     }
    //     if ('reset_mail' === $action_taken) {

    //     $title = 'Test user'; // send here from contact form
    //    // $email = 'demo@demo.com'; // send here from contact form
    //     //init
    //     $message = wp_strip_all_tags("Hi message");
    //     $to = "siddharth@coadjute.com";//get_bloginfo('admin_email');
    //     $subject = 'My Contact Form - ' . $title;
    //     //set headers
    //     $headers[] = 'From: Sid <' . $to . '>'; // 'From: Alex <me@alecaddd.com>'
    //     $headers[] = 'Reply-To: ' . $title . ' <' . $user_email . '>';
    //     $headers[] = 'Content-Type: text/html: charset=UTF-8';

    //     //send email
    //     wp_mail($to, $subject, $message, $headers);
    //     //exit
    //     exit(1);
    //     }

    // }

}
