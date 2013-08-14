<?php

/*   
Component: Admin
Description: WordPress functions for the admin area
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * _site_admin_action_admin_init_scripts
 * @desc	Adds custom CSS and JavaScript to the admin area.
 */
function _site_admin_action_admin_init_scripts() {
	wp_register_script('admin-app', get_stylesheet_directory_uri() . '/js/app/app.js', array('jquery'), '1.0', true);
	wp_register_script('admin-custom', get_stylesheet_directory_uri() . '/js/app/run.admin.js', array('jquery', 'admin-app', 'jquery-ui-core', 'jquery-ui-datepicker'), '1.0', true);
	wp_enqueue_script('admin-custom');

	wp_register_style('admin-custom', get_stylesheet_directory_uri() . '/css/admin.css', array(), null);
	wp_register_style('admin-jquery-ui-css', get_stylesheet_directory_uri() . '/css/jquery-ui/smoothness/jquery-ui-1.10.3.custom.min.css', array(), null);
	wp_enqueue_style('admin-custom');
	wp_enqueue_style('admin-jquery-ui-css');
}
add_action('admin_init', '_site_admin_action_admin_init_scripts');

/**
 * _site_admin_filter_admin_body_class
 * @desc	Add classes to the admin area.
 * @see		http://www.kevinleary.net/customizing-wordpress-admin-css-javascript/
 * @param	string	$classes
 * @return	string 
 */
function _site_admin_filter_admin_body_class($classes) {
	if(is_admin()) {
		if(isset($_GET['action']) ) {
			$classes .= ' action-' . $_GET['action'];
		}
		if(isset($_GET['post']) ) {
			$classes .= ' post-' . $_GET['post'];
		}
		if(isset($_GET['post_type'])) {
			$classes .= ' post-type-' . $_GET['post_type'];
		}
		
		$post = get_post();
		if(isset($post->post_type) && 'page' === $post->post_type) {
			$classes .= ' page-template-' . str_replace('.', '-', get_page_template_slug());
		}

		$post_query = !empty($_GET['post']) ? $_GET['post'] : null;
		if(isset($post_query)) {
			$current_post_edit = get_post($post_query);
			$current_post_type = $current_post_edit->post_type;
			if(!empty($current_post_type)) {
				$classes .= ' post-type-' . $current_post_type;
			}
		}
	}

	return $classes;
}
add_filter('admin_body_class', '_site_admin_filter_admin_body_class');

/**
 * _site_admin_action_right_now_content_table_end
 * @desc	Add custom post type counts to 'Right Now' dashboard widget.
 */
function _site_admin_action_right_now_content_table_end() {
	$post_types = get_post_types(array(
		'_builtin' => false
	), 'objects');
	
	if(count($post_types) > 0) {
		foreach($post_types as $pt => $args) {
			echo sprintf('<tr><td class="b"><a href="%1$s">%2$s</a></td><td class="t"><a href="%1$s">%3$s</a></td></tr>', 'edit.php?post_type=' . $pt,  wp_count_posts($pt)->publish, $args->labels->name);
		}
	}
}
add_action('right_now_content_table_end', '_site_admin_action_right_now_content_table_end');

/**
 * _site_admin_action_admin_menu_remove
 * @desc	Remove menu items from the admin menu.
 */
function _site_admin_action_admin_menu_remove() {
	$slugs	= array(
		'link-manager.php',
		'edit-comments.php',
	);
	foreach($slugs as $slug) {
		remove_menu_page($slug);
	}
}
add_action('admin_menu', '_site_admin_action_admin_menu_remove');