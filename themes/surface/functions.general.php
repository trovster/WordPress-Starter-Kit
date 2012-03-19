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
* @desc		Dumps output to the screen and exits
* @param	string	$item
* @param	boolean	$exit
* @return	void
*/
function _d($item, $exit = true) {
	echo '<p><strong>Debug:</strong></p>';
	echo '<pre style="border: solid 1px #000; background-color: #F7F7F7; padding: 10px">' . print_r($item, true) . '</pre>';
	if($exit === true) {
		exit;
	}
}

/**
 * template_pre_loop
 * @desc	Used before a custom loop
 *			Parameter is the WP_Query options for the new loop
 *			Saves the original query and post data
 *			Returns the new query, along with the original wp_query and post
 * @param	array	$options
 * @return	array 
 */
function template_pre_loop($options) {
	global $wp_query, $post;
	
	$original_post		= null;
	$original_wp_query	= null;
	
	if(!empty($post)) {
		$original_post = clone $post;
	}
	if(!empty($wp_query)) {
		$original_wp_query = clone $wp_query;
	}
	
	$wp_query = new WP_Query($options);
	
	return compact('wp_query', 'original_wp_query', 'original_post');
}

/**
 * template_post_loop
 * @desc	Used after a custom loop
 *			Parameter is the original array saved from the pre loop function
 *			Resets the query and post data
 *			Returns the original wp_query and post data
 * @param	array	$original
 * @return	array 
 */
function template_post_loop($original) {
	global $wp_query, $post;
	
	extract($original);
	
	if(!empty($original_wp_query)) {
		$wp_query = clone $original_wp_query;
	}
	if(!empty($original_post)) {
		$post = clone $original_post;
	}
	
	wp_reset_query();
	
	return compact('wp_query', 'post');
}

/**
 * new_excerpt_more
 * @hook	add_filter('excerpt_more');
 * @param	string	$more
 * @return	string
 */
