<?php // add_filter('show_admin_bar', '__return_false');

/*   
Component: Site
Description: Site specific functions.
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * =include
 * @desc	Include all the function components.
 */
$functions = glob(dirname(__FILE__) . '/functions/*.php');
foreach($functions as $function) {
	if(!in_array(basename($function), array('function.php'))) {
		require_once $function;
	}
}

/**
 * =include
 * @desc	Include Classy.
 */
// include all the custom post types
$cpts = glob(dirname(__FILE__) . '/Classy/*.php');
foreach($cpts as $cpt) {
	if(!in_array(basename($cpt), array('_cpt.php'))) {
		require_once $cpt;
	}
}

/**
 * Theme Support Options
 */
add_theme_support('post-thumbnails');

/**
 * _site_action_wp_enqueue_scripts
 * @desc	Queue stylesheets and JavaScript used on the project.
 *			Dequeue commonly added scripts.
 */
function _site_action_wp_enqueue_scripts() {
	if(!is_admin()) {
		// de-register unwanted scripts
		wp_deregister_script('jquery');

		// register javascript
		wp_register_script('jquery', get_stylesheet_directory_uri() . '/js/jquery/1.9.1.js', false, '1.9.1', false);
//		wp_register_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=false', array(), '1.0', true);
		
		// app
		wp_register_script('app', get_stylesheet_directory_uri() . '/js/app/app.js', array('jquery'), '1.0', true);
		wp_register_script('app.options', get_stylesheet_directory_uri() . '/js/app/options.js', array('jquery', 'app'), '1.0', true);
		wp_register_script('app.models', get_stylesheet_directory_uri() . '/js/app/models.js', array('jquery', 'app'), '1.0', true);
		wp_register_script('app.run', get_stylesheet_directory_uri() . '/js/app/run.js', array('jquery', 'app'), '1.0', true);
		
		// plugins
		wp_register_script('plugin.bxslider', get_stylesheet_directory_uri() . '/js/jquery/plugin/bxslider-4.1.1.js', array('jquery'), '4.1.1', true);
//		wp_register_script('plugin.fancybox', get_stylesheet_directory_uri() . '/js/jquery/plugin/fancybox-2.1.5.js', array('jquery'), '2.1.5', true);
//		wp_register_script('plugin.google-map', get_stylesheet_directory_uri() . '/js/jquery/plugin/google-map-1.0.js', array('jquery'), '1.0', true);
		
		// enqueue javascript
		wp_enqueue_script('jquery');
		if(_site_is_section('homepage')) {
			wp_enqueue_script('plugin.bxslider');
		}
		if(_site_is_section('contact')) {
//			wp_enqueue_script('google-maps');
//			wp_enqueue_script('plugin.google-map');
		}

		wp_enqueue_script('app');
		wp_enqueue_script('app.options');
		wp_enqueue_script('app.models');
		wp_enqueue_script('app.run');
		
		// register css
//		wp_register_style('font', 'http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700', array());
		wp_register_style('normalize', get_template_directory_uri() . '/css/normalize.css', array(), '2.1.0');
		wp_register_style('screen', get_template_directory_uri() . '/css/screen.css', array('normalize'));
		wp_register_style('responsive', get_template_directory_uri() . '/css/responsive.css', array('screen'));
		
		// enqueue css
		wp_enqueue_style('screen');
		wp_enqueue_style('responsive');
	}
}
add_action('wp_enqueue_scripts', '_site_action_wp_enqueue_scripts');

/**
 * _site_action_remove_nextgen_css_js
 * @desc	Remove all the CSS and JS added by NextGen Gallery
 */
function _site_action_remove_nextgen_css_js() {
	if(!is_admin()) {
		// remove NextGen Gallery CSS and JS
		wp_deregister_style('nggallery');
		wp_deregister_style('nextgen_widgets_style');
		wp_deregister_style('nextgen_basic_thumbnails_style');
		wp_deregister_style('nextgen_pagination_style');

		wp_deregister_script('ngg_common');
		wp_deregister_script('piclens');
		wp_deregister_script('nextgen-basic-thumbnails-ajax-pagination');
		wp_deregister_script('photocrati-nextgen_basic_thumbnails');
	}
}
add_action('wp_footer', '_site_action_remove_nextgen_css_js');

/**
 * _site_get_navigation
 * @desc	The primary navigation.
 * @param	string		$type
 * @return	array
 */
