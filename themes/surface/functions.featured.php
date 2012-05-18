<?php

/*/////////////////////////////////////////////////////////////////////
Featured Boxes
/////////////////////////////////////////////////////////////////////*/

add_action('init',			'featured_init');
add_action('admin_init',	'featured_custom_fields');
//add_action('save_post',	'featured_custom_fields_update'); // uses the general one

/**
 * featured_init
 * @desc	Initiating the "Featured" custom post types with WordPress
 * @hook	add_action('init');
 */
function featured_init() {
	register_post_type('featured', array(
		'labels'	=> array(
			'name'					=> __( 'Featured' ),
			'singular_name'			=> __( 'Featured Box' ),
			'add_new'				=> __( 'Add New Box' ),
			'add_new_item'			=> __( 'Add New Box' ),
			'edit'					=> __( 'Edit Box' ),
			'edit_item'				=> __( 'Edit Box' ),
			'new_item'				=> __( 'New Box' ),
			'view'					=> __( 'View Box' ),
			'view_item'				=> __( 'View Box' ),
			'search_items'			=> __( 'Search Boxs' ),
			'not_found'				=> __( 'No Boxs found' ),
			'not_found_in_trash'	=> __( 'No Boxs found in Trash' ),
			'parent'				=> __( 'Parent Box' ),
		),
		'description'			=> 'Featured one of four boxes on the homepage.',
		'capability_type'		=> 'post',
		'public'				=> true,
		'exclude_from_search'	=> true,
		'show_ui'				=> true,
		'rewrite'				=> false,
		'hierarchical'			=> false,
		'supports'				=> array(
			'title',
			'editor',
			'thumbnail',
			'page-attributes'
		)
	));
}

/**
 * slideshow_pre_get_posts
 * @desc	Restrict posts
 */
function slideshow_pre_get_posts(&$query) {
	$type	= !empty($query->query_vars['post_type']) ? $query->query_vars['post_type'] : false;
	$update	= !is_admin() && !is_preview() && is_string($type) && $type === 'featured';

	if(empty($query->query_vars['meta_query'])) {
		$query->query_vars['meta_query'] = array();
	}
	elseif(!empty($query->query_vars['meta_query']) && !is_array($query->query_vars['meta_query'])) {
		$query->query_vars['meta_query'] = array($query->query_vars['meta_query']);
	}
	
	if($update) {
		$query->set('post_type', 'featured');
	}
	
	$query->set('meta_query', array_filter($query->query_vars['meta_query']));
}
add_action('pre_get_posts', 'slideshow_pre_get_posts');

/**
 * featured_custom_fields_featured
 * @desc	Assigning the custom fields
 * @global	int	$user_ID
 * @hook	add_action('admin_init');
 */
function featured_custom_fields() {
	add_meta_box('specific', 'Featured Specifics', 'featured_custom_fields_featured', 'featured', 'normal', 'high');
}

/**
 * featured_custom_fields_featured
 * @global	object	$post
 */
function featured_custom_fields_featured() {
	post_type_custom_fields_general_page_id();
	post_type_custom_fields_general_link();
}