<?php

/**
 * Pagemanage Management Package
 * Managing helper functions for Pagemanage
 *
 * @package ElementalPlugin\Module\Pagemanage
 */

namespace ElementalPlugin\Module\Pagemanage;

use ElementalPlugin\Library\Factory;

use ElementalPlugin\Module\Pagemanage\Library\PagemanageShortCode;

/**
 * Class Pagemanage - Main Control Function Class for Pagemanage.
 */
class PageManage
{

    public function __construct()
    {
        //   add_action('init', array(Factory::get_instance(PagemanageShortCode::class), 're_endpoint'));


        // add_action(
        //     'init',
        //     function () {
        //         add_rewrite_rule('somethings', 'sign-up.php?p=14','top');

        //         flush_rewrite_rules( );
        //         //add_rewrite_endpoint('sid_route', EP_PAGES);
        //         //  add_rewrite_endpoint('sid_route', EP_PERMALINK);
        //     }
        // );
    }
  



    /**
     * Runtime Shortcodes and Setup
     * Required for Normal Runtime.
     */
    public function init(): void
    {


//         add_action(
//             'init',
//             function () {
//                 add_rewrite_endpoint('sid_route', EP_ROOT);

//                 //add_rewrite_endpoint('sid_route', EP_PAGES);
//                 //  add_rewrite_endpoint('sid_route', EP_PERMALINK);
//             }

// //            flush_rewrite_rules();
//         );

        //   die();
       //  add_shortcode('login_page', array(Factory::get_instance(PagemanageShortCode::class), 'render_Pagemanage_shortcode'));
        // Factory::get_instance(PagemanageRender::class)->init();
        //    Factory::get_instance(PagemanageShortCode::class)->re_endpoint();

       //  add_action( 'template_redirect',  Factory::get_instance(PagemanageShortCode::class)->template_redirect()); //, 0);
    // add_filter('query_vars', function ($query_vars) {
    //         $query_vars[] = 'sid_route';
    //         return $query_vars;
    //     });
    //     add_filter('request', function ($vars = []) {
    //         if (isset($vars['sid_route']) && empty($vars['sid_route'])) {
    //             $vars['sid_route'] = 'default';
    //         }
    //         return $vars;
    //     });
    //     add_action('template_redirect', array(Factory::get_instance(PagemanageShortCode::class), 'template_redirect'));

    //    //  add_action('template_redirect', array(Factory::get_instance(PagemanageShortCode::class), 'process_request'));
    //     add_filter(
    //         'request',
    //         array(Factory::get_instance(PagemanageShortCode::class), 'request_filter')
    //     );
         
    }
    
    /**
     * Activate Functions for Pagemanage Module.
     */
    public function activate(): void
    {
  
    }

    /**
     * De-Activate Functions for Pagemanage Module.
     */
    public function de_activate(): void
    {
    }


}
