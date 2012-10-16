<?php if($wp_query->post_count > 0): ?>
<div id="featured">
	<ul class="listing hatom">
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$class	 = class_count_attr($i, $total, array());
		
		$featured	= Surface_CPT_Featured::find_by_id($post->ID);
		$class[]	= $featured->has_link_href() ? 'has-link' : '';
		$class[]	= $featured->has_thumbnail() ? 'has-thumbnail' : '';
		?>
		<li <?php post_class(array_filter($class)); ?>>
			<?php if($featured->has_link_href()): ?><a href="<?php echo $featured->get_link_href(); ?>" rel="bookmark" class="url"><?php endif; ?>
			<h3 class="entry-title"><?php $featured->the_title(); ?></h3>
			<?php if($featured->has_thumbnail()): ?>
			<div class="photo">
				<?php echo $featured->get_thumbnail($featured->get_post_type() . '-image'); ?>
			</div>
			<?php endif; ?>
			<div class="entry-content">
				<?php $featured->the_content(); ?>
			</div>
			<?php if($featured->has_link_href()): ?></a><?php endif; ?>
		</li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div #featured -->
</div>
<?php endif; ?>