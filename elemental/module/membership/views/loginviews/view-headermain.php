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
        <nav class="navbar">
            <div class="containerNav nn1">
                <div class="navbar-header">
                    <button class="navbar-toggler" data-toggle="open-navbar1">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <a href="https://coadjute.com" title="logo" target="_blank">
                        <img width="260" src="https://coadjute.app/wp-content/uploads/2022/06/Coadjute-Masterbrand-Logo-updated.png" title="logo" alt="logo">
                        <!-- <h4>Awesome<span>logo</span></h4> -->
                    </a>
                </div>

                <div class="navbar-menu" id="open-navbar1 n1">
                    <ul class="navbar-nav">
                        <li class="lin active"><a href="#">Home</a></li>

                        <li class="lin"><a href="#">SandBox</a></li>
                        <li class="lin"><a href="#">Other</a></li>
                    </ul>
                </div>
    
                <div class="navbar-menu" id="open-navbar1 n2">
                    <ul class="navbar-nav">
                        <li class="lin navbar-dropdown">
                            <a href="#" class="dropdown-toggler" data-dropdown="my-dropdown-id">
                                <i class="fa fa-user"></i> <?php echo $current_user->display_name;?> <i class="fa fa-angle-down dropdown-toggler" data-dropdown="my-dropdown-id"></i>
                            </a>
                            <ul class="dropdown" id="my-dropdown-id">
                                <li><a href="#">Sign Out</a></li>

                                <li class="separator"></li>
                                <li><a href="#">Seprated link</a></li>
                                <li class="separator"></li>
                                <li><a href="#">One more seprated link.</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
        </nav>

<?php
    } else {
        // Redirect something
    }
    return ob_get_clean();
};
