<?php

/**
 * Pagemanage Shortcode Controller.
 *
 * @package module/Pagemanage/library/class-Pagemanageshortcode.php
 */

namespace ElementalPlugin\Module\Pagemanage\Library;

/**
 * Class MembershipShortcode - Renders the Membership Shortcode View.
 */
class PagemanageShortCode
{

  
    /**
     * Render shortcode to render Pagemanage Control View
     *
     * @param array|string $attributes List of shortcode params.
     *
     * @return ?string
     */
    public function render_Pagemanage_shortcode($attributes = array()): ?string
    {
        $current_user = wp_get_current_user();

      //  return $this->Pagemanage_shortcode_test($current_user);
    }

    public function re_endpoint(){

       
            add_rewrite_endpoint('sid_route', EP_ROOT);

            //add_rewrite_endpoint('sid_route', EP_PAGES);
          //  add_rewrite_endpoint('sid_route', EP_PERMALINK);
        
   
        }

        public function template_redirect($template)
        {
            echo 'this is here ';
            die();
            return null;
        $file_name = 'signup.php';
       // echo       $template = dirname(__FILE__) . '/templates/' . $file_name;
       // die();
        if ($machinesUrl = get_query_var('sid_route')) 
         {
            if (locate_template($file_name)) {
                // var_dump($machinesUrl, $_GET);
                // $machinesURl contains the url part after example.com/machines
                // e.g. if url is example.com/machines/some/thing/else
                // then $machinesUrl == 'some/thing/else'
                // and params can be retrieved via $_GET

                // after parsing url and calling api, it's just a matter of loading a template:

                // then stop processing
                $template = locate_template($file_name, TRUE, TRUE);
                echo ' Something went wrong  1 if ' . $template;
                die();
             
            } else {
                // Template not found in theme's folder, use plugin's template as a fallback
                  $template = dirname(__FILE__) . '/templates/' . $file_name;
              //  die();
               // $template = locate_template($file_name);
                echo ' Something went wrong 1 else' . $template;
              //  die();
            }

        }else{
            $template = locate_template($file_name, TRUE, TRUE);
            // $template = locate_template( get_template_directory_uri(). '/templates/' . $file_name); //.' Something went wrong ';
            //  return  $template = get_template_directory_uri() . '/templates/' . $file_name;//.' Something went wrong ';
           echo  get_query_var('sid_route');
          echo ' Something went wrong  2 else '. $template;
           die;
          //  $template = 'https://wordpress.test/control';
          //  die();
      
        }
        // $current_user = wp_get_current_user();

        // return $this->Pagemanage_shortcode_test($current_user);
                 return $template;
    }

     
        public function request_filter($vars = []){
               
                    if (isset($vars['sid_route']) && empty($vars['sid_route'])) {
                        $vars['sid_route'] = 'default';
                    }
                    return $vars;
                
            }


    public function process_request()
    {
        global $wp;
        // Check if we're on the correct url
        $current_slug = add_query_arg(array(), $wp->request);
        if ($current_slug !== 'sid_route') {
            return false;
        }

        // Check if it's a valid request.
        $nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING);
        if (!wp_verify_nonce($nonce,  'NONCE_KEY')) {
            //$a = wp_verify_nonce($nonce,  'NONCE_KEY');
           // echo $nonce .' -- ';
            $file_name = 'login.php';
         // $template = locate_template($file_name, TRUE, TRUE);
            $template2 = dirname(__FILE__) . '/templates/' . $file_name;
            die(__('Security check ! '.$template2, 'textdomain'));
        }

        // Do your stuff here
        //
        die('Process completed');
    }

        
        
    /**
     * Page Switch
     * Renders the shortcode to correctly Signup and login.
     *
     * @return string
     */
    public function page_switch(): string
    {

        return do_shortcode('');
    }


    /**
     * Pagemanage Shortcode Worker Function
     * Handles the rendering of the shortcode for Pagemanage Control.
     *
     * @param  object $current_user - The signed in user object.
     * @return ?string
     */
    public function Pagemanage_shortcode_test(object $current_user): ?string
    {
    
        $render = (require __DIR__ . '/../views/view-admin-login.php');
        // phpcs:ignore -- WordPress.Security.EscapeOutput.OutputNotEscaped . Functions already escaped
    //    return $render($current_user);
    }
}
