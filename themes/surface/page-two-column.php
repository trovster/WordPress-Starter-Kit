<?php
	/*	
		Template Name: Two Column
	*/
?>
<?php get_header(); ?>

<div id="content-primary">
	
	<?php while(have_posts()): the_post(); ?>
	
	<?php $the_page	= Surface_CPT_Page::find_by_id($post->ID); ?>
	
	<div class="hentry">
		
		<h1 class="entry-title"><?php $the_page->the_title(); ?></h1>
		
		<div class="entry-content column column-primary">
			<?php $the_page->the_content(); ?>
		</div>
		
		<?php if($the_page->has_custom_value('secondary_column')): ?>
		<div class="column column-secondary">
			<?php echo apply_filters('the_content', $the_page->custom_value('secondary_column')); ?>
		<!-- end of div .column-secondary -->
		</div>
		<?php endif; ?>
		
	</div>
	<?php endwhile; ?>

<!-- end of div id #content-primary -->
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>