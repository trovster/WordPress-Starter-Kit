<?php get_header(); ?>

<div id="content-primary">
	
	<?php rewind_posts(); ?>
	<?php get_template_part('loop', 'post-single'); ?>
	
<!-- end of div id #content-primary -->
</div>

<?php get_sidebar('post'); ?>

<?php get_sidebar('tertiary'); ?>

<?php get_footer(); ?>