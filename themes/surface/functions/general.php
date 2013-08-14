<?php

/*   
Component: General
Description: WordPress functions which are the same for every project
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
* _d
* @desc		Dumps output to the screen
* @param	string	$item
* @param	boolean	$exit	Optionally stop the rendering
* @return	void
*/
function _d($item, $exit = true) {
	$message = sprintf('<p><strong>Debug:</strong></p><pre style="border: solid 1px #000; background-color: #F7F7F7; padding: 10px">%s</pre>', print_r($item, true));
	if($exit === true) {
		die($message);
	}
	else {
		echo $message;
	}
}

/**
 * class_count_array
 * @desc	Add first and last classes based on count and total.
 *			Optionally pass in classes to append to.
 * @param	int		$i
 * @param	int		$total
 * @param	array	$classes
 * @return	array 
 */
function class_count_array($i, $total, $classes = array()) {
	if(!is_array($classes)) {
		$classes = array($classes);
	}
	if($i === 1) {
		array_push($classes, 'f');
	}
	if($i === $total) {
		array_push($classes, 'l');
	}
	
	return $classes;
}

/**
 * class_count_clear_array
 * @desc	Returns the classes needed for clearing, based on increment.
 * @param	int		$i
 * @param	array	$classes
 * @return	array
 */
function class_count_clear_array($i, $classes = array()) {
	if(!is_array($classes)) {
		$classes = array($classes);
	}
	
	if($i % 2 === 0 && $i % 3 === 0 && $i % 5 === 0) {
		$classes = array('clear', 'clear-2', 'clear-3', 'clear-5');
	}
	elseif($i % 2 === 0 && $i % 5 === 0) {
		$classes = array('clear', 'clear-2', 'clear-5');
	}
	elseif($i % 2 === 0 && $i % 3 === 0) {
		$classes = array('clear', 'clear-2', 'clear-3');
	}
	elseif($i % 2 === 0) {
		$classes = array('clear', 'clear-2');
	}
	elseif($i % 3 === 0) {
		$classes = array('clear', 'clear-3');
	}
	elseif($i % 5 === 0) {
		$classes = array('clear', 'clear-5');
	}
	
	return $classes;
}

/**
 * _site_get_nav_item
 * @desc	Get the page information from the navigation array.
 * @param	string	$key
 * @param	string	$value
 * @return	mixed
 */
function _site_get_nav_item($key, $value = null) {
	$navigation = _site_get_navigation();

	if(!empty($navigation[$key])) {
		if(!is_null($value)) {
			if(!empty($navigation[$key][$value])) {
				return $navigation[$key][$value];
			}

			return null;
		}

		return $navigation[$key];
	}

	return null;
}

/**
 * _site_get_nav_item_id
 * @desc	Proxy to _site_get_nav_item, to get the page_id.
 * @param	string	$key
 * @return	null|int
 */
function _site_get_nav_item_id($key) {
	return _site_get_nav_item($key, 'page_id');
}

/**
 * Walker_Page_Active
 * @desc	
 */
class Walker_Page_Active extends Walker_Page {

	/**
	 * start_el
	 * @param	string	$output				Passed by reference. Used to append additional content.
	 * @param	object	$page				Page data object.
	 * @param	int		$depth				Depth of page. Used for padding.
	 * @param	int		$current_object_id	Page ID.
	 * @param	array	$args
	 */
	function start_el(&$output, $page, $depth = 0, $args = array(), $current_object_id = 0) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';

		extract($args, EXTR_SKIP);
		$css_class = array('page_item', 'page-item-'.$page->ID);
		if ( !empty($current_object_id) ) {
			$_current_page = get_page( $current_object_id );
			if ( isset($_current_page->ancestors) && in_array($page->ID, (array) $_current_page->ancestors) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $page->ID == $current_object_id ) {
				$css_class[] = 'current_page_item';
				$css_class[] = 'shown';
				$css_class[] = 'active';
			}
			elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
				$css_class[] = 'shown';
				$css_class[] = 'active';
			}
		}
		
		if(_site_is_section($page->ID)) {
			$css_class[] = 'active';
			$css_class[] = 'current_page_item';
		}
		
		if(in_array('active', $css_class) && $page->ID === _site_get_nav_item_id('news')) {
			$current_term	= get_queried_object();
			$categories		= get_terms('category', array(
				'orderby'		=> 'name',
				'order'			=> 'ASC',
				'hierarchical'	=> false,
				'hide_empty'	=> false,
				'exclude'		=> 1,
			));
			$sub_nav = '<ul>';
			foreach($categories as $category) {
				$classes	= array('category', 'category-' . $category->term_id);
				$class		= is_object($current_term) && !empty($current_term->term_id) && $current_term->term_id === $category->term_id ? array_merge($classes, array('active')) : $classes;
				$sub_nav   .= sprintf('<li %s><a href="%s">%s</a></li>', sprintf('class="%s"', implode(' ', $class)), get_term_link($category), $category->name);
			}
			$sub_nav .= '</ul>';
			
			$css_class[] = 'has-children';
			$css_class[] = 'has-children-shown';
		}

		$css_class_string = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_object_id ) );

		$output .= $indent . '<li class="' . $css_class_string . '"><a href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';
		$output .= !empty($sub_nav) ? $sub_nav : '';
		
		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;

			$output .= " " . mysql2date($date_format, $time);
		}
	}
}