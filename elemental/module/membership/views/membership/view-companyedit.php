<?php

/**
 * Edit Organisation for Managing  details
 *
 * @package  module/membership/views/view-companyedit.php
 * @param string $current_user - get Orgainsation details

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
                            Edit Organistation
                        </div>
                    </div>
                    <div class="row erow contentRow">
                        <div class="col-xs-12" id="demoContainer">
                            <form id="companyForm" action="elemental_editcompany_ajax" method="post" class="form-horizontal fv-form fv-form-bootstrap ajax" novalidate="novalidate">
                                <button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>


                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Company Name</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="company_name" value="<?php echo $current_user->display_name; ?>" />
                                    </div>
                                </div>


                                  <div class="form-group">
                                    <label class="col-xs-3 control-label">Company Slug</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="company_slug" value="<?php echo $current_user->user_firstname; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Email address</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="email" value="<?php echo $current_user->user_email; ?>" />
                                    </div>
                                </div>

                              

                                <div class="form-group">
                                    <label class="col-xs-3 control-label">Company Phone</label>
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control" name="company_phone" value="<?php echo $current_user->user_lastname; ?>" />
                                    </div>
                                </div>
                                <input type="hidden" name="userid" value="<?php echo $current_user->ID; ?>">
                                <div class="form-group">
                                     <label class="col-xs-3 control-label"></label>
                                    <div class="col-xs-8 col-xs-offset-3 lastButtonForm">
                                        <button type="button" id="update_profile" class="btn btn-primary profilebtn" name="update-user" value="update-user">Update </button>
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