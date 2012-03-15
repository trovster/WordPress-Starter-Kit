<?php get_header(); ?>

<?php $type = template_get_post_type(); ?>

<?php get_sidebar($type); ?>

<div id="content-primary">
	
	<div class="header">
		<p>Displaying search results for: <strong><?php echo get_search_query(); ?></strong></p>
	</div>
	
	<div id="<?php echo $type; ?>" class="hatom archive">
		<?php rewind_posts(); ?>
		<?php get_template_part('loop', $type); ?>
	<!-- end of div #<?php echo $type; ?> -->
	</div>
	
	<?php get_template_part('paginate'); ?>
	
<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>