<?php

/**
 * Edit user for Users details
 *
 *  @package  module/membership/views/view-edituser.php
 */


return function (
    object $current_user,

): string {
    ob_start();

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
                            <h4 id="edit_notifyuser" style="    text-align: center;color:#388015"> </h4>
                        </div>
                    </div>

                    <div class="row erow">
                        <div class="col-xs-12" id="demoContainer">
                            <form id="registrationForm" action="elemental_editcompany_ajax" method="post" class="form-horizontal fv-form fv-form-bootstrap ajax" novalidate="novalidate">

                                <div class="form-group">
                                    <label class="col-xs-3 control-label"><?php echo esc_html__('Email Address', 'elementalplugin'); ?></label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" style="color:#877878" value="<?php echo $current_user->user_email; ?>" disabled />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">User Name</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="display_name" value="<?php echo $current_user->display_name; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">First Name</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="first_name" value="<?php echo $current_user->user_firstname; ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Last Name</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="last_name" value="<?php echo $current_user->user_lastname; ?>" />
                                    </div>
                                </div>
                                <input type="hidden" name="userid" value="<?php echo $current_user->ID; ?>">
                                <div class="form-group">
                                    <div class="col-xs-8 col-xs-offset-3">
                                        <button type="button" id="update_user1" style="width:100%;background:#323064" class="btn btn-primary" name="update-user" value="update-user">Save Changes </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div><!-- #content -->
            </div><!-- #primary -->
    <?php
    return ob_get_clean();
};
