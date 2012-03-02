<?php if($wp_query->post_count > 0): ?>
<div id="featured">
	<ul class="listing hatom">
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$class	 = class_count_attr($i, $total, array('hentry'));
		$class[] = (has_post_thumbnail()) ? 'has-thumbnail' : '';

		$custom		= get_post_custom(get_the_ID());
		$href		= get_custom_link_href($custom);
		$link		= $href ? true : false;
		$class[]	= $href ? 'has-link' : '';
		?>
		<li <?php post_class(array_filter($class)); ?>>
			<?php if($link === true): ?><a href="<?php echo $href; ?>" rel="bookmark" class="url"><?php endif; ?>
			<h3 class="entry-title"><?php the_title(); ?></h3>
			<div class="photo">
				<?php the_post_thumbnail('featured'); ?>
			</div>
			<?php if($link === true): ?></a><?php endif; ?>
		</li>
	<?php $i++; endwhile; wp_reset_query(); ?>
	</ul>
<!-- end of div #featured -->
</div>
<?php endif; ?>