function new_excerpt_more($more) {
	return '…';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * new_excerpt_length
 * @hook	add_filter('excerpt_length');
 * @param	int $length
 * @return	int
 */
function new_excerpt_length($length) {
	return 60;
}
add_filter('excerpt_length', 'new_excerpt_length');

/**
 * disable_all_widgets
 * @hook	add_filter('sidebars_widgets');
 * @param	array	$sidebars_widgets
 * @return	array
 */
function disable_all_widgets($sidebars_widgets) {
		return array(false);
}
add_filter('sidebars_widgets', 'disable_all_widgets');

/**
 * remove_page_from_query_string
 * @desc	Fixes WordPress pagination issues
 * @see		http://barefootdevelopment.blogspot.com/2007/11/fix-for-wordpress-paging-problem.html
 * @hook	add_filter('request');
 * @param	array $query_string
 * @return	array
 */
function remove_page_from_query_string($query_string){
	if(isset($query_string['name']) && $query_string['name'] == 'page' && isset($query_string['page'])) {
		unset($query_string['name']);
		// 'page' in the query_string looks like '/2', so split it out
		list($delim, $page_index) = split('/', $query_string['page']);
		$query_string['paged'] = $page_index;
	}
	return $query_string;
}
add_filter('request', 'remove_page_from_query_string');

/**
 * relative_permalinks
 * @see http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
 * @hook	add_filter('the_permalink');
 * @param	string	$input
 * @return	string
 */
function relative_permalinks($input) {
	return preg_replace('!http(s)?://' . $_SERVER['SERVER_NAME'] . '/!', '/', $input);
}
add_filter('the_permalink', 'relative_permalinks');

/**
 * remove_img_titles()
 * @desc	Removes title from images
 * @hook	add_filter('the_content');
 * @hook	add_filter('post_thumbnail_html');
 * @hook	add_filter('wp_get_attachment_image');
 * @param	string	$text
 * @return	string 
 */
function remove_img_titles($text) {
	$result = array();
	preg_match_all('#title="[^"]*"#U', $text, $result);

	foreach($result[0] as $img_tag) {
		$text = str_replace($img_tag, '', $text);
	}

	return $text;
}
add_filter('the_content', 'remove_img_titles', 1000);
add_filter('post_thumbnail_html', 'remove_img_titles', 1000);
add_filter('wp_get_attachment_image', 'remove_img_titles', 1000);

/**
 * @desc get rid of unwanted stuff from the header
 */
remove_action('wp_head', 'feed_links_extra', 3);
//remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wpp_print_stylesheet');

/**
 * is_tree
 * @desc	Parent page check, useful for navigations
 * @global	object $post
 * @param	int $pid
 * @return	boolean
 */
function is_tree($pid) {
	global $post;
	if($post->post_parent == $pid || is_page($pid) || get_top_level_id(get_the_id()) === $pid) {
		return true;
	}
	else {
		return false;
	}
};

/**
 * get_top_level_id
 * @global	object	$post
 * @param	int		$post_id
 * @param	int		$parent_id
 * @param	int		$level
 * @return	int
 */
function get_top_level_id($post_id, $parent_id=NULL, $level=1) {
	global $post;

	$a		= get_post_ancestors($post_id);
	$query	= array(
		'post_parent'	=> $post_id,
		'post_type'		=> 'page'
	);
	$posts	= new WP_Query($query);
	$root	= count($a) - $level;
	$pid	= array_key_exists($root, $a) ? $a[$root] : $parent_id;
	$pid	= ($posts->have_posts() && empty($pid)) ? $post_id : $pid;

	return (int) $pid;
}

/**
 * get_second_level_id
 * @param	int	$post_id
 * @param	int	$parent_id
 * @return	int
 */
function get_second_level_id($post_id, $parent_id=NULL) {
	return get_top_level_id($post_id, $parent_id, 2);
}

/**
 * catch_that_image
 * @desc	Finds the first image in post and returns an empty if no image
 * @global	object	$post
 * @return	string
 */
function catch_that_image() {
	global $post;

	$first_img = '';

	ob_start();
	ob_end_clean();

	$output		= preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img	= $matches [1][0];

	if(empty($first_img)){
		$first_img = '';
	}

	return $first_img;
}

/**
 * get_custom_link_href
 * @desc	
 * @param	array	$custom
 * @param	mixed	$default
 * @return	mixed
 */
function get_custom_link_href($custom, $default = false) {
	$href = $default;
	
	if((isset($custom['custom_link_url']) && !empty($custom['custom_link_url'][0])) ||
	   (isset($custom['custom_page_id']) && !empty($custom['custom_page_id'][0]))
	) {
		if(isset($custom['custom_page_id']) && !empty($custom['custom_page_id'][0])) {
			$href = get_permalink($custom['custom_page_id'][0]); // get the page link by page id
		}
		else {
			$href = $custom['custom_link_url'][0];
		}
	}
	
	return $href;
}


/*/////////////////////////////////////////////////////////////////////
	Custom Post / Admin Functions
/////////////////////////////////////////////////////////////////////*/

/**
 * template_custom_field
 * @desc	
 * @param	string	$id
 * @param	string	$name
 * @param	string	$label
 * @param	string	$value
 * @param	string	$class
 * @param	string	$type
 * @return string 
 */
function template_custom_field($id, $name, $label, $value, $class = '', $type = null) {
	$type  = is_null($type) ? 'text' : $type;
	
	$html  = '';
	$html .= '<p><label for="' . $id . '">' . $label . ':</label><br />';
	$html .= '<input style="width:90%;" type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '"' . (!empty($class) ? ' class="' . $class . '"' : '') . ' /></p>';
	return $html;
}

/**
 * post_type_custom_fields_general_featured
 * @desc	Video featured custom fields
 * @global	object	$post
 */
function post_type_custom_fields_general_featured() {
	global $post;

	$custom			= get_post_custom($post->ID);
	$is_featured	= is_custom_boolean($custom, 'custom_is_featured');

	echo '<p>You can set ONE item as “featured”?..</p>';

	echo '<p class="checkbox" style="padding-top: 5px;">';
	echo '<input type="hidden" name="custom_is_featured" value="false" />';
	echo '<input style="margin-right: 5px;" id="custom_is_featured" value="true" type="checkbox" name="custom_is_featured"' . (($is_featured) ? ' checked="checked"' : '') . ' />';
	echo '<label for="custom_is_featured" style="font-weight: bold;">Set as Featured?</label>';
	echo '</p>';

	echo '<p>Note, there can only be one item featured
	<em>if more than one item is set as featured,
	<strong>the first</strong> will be used</em>.</p>';
}

/**
 * post_type_custom_fields_general_page_id
 * @desc	Custom post type to associate WordPress pages
 * @global	object	$post
 */
function post_type_custom_fields_general_page_id($id = 'custom_page_id', $label = 'Select page') {
	global $post;

	$i			= 0;
	$custom		= get_post_custom($post->ID);
	$pages		= array();
	$pageId		= template_get_custom_field($custom, $id, '');

	echo '<p><label for="' . $id . '">' . $label . ':</label><br />';
	echo wp_dropdown_pages(array(
		'depth'				=> 0,
		'child_of'			=> 0,
		'selected'			=> !empty($pageId) ? $pageId : 0,
		'echo'				=> false,
		'name'				=> $id,
		'show_option_none'	=> 'None',
	));
}

/**
 * post_type_custom_select
 * @param	string	$custom_post_type
 * @param	string	$option // the post property to show in the <option></option>
 * @param	string	$value // current value, if present
 * @param	string	$name
 * @param	string	$id
 * @param	string	$default // text that is the default, none selected option
 * @return	string	$html
 */
function post_type_custom_select($custom_post_type, $option, $value, $name, $id = null, $default = 'Select...') {
	$html	= '';
	$id		= !is_string($id) ? $name : $id; // general, id the same as name

	// find the posts based on the custom_post_type
	$custom_posts	= get_posts(array(
		'numberposts'	=> -1,
		'orderby'		=> 'post_title',
		'order'			=> 'ASC',
		'post_type'		=> $custom_post_type
	));

	$html .= '<select name="' . $name . '" id="' . $id . '" style="min-width: 200px;">';
	$html .= '<option value="">' . attribute_escape(__($default)) . '</option>';
	foreach($custom_posts as $custom_post) {
		$selected = ($custom_post->ID == $value) ? ' selected="selected"' : '';
		$html .= '<option value="' . $custom_post->ID . '"' . $selected . '>' . $custom_post->{$option} . '</option>';
	}
	$html .= '</select>';

	return $html;
}

/**
 * post_type_custom_fields_general_link
 * @desc	General link custom post type
 * @global	object	$post
 */
function post_type_custom_fields_general_link($id = 'custom_link_url', $label = 'Link URL') {
	global $post;

	$custom		= get_post_custom($post->ID);
	$value		= template_get_custom_field($custom, $id, '');
	
	echo template_custom_field($id, $id, $label, $value);
}

/**
 * post_type_custom_fields_update
 * @desc	All custom fields are saved here.
 *			Note: Custom fields must be prefixed with custom_ to be saved automatically
 * @hook	add_action('save_post');
 * @global	object	$post
 * @param	int		$post_id
 * @return	int
 */
function post_type_custom_fields_update($post_id) {
	global $post;

	// check user permissions / capabilities
	if($_POST['post_type'] == 'page') {
		if(!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	}
	else {
		if(!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	}

	// authentication passed, save data
	// cycle through each posted meta item and save
	// by default only saves custom fields which are prefixed with custom_
	foreach($_POST as $key => $value) {
		if(strpos($key, 'custom_') !== false) {
			$current_data	= get_post_meta($post_id, $key, true);
			$new_data		= !empty($_POST[$key]) ? $_POST[$key] : null;

			if(is_null($new_data)) {
				delete_post_meta($post_id, $key);
			}
			else {
				// add_post_meta is called if not already set
				// @see http://codex.wordpress.org/Function_Reference/update_post_meta
				
				if(strpos($key, 'custom_date_') !== false) {
					$new_data = strtotime($new_data); // convert to timestamp
				}
				
				update_post_meta($post_id, $key, $new_data, $current_data);
			}
		}
	}

	return $post_id;
}
add_action('save_post',	'post_type_custom_fields_update');

/**
 * is_custom_boolean
 * @desc	Standardises an "is_featured" custom field in to boolean
 * @param	array	$custom
 * @return	boolean
 */
function is_custom_boolean($custom, $key) {
	if(!empty($custom[$key][0]) && $custom[$key][0] === 'true') {
		return true;
	}
	return false;
}


/*/////////////////////////////////////////////////////////////////////
General Template Functions
/////////////////////////////////////////////////////////////////////*/

/**
 * template_wp_list_pages
 * @desc	Tempalte function for wp_list_pages() but adds first and last classes
 * @param	array	$query
 * @param	string	$first
 * @param	string	$last
 */
function template_wp_list_pages($query, $first='f', $last='l') {
	if(is_array($first)) {
		$first = template_add_class($first, false);
	}
	elseif(!is_string($first)) {
		$first = 'f';
	}
	if(is_array($last)) {
		$last = template_add_class($last, false);
	}
	elseif(!is_string($last)) {
		$last = 'f';
	}

	$list = wp_list_pages($query);
	$list = preg_replace('/class="page_item/', 'class="page_item ' . $first, $list, 1);
	$list = strrev(preg_replace('/meti_egap"=ssalc/', strrev($last) . ' meti_egap"=ssalc', strrev($list), 1));

	if(isset($query['echo']) && $query['echo'] == '1') {
		echo $list;
	}
	else {
		return $list;
	}
}

/**
 * template_post_type_join_id
 * @param	string	$custom_post_type
 * @param	int		$id
 * @param	string	$key
 * @param	int		$limit
 * @return	array|object|boolean(false)
 */
function template_post_type_join_id($custom_post_type, $id, $key, $limit = -1) {
	$posts = get_posts(array(
		'posts_per_page'	=> $limit,
		'post_type'			=> $custom_post_type,
		'meta_query'		=> array(
			array(
				'key'		=> $key,
				'value'		=> $id,
				'compare'	=> '='
			)
		)
	));

	if($limit == 1) {
		return count($posts === 1) ? $posts[0] : false;
	}

	return $posts;
}

/**
 * template_pluralize
 * @desc	Template function to pluralise words
 * @example	echo template_pluralize(1, 'Baby'); // Baby
 * @example	echo template_pluralize(2, 'Baby'); // Babys (incorrect)
 * @example	echo template_pluralize(2, 'Baby', 'Babies'); // Babies (correct)
 * @param	int				$count
 * @param	string			$singular
 * @param	boolean|string	$plural
 * @return	string
 */
function template_pluralize($count, $singular, $plural = false) {
	if(!$plural) {
		$plural = $singular . 's';
	}

	return ($count == 1 ? $singular : $plural);
}

/**
 * template_is_ajax
 * @desc	Template function to test whether the current request was with AJAX or not
 * @return	boolean
 */
function template_is_ajax(){
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

/**
 * template_add_class
 * @desc	Template function to turn an array of classes in to a space separated string
 *			Default is to wrap the classes in the attribute class=""
 * @param	array	$class
 * @param	boolean	$attr
 * @return	string
 */
function template_add_class($class, $attr=true) {
	if(is_array($class)) {
		$class = implode(' ', $class);
	}
	$class	= trim($class);
	$length	= strlen($class);
	return (($attr === true && $length > 0) ? ' class="' : '') . $class . (($attr === true && $length > 0) ? '"' : '');
}

/**
 * class_count_attr
 * @param	int		$i
 * @param	int		$total
 * @param	array	$classes
 * @return	array 
 */
function class_count_attr($i, $total, $classes = array()) {
	if(!is_array($classes)) {
		$classes = array($classes);
	}
	$new_class = array();

	if($i === 1) {
		array_push($new_class, 'f');
	}
	if($i === $total) {
		array_push($new_class, 'l');
	}
	
	return array_merge($classes, $new_class);
}

/**
 * template_get_custom_field
 * @desc	Template helper function to get custom field values easily
 * @param	array	$custom
 * @param	string	$key
 * @param	string	$prefix
 * @return	string
 */
function template_get_custom_field($custom, $key, $prefix = 'custom_') {
	if(!empty($custom[$prefix . $key][0])) {
		return $custom[$prefix . $key][0];
	}
	return '';
}

/**
 * template_prefix_number
 * @param int		$number
 * @param string	$prefix
 * @return string
 */
function template_prefix_number($number, $prefix = '0') {
	return ($number < 10) ? $prefix . $number : $number;
}

/**
 * template_short_url
 * @param	string	$url
 * @param	boolean	$removeWWW
 * @return	string
 */
function template_short_url($url, $removeWWW = true) {
	return preg_replace('#https?://' . ($removeWWW ? '(www\.)?' : '') . '#', '', $url);
}

/**
 * template_format_size
 * @param	float	$size
 * @return	string 
 */
function template_format_size($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

/**
 * template_taxonomy_single_object
 * @param	string	$taxonomy
 * @param	int		$id
 * @param	string	$default
 * @param	boolean	$string
 * @return	object|boolean
 */
function template_taxonomy_single_object($taxonomy = 'category', $id = false, $default = '', $string = false) {
	$return = (object) array(
		'term_id'	=> 0,
		'name'		=> $default,
		'slug'		=> sanitize_title($default)
	);

	$taxonomies = get_the_terms($id, $taxonomy);
	if(!empty($taxonomies)) {
		$return = array_shift($taxonomies);
	}

	return ($string === true) ? $return->name : $return;
}

/**
 * template_address
 * @param	array	$address
 * return	string
 */
function template_address($address) {
	$html = '';

	foreach($address as $key => $value) {
		$class = strpos($key, 'extended_address_') !== false ? 'extended-address' : str_replace('_', '-', $key);
		$html .= '<span class="' . $class . '">' . $value . '</span>';
	}

	return '<address class="adr">' . $html . '</address>';
}

/**
 * template_get_post_type
 * @desc	Wrapper for get_post_type to fix for taxonomies and other pages
 * @return	string
 */
if(!function_exists('template_get_post_type')) {
	function template_get_post_type() {
		$post_type = get_post_type();
		$post_type = empty($post_type) ? 'post' : $post_type;

		if(empty($post_type)) {
			if(is_tax('category') || is_tax('post_tag')) {
				$post_type = 'post';
			}
		}
		
		if(is_tax('training_category')) {
			$post_type = 'training';
		}

		if(!empty($_GET['post_type'])) {
			$type = strtolower($_GET['post_type']);

			if($type === 'news') {
				$post_type = 'news';
			}
			elseif($type === 'training') {
				$post_type = 'training';
			}
		}

		return $post_type;
	}
}

/**
 * template_comment
 * @desc	
 * @param	object	$comment
 * @param	array	$args
 * @param	int		$depth 
 */
function template_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	
	switch($comment->comment_type) {
		case 'pingback':
		case 'trackback':
			?>
			<li class="pingback"><strong>Pingback:</strong> <?php comment_author_link(); ?></li>
			<?php
			break;
		
		default:
			?>
			<li id="comment-<?php comment_ID(); ?>" <?php comment_class('hentry'); ?>>
				<div class="comment-content entry-content">
					<?php comment_text(); ?>
				<!-- end of div .comment-content -->
				</div>
				<div class="comment-meta">
					<p class="vcard">Comment from <span class="fn"><?php comment_author_link(); ?></span></p>
					
					<?php if($comment->comment_approved == '0'): ?>
						<em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
					<?php endif; ?>
				</div>
			</li>
			<?php
			break;
	}
}

/**
 * tempalte_ordinal
 * @param	numeric	$num
 * @return	string 
 */
function tempalte_ordinal($num) {
	$suffix = 'th';
	if(($num / 10) % 10 != 1) {
		switch($num % 10)  {
			case 1:
				$suffix = 'st';
				break;
			
			case 2:
				$suffix = 'nd';
				break;
			
			case 3:
				$suffix = 'rd';
				break;
		}
	}
	
	return $num . '<sup>' . $suffix . '</sup>';
}

/**
 * add_first_and_last_to_nav
 * @param	array $items
 * @return	array
 */
function add_first_and_last_to_nav($items) {
	$total = count($items);
	$items[1]->classes[] = 'first-menu-item';
	$items[1]->classes[] = 'f';
	$items[$total]->classes[] = 'last-menu-item';
	$items[$total]->classes[] = 'l';
	return $items;
}

add_filter('wp_nav_menu_objects', 'add_first_and_last_to_nav');