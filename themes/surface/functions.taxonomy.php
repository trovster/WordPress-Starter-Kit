<?php

/*   
Component: Taxonomy
Description: WordPress taxonomy functions which are the same for every project
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * taxonomy_to_simple_array
 * @desc	Convert a taxonomy object to the slug
 *			For use with array_map to turn in to a single array
 * @example array_map('taxonomy_to_simple_array', get_the_terms($post->ID, 'post_tag'));
 * @param	object $taxonomy
 * @return	string
 */
function taxonomy_to_simple_array($taxonomy) {
	return $taxonomy->slug;
}

/**
 * get_taxonomy_type
 * @param	array	$taxonomy
 * @param	string	$type
 * @return	array
 */
function get_taxonomy_type($taxonomy, $type) {
	$ids = array();
	if(is_array($taxonomy)) {
		foreach($taxonomy as $item) {
			if(is_array($item) && array_key_exists($type, $item)) {
				$ids[] = $item[$type];
			}
			elseif(is_object($item)) {
				$ids[] = $item->{$type};
			}
		}
	}
	return $ids;
}

/**
 * get_taxonomy_ids
 * @desc	A commonly used proxy function to get_taxonomy_type()
 * @param	array	$taxonomy
 * @return	array
 */
function get_taxonomy_ids($taxonomy) {
	return get_taxonomy_type($taxonomy, 'term_id');
}

/**
 * get_taxonomy_safe
 * @desc	A commonly used proxy function to get_taxonomy_type()
 * @param	array	$taxonomy
 * @return	array
 */
function get_taxonomy_safe($taxonomy) {
	return get_taxonomy_type($taxonomy, 'slug');
}

/**
 * get_taxonomy_keys
 * @desc	
 * @param	string	$taxonomy
 * @param	int		$id
 * @param	boolean	$ancestors
 * @return	array 
 */
function get_taxonomy_keys($taxonomy, $id, $ancestors = false) {
	$keys	= array();
	$terms	= get_the_terms($id, $taxonomy);

	if(is_array($terms)) {
		$slugs = array_map('taxonomy_to_simple_array', $terms);

		if($ancestors === true) {
			foreach(array_keys($slugs) as $key) {
				$keys = array_merge($keys, get_taxonomy_keys_by_term($key, $taxonomy));
			}
		}
	}
	
	return $keys;
}

/**
 * get_taxonomy_keys_by_term
 * @desc	
 * @param	int		$term_id
 * @param	string	$taxonomy
 * @return	array
 */
function get_taxonomy_keys_by_term($term_id, $taxonomy = 'category') {
	return array_merge(array($term_id), get_ancestors($term_id, $taxonomy));
}

/**
 * get_taxonomy_based_on_type
 * @desc	Give a default, and list of custom post types
 *			Checks section & sets the correct taxonomy
 * @param	string	$default
 * @param	array	$types
 * @return	string
 */
function get_taxonomy_based_on_type($default, $custom_post_types = array(), $prefix = null) {
	$taxonomy	= $default;
	$prefix		= is_null($prefix) ? '_' . $default : $prefix;
	foreach($custom_post_types as $type) {
		$taxonomy = template_is_section($type) ? $type . $prefix : $taxonomy;
	}
	return $taxonomy;
}
/**
 * get_taxonomy_custom_option
 * @desc	
 * @param	object	$taxonomy
 * @param	string	$option
 * @return	string 
 */
function get_taxonomy_custom_option($taxonomy, $option) {
	return get_option($category->taxonomy . '_' . $category->term_id . '_' . $option);
}

/**
 * get_taxonomy_colour
 * @desc	
 * @param	object	$taxonomy
 * @return	string 
 */
function get_taxonomy_colour($taxonomy) {
	$colour = get_option($taxonomy->taxonomy . '_' . $taxonomy->term_id . '_category_colour');
	if(!empty($colour)) {
		$colour = strpos($colour, '#') === 0 ? $colour : '#' . $colour;
	}
	return $colour;
}