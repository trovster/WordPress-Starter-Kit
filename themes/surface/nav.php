<?php wp_nav_menu(array(
	'theme_location'	=> 'main',
	'container_class'	=> '',
	'container_id'		=> '',
	'menu_class'		=> '',
	'menu_id'			=> '',
	'walker'			=> new Custom_Walker_Nav_Menu,
	'exclude'			=> '' // 
));  ?>