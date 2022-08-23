<?php

/**

 * @package sid_test_rewrite
 */

/*
Plugin Name: sid_test_rewrite Anti-Spam

Plugin URI: https://sid_test_rewrite.com/

Description: Used by millions, sid_test_rewrite is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the sid_test_rewrite plugin and then go to your sid_test_rewrite Settings page to set up your API key.

Version: 4.1.7

Author: Automattic

Author URI: https://automattic.com/wordpress-plugins/

License: GPLv2 or later

Text Domain: sid_test_rewrite

*/

function tutsplus_register_taxonomy() {
	// books
	$labels = array(
		'name'          => __( 'Genres', 'tutsplus' ),
		'singular_name' => __( 'Genre', 'tutsplus' ),
		'search_items'  => __( 'Search Genres', 'tutsplus' ),
		'all_items'     => __( 'All Genres', 'tutsplus' ),
		'edit_item'     => __( 'Edit Genre', 'tutsplus' ),
		'update_item'   => __( 'Update Genres', 'tutsplus' ),
		'add_new_item'  => __( 'Add New Genre', 'tutsplus' ),
		'new_item_name' => __( 'New Genre Name', 'tutsplus' ),
		'menu_name'     => __( 'Genres', 'tutsplus' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'sort'              => true,
		'args'              => array( 'orderby' => 'term_order' ),
		'rewrite'           => array( 'slug' => 'genres' ),
		'show_admin_column' => true,
		'show_in_rest'      => true,

	);

	register_taxonomy( 'tutsplus_genre', array( 'tutsplus_movie' ), $args );
}
add_action( 'init', 'tutsplus_register_taxonomy' );


// require_once(ABSPATH . 'wp-config.php');
// require_once(ABSPATH . 'wp-includes/wp-db.php');
// require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

// add_action('admin_init', function () {
// add_rewrite_endpoint('sid_test_rewrite', EP_ROOT);
// });

// add_rewrite_endpoint('sid_test_rewrite', EP_ROOT);
// add_action('template_redirect', function () {
// if ($sid_test_rewriteUrl = get_query_var('sid_test_rewrite')) {
// var_dump($sid_test_rewriteUrl, $_GET);
// $sid_test_rewriteURl contains the url part after example.com/sid_test_rewrite
// e.g. if url is example.com/sid_test_rewrite/some/thing/else
// then $sid_test_rewriteUrl == 'some/thing/else'
// and params can be retrieved via $_GET

// after parsing url and calling api, it's just a matter of loading a template:
// locate_template('singe-machine.php', TRUE, TRUE);
// $current_user = wp_get_current_user();
// locate_template('sign-up.php', TRUE, TRUE);
// return $this->Pagemanage_shortcode_test($current_user);
// then stop processing
// die();
// }
// });
