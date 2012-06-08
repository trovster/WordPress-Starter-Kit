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
		$text = str_replace($img_tag, 'title=""', $text);
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

		if(!empty($_GET['post_type'])) {
			$type = strtolower($_GET['post_type']);

			if($type === 'news') {
				$post_type = 'news';
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

/**
 * jpeg_quality_callback
 * @param	array	$arg
 * @return	int 
 */
function jpeg_quality_callback($arg) {
	return 100;
}
add_filter('jpeg_quality', 'jpeg_quality_callback');

/**
 * wp_trim_all_excerpt
 * @see		http://www.transformationpowertools.com/wordpress/automatically-shorten-manual-excerpt
 * @global	object	$post
 * @param	string	$text
 * @return	string
 */
function wp_trim_all_excerpt($text) {
	global $post;
	
	$raw_excerpt = $text;
	
	if('' == $text) {
		$text = get_the_content('');
		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
	}

	$text			= strip_tags($text);
	$excerpt_length	= apply_filters('excerpt_length', 55);
	$excerpt_more	= apply_filters('excerpt_more', ' ' . '[...]');
	$text			= wp_trim_words($text, $excerpt_length, $excerpt_more);

	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wp_trim_all_excerpt');

/**
 * template_list_custom_post_type
 * @desc	Like wp_list_pages, but for custom post type
 * @global	object	$post
 * @param	string	$post_type
 * @param	array	$class
 * @return	string
 */
function template_list_custom_post_type($post_type, $class = array()) {
	global $post;
	
	$output				= '';
	$post_type			= strtolower($post_type);
	$class				= !is_array($class) ? array($class) : $class;
	$post_type_posts	= get_posts(array(
		'post_type'			=> $post_type,
		'numberposts'		=> -1,
		'orderby'			=> 'menu_order',
		'order'				=> 'ASC',
	));
	
	if(count($post_type_posts)) {
		$output .= '<ul' . template_add_class($class) . '>' . "\r\n";
		foreach($post_type_posts as $post_type_post) {
			$class	= $post_type_post->ID === $post->ID && $post->post_type === $post_type ? array('active', 'current_page_item') : array();
			$output .= '<li' . template_add_class($class) . '><a href="' . get_permalink($post_type_post->ID) . '">' . get_the_title($post_type_post->ID) . '</a></li>';
		}
		$output .= '</ul>' . "\r\n";
	}
	
	return $output;
}

/**
 * template_get_template_by_id
 * @desc	Finds the page template variable, by page ID
 * @param	int	$page_id
 * @return	false|string
 */
function template_get_template_by_id($page_id) {
	$custom = get_post_custom($page_id);
	if(!empty($custom)) {
		return template_get_custom_field($custom, 'page_template', '_wp_');
	}
	return false;
}

/**
 * template_get_nav_item
 * @desc	Get the page information from the navigation array
 * @param	string	$key
 * @param	string	$value
 * @return	null|int
 */
function template_get_nav_item($key, $value = null) {
	$navigation = template_get_nav();

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
 * template_get_nav_item_id
 * @desc	Proxy to template_get_nav_item, to get the page_id
 * @param	string	$key
 * @return	null|int
 */
function template_get_nav_item_id($key) {
	return template_get_nav_item($key, 'page_id');
}

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
	
	if(defined('WP_CDN')) {
		$uri = str_replace(constant('WP_SITEURL'), constant('WP_CDN'), $uri);
	}
	
	return $uri;
}
add_action('stylesheet_directory_uri', 'site_stylesheet_directory_uri', 10, 3);

/**
 * Walker_Nav_Menu
 * Overwrite end_el function to remove the new line, which causes white space issues with display-inline
 */
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
	function end_el(&$output, $item, $depth) {
		$output .= "</li>";
	}
}

/**
 * gform_tabindex_remove
 * @desc	Completely removes the tabindex for the gravity forms
 * @see		http://www.gravityhelp.com/documentation/page/Gform_tabindex
 * @return	boolean 
 */
function gform_tabindex_remove() {
	return false;
}
add_filter('gform_tabindex', 'gform_tabindex_remove');

/**
 * gform_ajax_spinner_url_remove
 * @desc	Remove the AJAX spinner
 * @param	string	$image_src
 * @param	object	$form
 * @return	string 
 */
function gform_ajax_spinner_url_remove($image_src, $form) {
    return '';
}
add_filter('gform_ajax_spinner_url', 'gform_ajax_spinner_url_remove', 10, 2);