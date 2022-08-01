<?php

/**
 * View for Managing Admin Users
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

            <div class="container">
                <div class="row main-content text-center">
                    <div class="col-md-4 text-center company__info">
                        <span class="company__logo">
                            <h2><span class="fa fa-laptop fic"></span></h2>
                        </span>
                        <h4 class="company_title">Keingston Sandbox</h4>
                    </div>
                    <div class="col-md-4 text-center company__info">
                        <span class="company__logo">
                            <h2><span class="fa fa-user fic"></span></h2>
                        </span>
                        <h4 class="company_title">My Account</h4>
                    </div>
                </div>
            </div>


        </div><!-- #content -->
    </div><!-- #primary -->
<?php
    }else{
        echo 'Login as admin ';
    }
    return ob_get_clean();
};