function _site_get_navigation($type = 'main') {
	$navigation = array(
		'home' => array(
			'text'			=> 'Home',
			'href'			=> '/',
			'class'			=> array('home'),
			'page_id'		=> 2,
		),
		'cloud-computing' => array(
			'text'			=> 'Cloud Computing',
			'href'			=> '/cloud-computing/',
			'class'			=> array('cloud-computing'),
			'page_id'		=> 4,
		),
		'virtualisation' => array(
			'text'			=> 'Virtualisation',
			'href'			=> '/virtualisation/',
			'class'			=> array('virtualisation'),
			'page_id'		=> 5,
		),
		'professional-services' => array(
			'text'			=> 'Professional Services',
			'href'			=> '/professional-services/',
			'class'			=> array('professional-services'),
			'page_id'		=> 6,
		),
		'it-support' => array(
			'text'			=> 'IT Support',
			'href'			=> '/it-support/',
			'class'			=> array('it-support'),
			'page_id'		=> 7,
		),
		'company' => array(
			'text'			=> 'Company',
			'href'			=> '/company/',
			'class'			=> array('company'),
			'page_id'		=> 8,
		),
	);
	
	if ($type === 'main') {
		unset($navigation['home']);
	}
	if ($type === 'sitemap') {}
	
	if ($type === 'extra') {
		$navigation = array(
			'sitemap' => array(
				'text'			=> 'Sitemap',
				'href'			=> '/sitemap/',
				'class'			=> array('sitemap'),
				'page_id'		=> 10,
			),
			'privacy-legal' => array(
				'text'			=> 'Privacy & Legal',
				'href'			=> '/privacy-legal/',
				'class'			=> array('privacy-legal'),
				'page_id'		=> 11,
			),
		);
	}
	
	return $navigation;
}


/**
 * _site_is_section
 * @desc	Check what page is currently active.
 * @global	object	$post
 * @param	string	$page
 * @return	boolean 
 */
function _site_is_section($page) {
	global $post;
	
	if(is_search()) {
		return false; // no section is active
	}
	
	switch(strtolower($page)) {
		case 2:
		case 'homepage':
		case 'home':
			return is_front_page();
			break;
		
		case 4:
		case 'cloud-computing':
			return (is_object($post) && $post->ID === 4) || (!empty($post->ancestors) && is_array($post->ancestors) && in_array(4, $post->ancestors)) || is_page('cloud-computing');
			break;
		
		case 5:
		case 'virtualisation':
			return (is_object($post) && $post->ID === 5) || (!empty($post->ancestors) && is_array($post->ancestors) && in_array(5, $post->ancestors)) || is_page('virtualisation');
			break;
		
		case 6:
		case 'professional-services':
			return (is_object($post) && $post->ID === 6) || (!empty($post->ancestors) && is_array($post->ancestors) && in_array(6, $post->ancestors)) || is_page('professional-services');
			break;
		
		case 7:
		case 'it-support':
			return (is_object($post) && $post->ID === 7) || (!empty($post->ancestors) && is_array($post->ancestors) && in_array(7, $post->ancestors)) || is_page('it-support');
			break;
		
		case 8:
		case 'company':
			return (is_object($post) && $post->ID === 8) || (!empty($post->ancestors) && is_array($post->ancestors) && in_array(8, $post->ancestors)) || is_page('company') || _site_is_section('news');
			break;
		
		case 'news':
			return is_home() || is_archive() || is_single();
			break;
	}
	
	return false;
}

/**
 * _site_filter_body_class
 * @desc	Add extra information to the body class.
 * @hook	add_filter('body_class');
 * @param	array	$classes
 * @return	array
 */
function _site_filter_body_class($classes) {

	if(!is_array($classes)) {
		$classes = (array) $classes;
	}
	
	if(is_search()) {
		return $classes; // no section is active
	}
	if(is_404()) {
		 $classes[] = 'page';
	}
	if(_site_is_section('homepage')) {
		$classes[] = 'homepage';
	}
	if(_site_is_section('contact')) {
		$classes[] = 'page-contact';
	}
	
	return $classes;
}
add_filter('body_class', '_site_filter_body_class');

/**
 * _site_filter_gform_field_css_class
 * @param string	$css_class
 * @param array		$field
 * @param array		$form
 * @return string
 */
function _site_filter_gform_field_css_class($css_class, $field, $form) {
	switch($field['type']) {
		case 'checkbox':
			$css_class .= ' gfield_checkbox';
			break;
		
		case 'select':
			$css_class .= ' gfield_select';
			break;
	}
	
	return $css_class;
}
add_filter('gform_field_css_class', '_site_filter_gform_field_css_class', 10, 3);