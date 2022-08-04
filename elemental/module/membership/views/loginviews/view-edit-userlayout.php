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

   // print_r($current_user);
//    echo $current_user->user_pass;
//    echo '<br>';
//    $password = '#ktXxL7&fHd4HZon';
//    echo wp_check_password($password, $current_user->user_pass, $current_user->ID).' -- ';

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
                            <form id="registrationForm" action="elemental_edituser_ajax" method="post" class="form-horizontal fv-form fv-form-bootstrap ajax" novalidate="novalidate">
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
                                    <label class="col-xs-3 control-label">Password</label>
                                    <div class="col-xs-5">
                                        <input type="password" class="form-control" name="user_password" value="" />
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
                                <input type="hidden" name="userid" value="<?php echo $current_user->ID; ?>">
                                <div class="form-group">
                                    <div class="col-xs-9 col-xs-offset-3">
                                        <button type="button" id="update_user1" class="btn btn-primary" name="update-user" value="update-user">Update User </button>
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
