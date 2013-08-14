<?php get_header(); ?>

<div id="content-primary">
	
	<div class="header">
		<h1>Search Results</h1>
		<p><?php _e('Displaying search results for'); ?>: <strong><?php echo get_search_query(); ?></strong></p>
	</div>
	
	<div id="search" class="hatom archive">
		<?php rewind_posts(); ?>
		<?php get_template_part('loop', 'search'); ?>
	<!-- end of div #search -->
	</div>
	
	<?php get_template_part('paginate'); ?>
	
<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>