<?php

/*   
Component: Admin
Description: WordPress functions for the admin area
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * surface_admin_custom_js_css
 * @desc	Adds custom CSS and JavaScript to the CMS
 */
function surface_admin_custom_js_css() {
	// custom JS and CSS
	wp_enqueue_script('app', get_stylesheet_directory_uri() . '/js/app.js', array('jquery'), null, true);
	wp_enqueue_script('custom-admin-js', get_stylesheet_directory_uri() . '/js/site.admin.js', array('jquery', 'app'), null, true);
	wp_enqueue_style('custom-admin-css', get_stylesheet_directory_uri() . '/css/admin.css', array(), null);
}
add_action('admin_init', 'surface_admin_custom_js_css');

/**
 * surface_admin_body_class
 * @see		http://www.kevinleary.net/customizing-wordpress-admin-css-javascript/
 * @param	string	$classes
 * @return	string 
 */
function surface_admin_body_class($classes) {
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
		$post_query = !empty($_GET['post']) ? $_GET['post'] : null;
		if(isset($post_query)) {
			$current_post_edit = get_post($post_query);
			$current_post_type = $current_post_edit->post_type;
			if(!empty($current_post_type)) {
				$classes .= ' ';
				$classes .= 'post-type-' . $current_post_type;
			}
		}
	}
	// Return the $classes array
	return $classes;
}
add_filter('admin_body_class', 'surface_admin_body_class');

/**
 * surface_admin_post_types_rightnow
 * @desc Add all your custom post type counts to 'Right Now' dashboard widget
 */
function surface_admin_post_types_rightnow() {
	$post_types = get_post_types(array(
		'_builtin' => false
	), 'objects');
	
	if(count($post_types) > 0) {
		foreach($post_types as $pt => $args) {
			$url = 'edit.php?post_type=' . $pt;
			echo '<tr>';
			echo '<td class="b"><a href="' . $url . '">' . wp_count_posts($pt)->publish . '</a></td>';
			echo '<td class="t"><a href="' . $url . '">' . $args->labels->name . '</a></td>';
			echo '</tr>';
		}
	}
}
add_action('right_now_content_table_end', 'surface_admin_post_types_rightnow');

/**
 * surface_admin_menu_remove_menu
 * @desc	Remove menu items from the admin menu
 */
function surface_admin_menu_remove_menu() {
	$slugs	= array(
		'link-manager.php',
	);
	foreach($slugs as $slug) {
		remove_menu_page($slug);
	}
}
add_action('admin_menu', 'surface_admin_menu_remove_menu');