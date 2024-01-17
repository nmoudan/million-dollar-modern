<?php
/*
Plugin Name: SearchWP Customizations
Description: Customizations for SearchWP
Version: 1.0.0
*/

// Force enable SearchWP's alternate indexer.
 add_filter( 'searchwp\indexer\alternate', '__return_true' );

// Add all hooks and custom code here.


// @link https://searchwp.com/documentation/multisite/
// Tell SearchWP to search the entire Multisite network when searching on the main site.
// add_filter( 'searchwp\query\args', function( $args, $query ) {
// 	// If this is not site 1, bail out.
// 	if ( 1 !== get_current_blog_id() ) {
// 	  return $args;
// 	}
	

// 	// Search sites with ID 1, 2, 3.
// 	$args['site'] = [1,2,3,4];

// 	// Retain site info in results.
// 	$args['fields'] = 'default';

// 	return $args;
// }, 10, 2 );
function my_searchwp_live_search_posts_per_page() {
	return 30; // return 20 results
}

add_filter( 'searchwp_live_search_posts_per_page', 'my_searchwp_live_search_posts_per_page' );

function my_searchwp_live_search_get_search_form_engine() {
  return 'supplemental';
}

add_filter( 'searchwp_live_search_get_search_form_engine', 'my_searchwp_live_search_get_search_form_engine' );

function my_searchwp_live_search_configs( $configs ) {

	// add an additional config called 'my_config'
	$configs['supplemental_config'] = array(
		'engine' => 'supplemental',
		'input' => array(
			'delay'     => 300,
			'min_chars' => 2,
		),
		'results' => array(
			'position'  => 'top',
			'width'     => 'css',
			'offset'    => array(
				'x' => 0,
				'y' => 0 
			),
		),
	);
	
	return $configs;
}

add_filter( 'searchwp_live_search_configs', 'my_searchwp_live_search_configs' );