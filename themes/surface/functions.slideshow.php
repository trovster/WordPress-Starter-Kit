<?php

/*/////////////////////////////////////////////////////////////////////
Slideshow
/////////////////////////////////////////////////////////////////////*/

add_action('init',			'slideshow_init');
add_action('admin_init',	'slideshow_custom_fields');
//add_action('save_post',	'slideshow_custom_fields_update'); // uses the general one

/**
 * slideshow_init
 * @desc	Initiating the "Slideshow" custom post types with WordPress
 * @hook	add_action('init');
 */
function slideshow_init() {
	register_post_type('slideshow', array(
		'labels'	=> array(
			'name'					=> __( 'Slideshow' ),
			'singular_name'			=> __( 'Slideshow' ),
			'add_new'				=> __( 'Add New Image' ),
			'add_new_item'			=> __( 'Add New Image' ),
			'edit'					=> __( 'Edit Image' ),
			'edit_item'				=> __( 'Edit Image' ),
			'new_item'				=> __( 'New Image' ),
			'view'					=> __( 'View Image' ),
			'view_item'				=> __( 'View Image' ),
			'search_items'			=> __( 'Search Images' ),
			'not_found'				=> __( 'No Images found' ),
			'not_found_in_trash'	=> __( 'No Images found in Trash' ),
			'parent'				=> __( 'Parent Image' ),
		),
		'description'			=> 'Rotating Images on the Homepage.',
		'capability_type'		=> 'post',
		'public'				=> true,
		'exclude_from_search'	=> true,
		'show_ui'				=> true,
		'rewrite'				=> false,
		'hierarchical'			=> false,
		'taxonomies'			=> array(),
		'supports'				=> array(
			'title',
		//	'editor',
			'thumbnail',
			'page-attributes'
		)
	));
}

/**
 * featured_pre_get_posts
 * @desc	Restrict posts
 */
function featured_pre_get_posts(&$query) {
	$type	= !empty($query->query_vars['post_type']) ? $query->query_vars['post_type'] : false;
	$update	= !is_admin() && !is_preview() && is_string($type) && $type === 'slideshow';

	if(empty($query->query_vars['meta_query'])) {
		$query->query_vars['meta_query'] = array();
	}
	elseif(!empty($query->query_vars['meta_query']) && !is_array($query->query_vars['meta_query'])) {
		$query->query_vars['meta_query'] = array($query->query_vars['meta_query']);
	}
	
	if($update) {
		$query->set('post_type', 'slideshow');
	}
	
	$query->set('meta_query', array_filter($query->query_vars['meta_query']));
}
add_action('pre_get_posts', 'featured_pre_get_posts');

/**
 * slideshow_custom_fields
 * @desc	Assigning the custom fields
 * @global	int	$user_ID
 * @hook	add_action('admin_init');
 */
function slideshow_custom_fields() {
	add_meta_box('specific', 'Slideshow Specifics', 'slideshow_custom_fields_specific', 'slideshow', 'normal', 'high');
}

/**
 * slideshow_custom_fields_specific
 * @global	object	$post
 */
function slideshow_custom_fields_specific() {
	global $post;

	$custom			= get_post_custom($post->ID);
	$fields			= array();

	foreach($fields as $field) {
		$value	= template_get_custom_field($custom, $field);
		$label	= str_replace('_', ' ', $field);
		$label	= ucwords($label);
		$id		= $field;
		$name	= 'custom_' . $field;
		echo template_custom_field($id, $name, $label, $value);
	}
	
	post_type_custom_fields_general_page_id();
	post_type_custom_fields_general_link();
}

/**
 * new_custom_slideshow_columns
 * @param	array $columns
 * @return	array
 */
function new_custom_slideshow_columns($columns) {
	$date		= $columns['date'];
	$categories	= $columns['categories'];
	unset($columns['date']);
	unset($columns['categories']);
	
	$columns['slideshow_image']		= 'Image';
	$columns['slideshow_link']		= 'Link';
	$columns['date']				= $date;

	return $columns;
}
add_filter('manage_edit-slideshow_columns', 'new_custom_slideshow_columns');

/**
 * manage_slideshow_columns
 * @desc	Populate the row values for the new columns
 * @desc	column row data
 * @param	string	$column
 * @param	int		id
 */
function manage_slideshow_columns($column, $id) {
	$custom = get_post_custom($id);

	switch($column) {
		case 'slideshow_image':
			if (has_post_thumbnail()) {
				echo the_post_thumbnail(array(80, 60));
			}
			break;
			
		case 'slideshow_link':
			$link = get_custom_link_href($custom);
			echo !empty($link) ? '<a href="' . $link . '">' . template_short_url($link) . '</a>' : '-';
			break;
	}
}
add_action('manage_posts_custom_column', 'manage_slideshow_columns', 10, 2);