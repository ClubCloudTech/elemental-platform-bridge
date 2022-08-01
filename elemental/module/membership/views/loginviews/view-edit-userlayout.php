<?php

/**
 * Edit user for Managing Users details
 *
 * @package module/Admin Users/views/view-Admin Users-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
    object $current_user
): string {
    ob_start();
    global $wp;

    // echo 'Username: ' . $current_user->user_login . '<br />';
    // echo 'User email: ' . $current_user->user_email . '<br />';
    // echo 'User first name: ' . $current_user->user_firstname . '<br />';
    // echo 'User last name: ' . $current_user->user_lastname . '<br />';
    // echo 'User display name: ' . $current_user->display_name . '<br />';
    // echo 'User ID: ' . $current_user->ID . '<br />';
    //  echo $roles = (array) $current_user->roles;
    //echo    $current_url = home_url(add_query_arg(array(), $wp->request));
   // print_r($_POST);
    wp_dequeue_style('login-form-min.css');
    wp_enqueue_style('login-adminstyle', plugin_dir_url(__FILE__) . '../../css/login-adminstyle.css', false);
    wp_enqueue_style('fontAwesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false);
    wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', false);
    // echo get_bloginfo('template_directory');
    // echo '<br>';
    //echo get_template_directory_uri() . '/assets/css/login-style.css';

    $error = array();
    /* If profile was saved, update profile. */
    // echo  $_SERVER['REQUEST_METHOD'];
    if ('POST' == $_SERVER['REQUEST_METHOD']  && $_POST['update-user'] == 'update-user') {

        /* Update user password. */
        // if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
        //     if ($_POST['pass1'] == $_POST['pass2'])
        //         wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['pass1'])));
        //     else
        //         $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
        // }
        /* Update user information. */
        // if (!empty($_POST['url']))
        //     wp_update_user(array('ID' => $current_user->ID, 'user_url' => esc_url($_POST['url'])));
        if (!empty($_POST['user_email'])) {
            if (!is_email(esc_attr($_POST['user_email'])))
                $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
            elseif (email_exists(esc_attr($_POST['user_email'])) != $current_user->id)
                $error[] = __('This email is already used by another user.  try a different one.', 'profile');
            else {
                wp_update_user(array('ID' => $current_user->ID, 'user_email' => esc_attr($_POST['user_email'])));
            }
        }
        if (!empty($_POST['display_name'])) {
            wp_update_user(array('ID' => $current_user->ID, 'display_name' => esc_attr($_POST['display_name'])));
        }
        if (!empty($_POST['user_status'])) {
            wp_update_user(array('ID' => $current_user->ID, 'user_status' => esc_attr($_POST['user_status'])));
        }

        if (!empty($_POST['firstname']))
            update_user_meta($current_user->ID, 'first_name', esc_attr($_POST['firstname']));
        if (!empty($_POST['lastname']))
            update_user_meta($current_user->ID, 'last_name', esc_attr($_POST['lastname']));
     
        /* Redirect so the page will show updated info.*/
        if (count($error) == 0) {
            // do something here -- 
            
            //action hook for plugins and extra fields saving
            //  do_action('edit_user_profile_update', $current_user->ID);
            //  wp_redirect('https://wordpress.test/edit-user/');
            
            // exit;
            // print_r($error);
            // echo 'error';
        }
    }


    if (user_can($current_user, 'administrator')) {
?>
        <div id="primary" class="site-content">
            <div id="content" role="main">

                <div class="container editcontainer">
                    <div class="row erow headrow">
                        <div class="col-xs-12" id="demoContainer">
                            Edit User
                        </div>
                    </div>
                    <div class="row erow">
                        <div class="col-xs-12" id="demoContainer">
                            <form id="registrationForm" action="" method="post" class="form-horizontal fv-form fv-form-bootstrap" novalidate="novalidate">
                                <button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>


                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="display_name" value="<?php echo $current_user->display_name; ?>" />
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Email address</label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="user_email" value="<?php echo $current_user->user_email; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Firstname</label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="firstname" value="<?php echo $current_user->user_firstname; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Lastname</label>
                                    <div class="col-xs-5">
                                        <input type="text" class="form-control" name="lastname" value="<?php echo $current_user->user_lastname; ?>" />
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Status</label>
                                    <div class="col-xs-5">
                                        <select class="form-control" name="user_status">
                                            <?php
                                            if ($current_user->user_status == 0) {
                                            ?>
                                                <option value="0" selected>
                                                    Active
                                                </option>
                                                <option value="1">
                                                    In-active
                                                </option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="0">
                                                    Active
                                                </option>
                                                <option value="1" selected>
                                                    In-active
                                                </option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-9 col-xs-offset-3">
                                        <input type="submit" class="btn btn-primary" name="update-user" value="update-user">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div><!-- #content -->
            </div><!-- #primary -->
    <?php
    } else {
        echo 'Login as admin ';
    }
    return ob_get_clean();
};
