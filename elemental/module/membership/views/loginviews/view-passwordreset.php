<?php

/**
 * View for Managing Login Users
 *
 * @package module/Login Users/views/view-Login Users-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
    object $redirect
): string {
    ob_start();

    if (check_password_reset_key($_GET['key'], $_GET['login'])) {
?>
        <div id="primary" class="site-content">
            <div id="content" role="main">
                <div class="login-fg">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-12 bg" style="background-size: cover;background-image:url('https://coadjute.app/wp-content/uploads/2022/06/Login.png');background-repeat: no-repeat;">
                                <div class="info">
                                    <h1></h1>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-7 col-md-12 login">
                                <div class="login-section">
                                    <div class="logo clearfix" style="color:#323064">
                                        <a href="#">
                                            Welcome Back !
                                        </a>
                                    </div>
                                    <p>Enter New Password</p>
                                    <div class="or-login clearfix">
                                        <span>-</span>
                                    </div>
                                    <div class="form-container">
                                        <form id="forgotPassForm" action="elemental_edituser_ajax" method="post" class="form-horizontal fv-form fv-form-bootstrap ajax" novalidate="novalidate">
                                            <input type="hidden" name="userid" id="userid" class="input-text" value="<?php echo $_GET['un']?>">
                                            <div class="form-group form-fg">
                                                <input type="text" name="user_password" id="user_password" class="input-text" placeholder="Enter New Password">
                                                <i class="fa fa-envelope signCheck"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <button type="button" class="btn-md btn-fg btn-block" id="reset_pass">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <p>Know Your Account ? <a href="#" class="linkButton"> Login</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div><!-- #content -->
        </div><!-- #primary -->
<?php
    } else {
        $redirect;
    }
    // use wordpress object
    return ob_get_clean();
};
