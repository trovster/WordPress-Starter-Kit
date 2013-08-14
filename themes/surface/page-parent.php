<?php
	/*	
		Template Name: Parent Redirect
	*/

// redirects parent pages to first child
$pagekids = get_pages(array(
	'child_of'		=> $post->ID,
	'sort_column'	=> 'menu_order'
));

if(!empty($pagekids)) {
	$page = array_shift($pagekids);
	wp_redirect(get_permalink($page->ID));
	exit;
}