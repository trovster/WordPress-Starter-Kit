<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-featured">
	<div class="inner">
		<ul>
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$featured	= Classy_Featured::find_by_id(get_the_ID());
			$classes	= class_count_array($i, $total);
			?>
			<li<?php $featured->the_attr('class', $classes) . $featured->the_attr('data'); ?>>
				<?php if($featured->has_permalink()): ?><a href="<?php $featured->the_permalink(); ?>" rel="bookmark" class="url"><?php endif; ?>

				<?php if($featured->has_thumbnail()): ?>
				<div class="photo">
					<?php echo $featured->get_thumbnail($featured->get_post_type()); ?>
				</div>
				<?php endif; ?>
					
				<h1 class="entry-title"><?php $featured->the_title(); ?></h1>

				<?php if($featured->has_permalink()): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	</div>
<!-- end of div .listing -->
</div>
<?php endif; ?>