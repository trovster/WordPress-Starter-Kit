<?php if($wp_query->post_count > 0): ?>
<div id="featured">
	<ul class="listing hatom">
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$class	 = class_count_attr($i, $total, array());
		$class[] = (has_post_thumbnail()) ? 'has-thumbnail' : '';
		
		$featured	= Surface_CPT_Featured::find_by_id($post->ID);
		$class[]	= $featured->has_link_href() ? 'has-link' : '';
		?>
		<li <?php post_class(array_filter($class)); ?>>
			<?php if($featured->has_link_href()): ?><a href="<?php echo $featured->get_link_href(); ?>" rel="bookmark" class="url"><?php endif; ?>
			<h3 class="entry-title"><?php the_title(); ?></h3>
			<div class="photo">
				<?php the_post_thumbnail('featured'); ?>
			</div>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<?php if($featured->has_link_href()): ?></a><?php endif; ?>
		</li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div #featured -->
</div>
<?php endif; ?>