<?php if($wp_query->post_count > 0): ?>
<div id="slideshow">
	<ul class="images">
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$class		= class_count_attr($i, $total, array());
		$class[]	= $i === 1 ? 'active' : '';

		$p			= Surface_CPT_Slideshow::find_by_id($post->ID);
		$href		= $p->get_link_href();
		$link		= $href ? true : false;
		$class[]	= $href ? 'has-link' : '';
		?>
		<li <?php post_class(array_filter($class)); ?>>
			<?php if($link === true): ?><a href="<?php echo $href; ?>" rel="bookmark" class="url"><?php endif; ?>
			<?php if(has_post_thumbnail()): ?>
			<div class="photo">
				<?php the_post_thumbnail('slideshow'); ?>
			</div>
			<?php endif; ?>
			<?php if($link === true): ?></a><?php endif; ?>
		</li>
	<?php $i++; endwhile; ?>
	</ul>
	<!-- end of div #slideshow -->
</div>
<?php endif; ?>