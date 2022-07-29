<?php

/**
 * View for Managing Pagemanage
 *
 * @package module/Pagemanage/views/view-Pagemanage-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
    object $current_user
): string {
    ob_start();

    wp_dequeue_style('login-form-min.css');
    wp_enqueue_style('login-style', plugin_dir_url(__FILE__) . '/assets/css/login-style.css', false);

    // echo get_bloginfo('template_directory');

    // echo '<br>';
    //echo get_template_directory_uri() . '/assets/css/login-style.css';
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <div id="primary" class="site-content">
        <div id="content" role="main">

            <div class="containerL">
                <main class="signup-container">
                    <h1 class="heading-primary">Welcom Back <span class="span-blue">.</span></h1>
                    <p class="text-mute">Enter your credentials to access your account.</p>


                    <form class="signup-form">
                        <label class="inp">
                            <input type="email" class="input-text" placeholder="&nbsp;" autocomplete="off" required>
                            <span class="label">Email</span>
                            <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                        </label>
                        <label class="inp">
                            <input type="password" class="input-text" placeholder="&nbsp;" id="password" autocomplete="off" required>
                            <span class="label">Password</span>
                            <span class="input-icon input-icon-password" data-password><i class="fa-solid fa-eye"></i></span>
                        </label>
                        <button class="btn btn-login">Login</button>
                    </form>
                    <div class="login-wrapper">
                        <div class="seperator">
                            <!-- <span>or do it via E-mail</span> -->
                            <div class="line-breaker">
                                <span class="line"></span>
                                <span>or</span>
                                <span class="line"></span>
                            </div>
                        </div>
                        <div class="social-buttons">
                            <a href="#" class="btn btn-google">
                                <i class="fa-brands fa-facebook"></i> Facebook
                            </a>
                            <a href="#" class="btn btn-google">
                                <i class="fa-brands fa-linkedin-in"></i> Linkdin
                            </a>
                            <a href="#" class="btn btn-google">
                                <i class="fa-brands fa-google"></i> Google
                            </a>

                        </div>


                    </div>
                    <p class="text-mute">Not a member? <a href="$">Sign up</a></p>
                </main>
                <div class="welcome-container">
                    <h1 class="heading-secondary">Welcome to ! <span class="lg">Coadjute!</span></h1>
                    <img src="https://images.adsttc.com/media/images/5c8b/2a4c/284d/d1e4/9400/01e5/large_jpg/1_TheJungleFrameHouse_Outside_1.jpg" alt="">

                </div>
            </div>

        </div><!-- #content -->
    </div><!-- #primary -->
<?php
    return ob_get_clean();
};
