<?php
$setup = array(
	'post_type'			=> 'slideshow',
	'orderby'			=> 'menu_order',
	'order'				=> 'ASC',
	'title_li'			=> '',
	'echo'				=> 0,
	'depth'				=> 1,
	'posts_per_page'	=> -1,
	'meta_query' => array(
		array(
			'key'		=> '_thumbnail_id',
		)
	)
);
?>
<?php $original = template_pre_loop($setup); ?>
<?php get_template_part('loop', 'slideshow'); ?>
<?php $original = template_post_loop($original); extract($original); wp_reset_query(); ?>
