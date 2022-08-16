<?php

/**
 * Add user for Managing Users details
 *
 * @package module/Admin Users/views/view-Admin Users-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
    object $current_user
): string {
    ob_start();

    if (user_can($current_user, 'administrator')) {
?>
        <div id="primary" class="site-content">
            <div id="content" role="main">

                <div class="container editcontainer">
                    <div class="row erow headrow">
                        <div class="col-xs-12" id="formHeader">
                            Add User
                        </div>
                    </div>
                    <div class="row erow">
                        <div class="col-xs-12" id="demoContainer">
                            <form id="registrationForm" action="elemental_adduser_ajax" method="post" class="form-horizontal fv-form fv-form-bootstrap ajax" novalidate="novalidate">
                                <button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>


                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Username</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="display_name" value="" />
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Email address</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="user_email" value="" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Firstname</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="firstname" value="" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Lastname</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="lastname" value="" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Custom Capability</label>
                                    <div class="col-xs-8">
                                        <select class="form-control" name="user_role">
                                    
                                                <option value="0" selected>
                                                    Subscriber
                                                </option>
                                                <option value="1">
                                                   Other Role
                                                </option>
                                      
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="userid" value="<?php echo $current_user->ID; ?>">
                                <div class="form-group">
                                    <div class="col-xs-9 col-xs-offset-3 lastButtonForm">
                                        <button type="button" id="addUser" class="btn btn-primary profilebtn" name="add-user" value="add-user">Add User </button>
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