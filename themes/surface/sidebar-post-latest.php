<?php echo Classy::loop(Classy_Post::get_options(array(
	'posts_per_page'	=> 3
)), 'loop', 'post-latest'); ?>