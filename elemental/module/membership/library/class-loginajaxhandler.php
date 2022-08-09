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
       
        if ('reset_password' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }
                                 wp_set_password($user_password, $user_id);
                                 $userID = get_userdata($user_id);
                                wp_set_auth_cookie($userID);
                                wp_set_current_user($userID);
                         
                    exit();
       
            if ($exists) {
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
                
                  
               if($update){
                $response['feedback'] = \esc_html__('User Updated', 'elementalplugin');
               }else{
                   	$response['feedback'] = \esc_html__( 'Update Failed', 'elementalplugin' ) . $user_id;
               }
           
        }

        if ('forgot_password' === $action_taken) {
            $verify = \wp_verify_nonce($nonce, 'elemental_membership');
            if (!$verify) {
                $response['feedback'] = \esc_html__('Invalid Security Nonce received', 'elementalplugin');
                return \wp_send_json($response);
            }

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $secret = '6LcnlF0hAAAAAJXHOIy0mXZUEhOfcqNb9p3AV3nh';
            $recaptcha_response = $_POST['token_response'];

          //  print_r($_POST);

            $request = file_get_contents($url . '?secret=' . $secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($request);


            // Take action based on the score returned
            if ($recaptcha->success == true && $recaptcha->score >= 0.5)// && $recaptcha->action == 'contact') {
                {
                $user = get_user_by('email', $user_email);
                $user_id = $user->ID;
                $user_info = get_userdata($user_id);
                $unique = get_password_reset_key($user_info);
                $unique_url = network_site_url("reset-password?action=rp&key=$unique&login=" . rawurlencode($user_info->user_login) . "un=" . $user_id, 'login');

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
                            <img width="260" src="https://coadjute.app/wp-content/uploads/2022/06/Coadjute-Masterbrand-Logo-updated.png" title="logo" alt="logo">
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
                                        <a href="' . $unique_url . '"
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

                $message2 = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml"
      xmlns:th="http://www.thymeleaf.org">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <style type="text/css">
    body, p, div {
      font-family: arial,helvetica,sans-serif;
      font-size: 14px;
    }
    body {
      color: #000000;
    }
    body a {
      color: #1188E6;
      text-decoration: none;
    }
    p { margin: 0; padding: 0; }
    table.wrapper {
      width:100% !important;
      table-layout: fixed;
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: 100%;
      -moz-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    img.max-width {
      max-width: 100% !important;
    }
    .column.of-2 {
      width: 50%;
    }
    .column.of-3 {
      width: 33.333%;
    }
    .column.of-4 {
      width: 25%;
    }
    ul ul ul ul  {
      list-style-type: disc !important;
    }
    ol ol {
      list-style-type: lower-roman !important;
    }
    ol ol ol {
      list-style-type: lower-latin !important;
    }
    ol ol ol ol {
      list-style-type: decimal !important;
    }
    @media screen and (max-width:480px) {
      .preheader .rightColumnContent,
      .footer .rightColumnContent {
        text-align: left !important;
      }
      .preheader .rightColumnContent div,
      .preheader .rightColumnContent span,
      .footer .rightColumnContent div,
      .footer .rightColumnContent span {
        text-align: left !important;
      }
      .preheader .rightColumnContent,
      .preheader .leftColumnContent {
        font-size: 80% !important;
        padding: 5px 0;
      }
      table.wrapper-mobile {
        width: 100% !important;
        table-layout: fixed;
      }
      img.max-width {
        height: auto !important;
        max-width: 100% !important;
      }
      a.bulletproof-button {
        display: block !important;
        width: auto !important;
        font-size: 80%;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }
      .columns {
        width: 100% !important;
      }
      .column {
        display: block !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
      }
      .social-icon-column {
        display: inline-block !important;
      }
    }


    </style>

</head>
<body>
<center class="wrapper" data-link-color="#1188E6"
        data-body-style="font-size:14px; font-family:arial,helvetica,sans-serif; color:#000000; background-color:#FFFFFF;">
    <div class="webkit">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#FFFFFF">
            <tr>
                <td valign="top" bgcolor="#FFFFFF" width="100%">
                    <table width="100%" role="content-container" class="outer" align="center" cellpadding="0"
                           cellspacing="0" border="0">
                        <tr>
                            <td width="100%">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                     
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                                   style="width:100%; max-width:700px;" align="center">
                                                <tr>
                                                    <td role="modules-container"
                                                        style="padding:10px 10px 10px 10px; color:#000000; text-align:left;"
                                                        bgcolor="#FFFFFF" width="100%" align="left">
                                                        <table class="module preheader preheader-hide" role="module"
                                                               data-type="preheader" border="0" cellpadding="0"
                                                               cellspacing="0" width="100%"
                                                               style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
                                                            <tr>
                                                                <td role="module-content">
                                                                    <span th:text="${previewText}"></span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table border="0" cellpadding="0" cellspacing="0" align="center"
                                                               width="100%" role="module" data-type="columns"
                                                               style="padding:0px 0px 0px 0px;" bgcolor="#f1f1f6"
                                                               data-distribution="1">
                                                            <tbody>
                                                            <tr role="module-content">
                                                                <td height="100%" valign="top">
                                                                    <table width="660"
                                                                           style="width:660px; border-spacing:0; border-collapse:collapse; margin:0px 10px 0px 10px;"
                                                                           cellpadding="0" cellspacing="0" align="left"
                                                                           border="0" bgcolor=""
                                                                           class="column column-0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td style="padding:0px;margin:0px;border-spacing:0;">
                                                                                <table class="wrapper" role="module"
                                                                                       data-type="image" border="0"
                                                                                       cellpadding="0" cellspacing="0"
                                                                                       width="100%"
                                                                                       style="table-layout: fixed;"
                                                                                       data-muid="roS4A48of9YUHLguDSh1xv">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="font-size:6px; line-height:10px; padding:0px 0px 0px 0px;"
                                                                                            valign="top" align="left">
                                                                                            <img class="max-width"
                                                                                                 border="0"
                                                                                                 style="display:block; color:#000000; text-decoration:none; font-family:Helvetica, arial, sans-serif; font-size:16px; max-width:100% !important; width:100%; height:auto !important;"
                                                                                                 width="660" alt=""
                                                                                                 data-proportionally-constrained="true"
                                                                                                 data-responsive="true"
                                                                                                 src="http://cdn.mcauto-images-production.sendgrid.net/4c09c012201affc9/108b0e27-83ef-4764-8f40-995c7417bae7/600x132.png">
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module"
                                                                                       data-type="spacer" border="0"
                                                                                       cellpadding="0" cellspacing="0"
                                                                                       width="100%"
                                                                                       style="table-layout: fixed;"
                                                                                       data-muid="7deb70e1-46ea-4cd8-8ce5-66cc02ad92fa">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 5px 0px;"
                                                                                            role="module-content"
                                                                                            bgcolor="">
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module"
                                                                                       data-type="text" border="0"
                                                                                       cellpadding="0" cellspacing="0"
                                                                                       width="100%"
                                                                                       style="table-layout: fixed;"
                                                                                       data-muid="efb6be68-1b18-4171-9945-19178cf60763"
                                                                                       data-mc-module-version="2019-10-22">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 0px 0px; line-height:23px; text-align:inherit;"
                                                                                            height="100%" valign="top"
                                                                                            bgcolor=""
                                                                                            role="module-content">
                                                                                            <div><h3
                                                                                                    style="text-align: center">
                                                                                                <span style="color: #0f0f0f;"
                                                                                                      th:text="${header}"></span>
                                                                                            </h3>
                                                                                                <div></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module"
                                                                                       data-type="text" border="0"
                                                                                       cellpadding="0" cellspacing="0"
                                                                                       width="100%"
                                                                                       style="table-layout: fixed;"
                                                                                       data-muid="96001edd-3521-42d0-8a1b-3e1e76121d85"
                                                                                       data-mc-module-version="2019-10-22">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:18px 10px 18px 20px; line-height:22px; text-align:inherit;"
                                                                                            height="100%" valign="top"
                                                                                            bgcolor=""
                                                                                            role="module-content">
                                                                                            <div>
                                                                                                <div style="font-family: inherit; text-align: inherit">
                                                                                                   
                                                                                                </div>
                                                                                                <div style="font-family: inherit; text-align: inherit">
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
                                        <a href="' . $unique_url . '"
                                            style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset
                                            Password</a>
                                    </td>
                                </tr>
                                                                                                </div>
                                                                                                <div style="font-family: inherit; text-align: inherit"><span
                                                                                                        style="color: #0f0f0f;"  th:utext="${messageBody}"></span>
                                                                                                </div>
                                                                                                <div></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module"
                                                                                       data-type="text" border="0"
                                                                                       cellpadding="0" cellspacing="0"
                                                                                       width="100%"
                                                                                       style="table-layout: fixed;"
                                                                                       data-muid="05fa63e1-3046-4ac9-80e6-0f31e7e59475"
                                                                                       data-mc-module-version="2019-10-22">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:18px 0px 18px 20px; line-height:22px; text-align:inherit;"
                                                                                            height="100%" valign="top"
                                                                                            bgcolor=""
                                                                                            role="module-content">
                                                                                            <div>
                                                                                                <div style="font-family: inherit; text-align: inherit">
                                                                                                    Thanks,
                                                                                                </div>
                                                                                                <div style="font-family: inherit; text-align: inherit">
                                                                                                    <strong>The Coadjute
                                                                                                        Team</strong>
                                                                                                </div>
                                                                                                <div></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module" data-type="divider"
                                                                                       border="0" cellpadding="0"
                                                                                       cellspacing="0" width="100%" style="table-layout: fixed;"
                                                                                       data-muid="ff62341d-97fe-4634-acaa-1ecc0179d9d3">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td style="padding:0px 0px 0px 0px;"
                                                                                            role="module-content" height="100%"
                                                                                            valign="top" bgcolor="">
                                                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                                                   align="center" width="100%"
                                                                                                   height="3px"
                                                                                                   style="line-height:3px; font-size:3px;">
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td style="padding:0px 0px 3px 0px;"
                                                                                                        bgcolor="#5335ca"></td>
                                                                                                </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="module" role="module" data-type="text" border="0"
                                                                                       cellpadding="0"
                                                                                       cellspacing="0" width="100%" style="table-layout: fixed;"
                                                                                       data-muid="90c6ff4e-6693-489d-b4f7-938d94e93a59"
                                                                                       data-mc-module-version="2019-10-22">
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td
                                                                                                style="padding:7px 0px 5px 18px; line-height:10px; text-align:inherit; background-color:#F1F1F6;"
                                                                                                height="100%" valign="top" bgcolor="#F1F1F6"
                                                                                                role="module-content">
                                                                                            <div>
                                                                                                <div style="font-family: inherit; text-align: center"><span
                                                                                                        style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: #444444; font-family: Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; text-align: center; font-size: 11px; line-height: 19.25px; font-weight: bold; background-color: rgb(241, 241, 246)">(Sent
                                            through automation)&nbsp;</span></div>
                                                                                                <div style="font-family: inherit; text-align: center"><span
                                                                                                        style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: #444444; font-family: Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; text-align: center; font-size: 12px; line-height: 15px; background-color: rgb(241, 241, 246)">This
                                            message was sent to  <b th:text="${email}"></b> because your preferences are set to
                                            receive notifications like this. You can change it on your notification
                                            preferences page at any time.</span></div>
                                                                                                <div style="font-family: inherit; text-align: center"><span
                                                                                                        style="background-color: rgb(241, 241, 246)">&nbsp;</span>
                                                                                                </div>
                                                                                                <div style="font-family: inherit; text-align: center"><span
                                                                                                        style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; color: #444444; font-family: Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial; font-size: 11px; line-height: 13.75px; background-color: rgb(241, 241, 246)">
                                                                            Coadjute,
                                            12th Floor, 2 London Wall Place, London, London EC2Y 5AU, United Kingdom,
                                            +44 2033270438</span><span
                                                                                                        style="background-color: rgb(241, 241, 246)">&nbsp;</span>
                                                                                                </div>
                                                                                                <div></div>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <div data-role="module-unsubscribe" class="module" role="module"
                                                                                     data-type="unsubscribe"
                                                                                     style="color:#444444; font-size:12px; line-height:2px; padding:16px 16px 16px 16px; text-align:center;"
                                                                                     data-muid="cf9b8b46-0e03-42c1-9f05-d882ee6646a4">
                                                                                    <div class="Unsubscribe--addressLine"></div>
                                                                                    <p style="font-size:12px; line-height:2px;">
                                                                                        <a href="#"
                                                                                           th:href="@{__${domainName}__/notifications/employees/__${employeeId}__?token=__${token}__&subscription-key=__${apiKey}__}"
                                                                                           target="_blank"
                                                                                           class="Unsubscribe--unsubscribePreferences"
                                                                                           style="">Manage Notification Preferences</a>
                                                                                    </p>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</center>
</body>
</html>';
                $title = 'Reset Password'; // send here from contact form
                $subject = 'Password Reset Form - ' . $title;
                //set headers
                $headers[] = 'From: Coadjute No-Reply <' . $user_email . '>'; // 
                // $headers[] = 'Reply-To: ' . $title . ' <' . $user_email . '>';
                $headers[] = 'Content-Type: text/html: charset=UTF-8';

                $mailResult = false;
                $mailResult = wp_mail($user_email, $subject,
                        $message2,
                        $headers
                    );

                if ($mailResult) {
                    $response['feedback'] = \esc_html__('Email Sent', 'elementalplugin');
                } else {
                    $response['feedback'] = \esc_html__('Something Went Wrong', 'elementalplugin');
                }    
              //  $response['feedback'] = "Your message sent successfully";
            } else {
             
                // Score less than 0.5 indicates suspicious activity. Return an error
                $response['feedback'] = "Something went wrong. Please try again later";
            }
           // exit;

           
    
        }

        return \wp_send_json($response);
           
       
    }
   
}
