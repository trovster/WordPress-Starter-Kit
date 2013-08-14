<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-testimonial">
	<div class="inner">
		<ul>
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$testimonial= Classy_Testimonial::find_by_id(get_the_ID());
			$classes	= class_count_array($i, $total);
			$classes	= $i === 1 ? array_merge(array('active'), $classes) : $classes;
			?>
			<li<?php $testimonial->the_attr('class', $classes) . $testimonial->the_attr('data'); ?>>
				<?php if($testimonial->has_permalink()): ?><a href="<?php $testimonial->the_permalink(); ?>" rel="bookmark" class="url"><?php endif; ?>
					
				<blockquote>
					<?php $testimonial->the_content(); ?>
				</blockquote>
				
				<?php if($testimonial->has_citation()): ?>
				<p class="citation"><?php $testimonial->the_citation(); ?></p>
				<?php else: ?>
				<p class="citation"><?php $testimonial->the_title(); ?></p>
				<?php endif; ?>

				<?php if($testimonial->has_permalink()): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	</div>
<!-- end of div .listing -->
</div>
<?php endif; ?>