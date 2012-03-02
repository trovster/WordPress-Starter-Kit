<?php get_header(); ?>

<?php
$type = get_post_type();
$type = empty($type) ? $wp_query->query_vars['post_type'] : $type;
$type = empty($type) ? 'post' : $type;
?>

<div id="content-primary">
	
	<div id="<?php echo $type; ?>" class="single hatom">
		<?php rewind_posts(); ?>
		<?php get_template_part('loop', $type); ?>
	<!-- end of div #<?php echo $type; ?> -->
	</div>
	
<!-- end of div id #content-primary -->
</div>

<?php get_sidebar($type); ?>

<?php get_footer(); ?>