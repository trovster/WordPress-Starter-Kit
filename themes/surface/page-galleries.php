<?php
	/*
		Template Name: Galleries
	*/
?>
<?php get_header(); ?>

<div id="content-primary">
	
	<?php if(class_exists('NextGEN_shortcodes')): ?>
	<?php echo do_shortcode('[album 1 template=listing]'); ?>
	<?php endif; ?>
	
<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>