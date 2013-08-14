<?php get_header(); ?>

<?php $classy_page = Classy_Page::find_by_id(_site_get_nav_item_id('news')); ?>

<div id="content-primary">
	
	<?php if(!empty($classy_page)): ?>
		<div <?php $classy_page->the_attr('class'); ?>>
			<?php $classy_page->the_content(); ?>
		</div>
	<?php endif; ?>
	
	<?php rewind_posts(); ?>
	<?php get_template_part('loop', 'post'); ?>
	
	<?php get_template_part('paginate'); ?>
	
<!-- end of div id #content-primary -->
</div>

<?php get_sidebar('post'); ?>

<?php get_sidebar('tertiary'); ?>

<?php get_footer(); ?>