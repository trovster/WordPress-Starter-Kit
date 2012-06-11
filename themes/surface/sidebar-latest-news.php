 <div id="latest-news" class="section">
	<h2><?php _e('Latest News'); ?></h2>
	
	<?php
	$setup = array(
		'post_type'			=> 'post',
		'title_li'			=> '',
		'echo'				=> 0,
		'depth'				=> 1,
		'posts_per_page'	=> 3,
		/*
		'meta_query'		=> array(
			array(
				'key'		=> '_thumbnail_id',
			)
		)
		*/
	);
	?>
	<?php $original = template_pre_loop($setup); ?>
	<?php get_template_part('loop', 'latest'); ?>
	<?php $original = template_post_loop($original); extract($original); wp_reset_query(); ?>
<!-- end of div #latest-news -->
</div>