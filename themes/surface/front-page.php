<?php
	/*
		Template Name: The Homepage
	*/
?>
<?php get_header(); ?>

<?php $classy_page = Classy_Page::find_by_id(get_the_ID()); ?>

<div id="content-primary">

	<div<?php $classy_page->the_attr('class'); ?>>
		<?php $classy_page->the_content(); ?>
	</div>
	
	<?php echo Classy::loop(Classy_Post::get_options(array(
		'posts_per_page'	=> 1,
		'tax_query'			=> array(
			array(
				'taxonomy'	=> 'category',
				'field'		=> 'slug',
				'terms'		=> 'spotlight'
			)
		)
	)), 'loop', 'post-spotlight'); ?>

<!-- end of div #content-primary -->
</div>
	
<div id="content-secondary">
	
	<?php get_sidebar('post-latest'); ?>
	
<!-- end of div #content-secondary -->
</div>

<div id="content-tertiary">
	
	<?php get_sidebar('calendar'); ?>

	<?php get_sidebar('video'); ?>
	
<!-- end of div #content-tertiary -->
</div>

<?php get_footer(); ?>