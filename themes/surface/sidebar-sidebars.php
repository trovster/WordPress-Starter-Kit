<?php
$section = Classy_Page::get_the_page();

if(!empty($section) && $section instanceof Classy_Page) {
	$sidebar_ids	= Classy_Sidebar::get_ids($section);
	$options		= Classy_Sidebar::get_options(array(
		'orderby'	=> 'menu_order',
		'order'		=> 'ASC',
		'post__in'	=> count($sidebar_ids) > 0 ? $sidebar_ids : array(-1),
	));
	
	// if no sidebars on the current page, look up top-level section for any sidebars
	if(Classy_Sidebar::has_items($section, $options) === 0) {
		$section_top	= Classy_Page::find_by_id($section->get_top_level_id());
		$sidebar_ids	= Classy_Sidebar::get_ids($section_top);
		$options		= Classy_Sidebar::get_options(array(
			'orderby'	=> 'menu_order',
			'order'		=> 'ASC',
			'post__in'	=> count($sidebar_ids) > 0 ? $sidebar_ids : array(-1),
		));
	}

	// static sidebars, such as latest news
	foreach(Classy_Sidebar::get_default_items() as $key => $item) {
		if($section->get_custom_value_boolean($key . '_enabled')) {
			get_template_part('sidebar', $key);
		}
	}

	// dynamic sidebars
	if(!empty($sidebar_ids)) {
		echo Classy::loop($options, 'loop', 'sidebar');
	}
}