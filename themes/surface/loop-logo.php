<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-logo">
	<div class="inner">
		<ul>
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$logo		= Classy_Logo::find_by_id(get_the_ID());
			$classes	= class_count_array($i, $total);
			?>
			<li<?php $logo->the_attr('class', $classes) . $logo->the_attr('data'); ?>>
				<?php if($logo->has_permalink()): ?><a href="<?php $logo->the_permalink(); ?>" rel="bookmark external" class="url"><?php endif; ?>

				<?php if($logo->has_thumbnail()): ?>
				<div class="photo">
					<?php echo $logo->get_thumbnail($logo->get_post_type()); ?>
				</div>
				<?php endif; ?>

				<?php if($logo->has_permalink()): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	</div>
<!-- end of div .listing -->
</div>
<?php endif; ?>