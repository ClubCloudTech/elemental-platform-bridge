<?php

/**
 * handle edit user form for Site.
 *
 * @package elemental/membership/library/class-edituser.php
 */

// phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase -- This parameter is set in upstream code and not in ours. Can't move to snake case.
namespace ElementalPlugin\Module\Membership\Library;

/**
 * Class Account EditUser - Handle the Edit User Screen.
 */
class Edituser
{

    /**
     * Edite User FormHandler .
     *
     * @return ?string
     */
    public function edit_user_handler(array $formData ,object $current_user, string $source): string
    {
        $error = array();
        if ('POST' == $_SERVER['REQUEST_METHOD']  && $formData['update-user'] == 'update-user') {

            if (!empty($formData['user_email'])) {
                if (!is_email(esc_attr($formData['user_email'])))
                $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
                elseif (email_exists(esc_attr($formData['user_email'])) != $current_user->id)
                    $error[] = __('This email is already used by another user.  try a different one.', 'profile');
                else {
                    wp_update_user(array('ID' => $current_user->ID, 'user_email' => esc_attr($formData['user_email'])));
                }
            }
            if (!empty($formData['display_name'])) {
                wp_update_user(array('ID' => $current_user->ID, 'display_name' => esc_attr($formData['display_name'])));
            }
            if (!empty($formData['user_status'])) {
                wp_update_user(array('ID' => $current_user->ID, 'user_status' => esc_attr($formData['user_status'])));
            }

            if (!empty($formData['firstname']))
            update_user_meta($current_user->ID, 'first_name', esc_attr($formData['firstname']));
            if (!empty($formData['lastname']))
            update_user_meta($current_user->ID, 'last_name', esc_attr($formData['lastname']));

            /* Redirect so the page will show updated info.*/
            if (count($error) == 0) {
                // do something here --  
                //action hook for plugins and extra fields saving
                //  do_action('edit_user_profile_update', $current_user->ID);
                //  wp_redirect('https://wordpress.test/edit-user/');

                // exit;

            }
        }
        $render = (require __DIR__ . '/../views/loginviews/view-loginadmin.php');
        // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
        return $render($current_user);
    }

 
}
