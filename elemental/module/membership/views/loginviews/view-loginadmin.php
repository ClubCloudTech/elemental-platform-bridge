<?php

/**
 * View for Managing Login Users
 *
 * @package module/Login Users/views/view-Login Users-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
    object $current_user
): string {
    ob_start();
   
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <div id="primary" class="site-content">
        <div id="content" role="main">

            <div class="login-fg">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-12 bg" style="background-image:url('https://img.freepik.com/free-photo/abstract-luxury-gradient-blue-background-smooth-dark-blue-with-black-vignette-studio-banner_1258-52393.jpg?w=2000&t=st=1659290640~exp=1659291240~hmac=4fa2bd0af4efcd78bf8daf1d9f13af5488908ee295ce798a5931d502bae1ea71')">
                            <div class="info">
                                <h1>Coadjute</h1>

                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-7 col-md-12 login">
                            <div class="login-section">
                                <div class="logo clearfix" style="color:#323064">
                                    <a href="#">
                                        Welcome Back !
                                    </a>
                                </div>
                                <p>Enter Your Crendentail to Access Your Account</p>
                                <!-- <ul class="social">
                                    <li><a href="#" class="facebook"><i class="fa fa-facebook facebook-i"></i><span>Facebook</span></a></li>
                                    <li><a href="#" class="twitter"><i class="fa fa-twitter twitter-i"></i><span>Twitter</span></a></li>
                                    <li><a href="#" class="google"><i class="fa fa-google google-i"></i><span>Google</span></a></li>
                                </ul> -->
                                <div class="or-login clearfix">
                                    <span>-</span>
                                </div>
                                <div class="form-container">
                                    <form action="#" method="GET">
                                        <div class="form-group form-fg">
                                            <input type="email" name="email" class="input-text" placeholder="Email Address">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        <div class="form-group form-fg">
                                            <input type="email" name="email" class="input-text" placeholder="Password">
                                            <i class="fa fa-unlock-alt"></i>
                                        </div>
                                        <div class="checkbox clearfix">
                                            <div class="form-check checkbox-fg">
                                                <input class="form-check-input" type="checkbox" value="" id="remember">
                                                <label class="form-check-label" for="remember">
                                                    Keep Me Signed In
                                                </label>
                                            </div>
                                            <a href="#">Forgot Password ?</a>
                                        </div>
                                        <div class="form-group mt-2">
                                            <button type="submit" class="btn-md btn-fg btn-block">Login</button>
                                        </div>
                                    </form>
                                </div>
                                <p>Don't have an account? <a href="#" class="linkButton"> Register</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- #content -->
    </div><!-- #primary -->
<?php
    return ob_get_clean();
};
