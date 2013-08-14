<?php get_header(); ?>

<?php $classy_page = Classy_Page::find_by_id(get_the_ID()); ?>

<div id="content-primary" <?php $classy_page->the_attr('class'); ?>>
	
	<?php $classy_page->the_content(); ?>

</div>

<?php get_sidebar(); ?>

<?php get_sidebar('tertiary'); ?>

<?php get_footer(); ?>