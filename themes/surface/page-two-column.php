<?php
	/*	
		Template Name: Two Column
	*/
?>
<?php get_header(); ?>

<div id="content-primary">
	
	<?php while(have_posts()): the_post(); ?>
	
	<?php
	$custom					= get_post_custom($post->ID);
	$secondary_column		= template_get_custom_field($custom, 'secondary_column');
	?>
	
	<div class="hentry">
		
		<h1 class="entry-title"><?php the_title(); ?></h1>
		
		<div class="entry-content column column-primary">
			<?php the_content(); ?>
		</div>
		
		<?php if(!empty($secondary_column)): ?>
		<div class="column column-secondary">
			<?php echo apply_filters('the_content', $secondary_column); ?>
		<!-- end of div .column-secondary -->
		</div>
		<?php endif; ?>
		
	</div>
	<?php endwhile; ?>

<!-- end of div id #content-primary -->
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>