<?php

/*   
Component: Admin
Description: WordPress functions for the admin area
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * admin_custom_js_css
 * @desc	Adds custom CSS and JavaScript to the CMS
 */
function admin_custom_js_css() {
	// custom JS and CSS
	wp_enqueue_script('app', get_stylesheet_directory_uri() . '/js/app.js', array('jquery'), null, true);
	wp_enqueue_script('custom-admin-js', get_stylesheet_directory_uri() . '/js/site.admin.js', array('jquery', 'app'), null, true);
	wp_enqueue_style('custom-admin-css', get_stylesheet_directory_uri() . '/css/admin.css', array(), null);
}
add_action('admin_init', 'admin_custom_js_css');

/**
 * base_admin_body_class
 * @see		http://www.kevinleary.net/customizing-wordpress-admin-css-javascript/
 * @param	string	$classes
 * @return	string 
 */
function base_admin_body_class($classes) {
	if(is_admin()) {
		// Current action
		if(isset($_GET['action']) ) {
			$classes .= ' action-' . $_GET['action'];
		}
		// Current post ID
		if(isset($_GET['post']) ) {
			$classes .= ' post-' . $_GET['post'];
		}
		// New post type & listing page
		if(isset($_GET['post_type'])) {
			$classes .= ' post-type-' . $_GET['post_type'];
		}
			
		// Editting a post type
		$post_query = $_GET['post'];
		if(isset($post_query)) {
			$current_post_edit = get_post($post_query);
			$current_post_type = $current_post_edit->post_type;
			if(!empty($current_post_type)) {
				$classes .= ' ';
				$classes .= 'post-type-'.$current_post_type;
			}
		}
	}
	// Return the $classes array
	return $classes;
}
add_filter('admin_body_class', 'base_admin_body_class');