<?php

/*   
Component: Site
Description: Site specific functions. Most functions only need to be tweaked
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

// include all the function components
$functions = glob(dirname(__FILE__) . '/functions.*.php');
foreach($functions as $function) {
	if(!in_array(basename($function), array('function.php'))) {
		require_once $function;
	}
}

/**
 * thumbnails
 * @desc	General thumbnail sizes. For individual post types, see relavant functions file
 * @hook	add_image_size
 */
add_theme_support('post-thumbnails');
set_post_thumbnail_size(700, 1000, true); // Normal post thumbnails	
add_image_size('slideshow', 960, 500, true);
add_image_size('featured', 240, 150, true);

/**
 * template_is_section
 * @desc	Check what page is set
 * @global	object	$post
 * @param	string	$page
 * @return	boolean 
 */
function template_is_section($page) {
	global $post;
	
	if(is_search()) {
		return false; // no section is active
	}
	
	switch(strtolower($page)) {
		case 'homepage':
		case 'home':
			return is_front_page();
			break;
		
		case 'contact':
			return is_page('contact');
			break;
		
		case 'about':
			return is_page('about');
			break;
		
		case 'gallery':
			return is_page_template('page-galleries.php');
			break;
		
		case 'gallery':
			return get_query_var('gallery');
			break;
	}
	
	return false;
}

/**
 * site_new_excerpt_length
 * @hook	add_filter('excerpt_length');
 * @param	int $length
 * @return	int
 */
function site_new_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'site_new_excerpt_length');

/**
 * Add excerpts to pages
 */
add_post_type_support('page', 'excerpt');

/**
 * register_javascript_css
 * @desc	Register the JavaScript and CSS
 * @hook	add_action('template_redirect');
 */
function site_register_javascript_css() {
	$post_type = get_post_type();

	if(!is_admin()) {
		wp_deregister_script('jquery');
		wp_deregister_script('NextGEN');
		wp_deregister_script('thickbox');
		wp_deregister_script('shutter');
		wp_deregister_script('swfobject');
		wp_deregister_script('events-manager');
		wp_deregister_script('jquery-cycle');
		wp_dequeue_style('NextGEN');
		wp_dequeue_style('shutter');
		wp_dequeue_style('thickbox');
		wp_dequeue_style('events-manager');
		wp_dequeue_style('ngg-slideshow');

		// javascript
		wp_register_script('jquery', get_stylesheet_directory_uri() . '/js/jquery/1.7.1.js', false, '1.7.1', true);
		wp_register_script('plugin.cycle', get_stylesheet_directory_uri() . '/js/jquery/plugin/cycle-2.99.js', false, '2.99', true);
		wp_register_script('plugin.fancybox', get_stylesheet_directory_uri() . '/js/jquery/plugin/fancybox-2.0.4.js', false, '2.0.4', true);
		wp_register_script('app', get_stylesheet_directory_uri() . '/js/app.js', array('jquery'), '1.0', true);
		wp_register_script('site', get_stylesheet_directory_uri() . '/js/site.js', array('jquery', 'app'), '1.0', true);
		
		wp_enqueue_script('jquery');
		
		if(template_is_section('homepage')) {
			wp_enqueue_script('plugin.cycle');
		}
		if(template_is_section('gallery')) {
			wp_enqueue_script('plugin.fancybox');
		}
		
		wp_enqueue_script('app');
		wp_enqueue_script('site');

		// css
		wp_enqueue_style('open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600', false, false);
		wp_enqueue_style('normalize', get_stylesheet_directory_uri() . '/css/normalize.css', false, false);
		wp_enqueue_style('screen', get_stylesheet_directory_uri() . ' /css/screen.css', false, false);
	}
}
add_action('template_redirect', 'site_register_javascript_css');

/**
 * site_stylesheet_directory_uri
 * @desc	Adding CDN URL (if set) and removing the -trade suffix for theme
 * @param	string	$stylesheet_dir_uri
 * @param	string	$stylesheet
 * @param	string	$theme_root_uri
 * @return	string 
 */
function site_stylesheet_directory_uri($stylesheet_dir_uri, $stylesheet, $theme_root_uri) {
	$uri	= $stylesheet_dir_uri;
	$uri	= str_replace(array('-trade'), array(''), $uri);
	
	if(defined('WP_CDN')) {
		$uri = str_replace(constant('WP_SITEURL'), constant('WP_CDN'), $uri);
	}
	
	return $uri;
}
add_action('stylesheet_directory_uri', 'site_stylesheet_directory_uri', 10, 3);

/**
 * site_body_classes
 * @desc	Add extra information to the body class
 * @hook	add_filter('body_class');
 * @param	array	$classes
 * @return	array
 */
function site_body_classes($classes) {
	global $post;
	
	$post_type	= template_get_post_type();
	$taxonomy	= 'category';

	if(!is_array($classes)) {
		$classes = (array) $classes;
	}
	
	if(is_single() || is_archive()) {
		$classes[] = 'blog';
	}
	
	if(is_search()) {
		 // no section is active
		return $classes;
	}
	if(is_404()) {
		 $classes[] = 'page';
	}

	if(template_is_section('contact')) {
		$classes[] = 'contact';
	}
	if(template_is_section('about')) {
		$classes[] = 'about';
	}
	if(template_is_section('homepage')) {
		$classes[] = 'homepage';
	}
	if(template_is_section('gallery')) {
		$classes[] = 'gallery';
	}

	return $classes;
}
add_filter('body_class', 'site_body_classes');

/**
 * register_navigation
 * @desc	Registering custom navigations with WordPress
 * @hook	add_action('init');
 */
function register_navigation() {
	register_nav_menus(array(
		'main'	=> __('Main Navigation'),
	));
}
//add_action('init', 'register_navigation');

/**
 * Walker_Nav_Menu
 * Overwrite end_el function to remove the new line, which causes white space issues with display-inline
 */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
	function end_el(&$output, $item, $depth) {
		$output .= "</li>";
	}
}