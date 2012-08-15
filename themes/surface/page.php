<?php get_header(); ?>

<div id="content-primary">
	
	<?php while(have_posts()): the_post(); ?>
	
	<?php $page	= Surface_CPT_Page::find_by_id($post->ID); ?>
	
	<div class="hentry">
		
		<h1 class="entry-title"><?php $page->the_title(); ?></h1>
		
		<div class="entry-content">
			<?php $page->the_content(); ?>
		</div>
		
	</div>
	
	<?php endwhile; ?>

<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>