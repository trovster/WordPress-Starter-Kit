<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-slideshow">
	<div class="inner">
		<ul>
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$slideshow	= Classy_Slideshow::find_by_id(get_the_ID());
			$classes	= class_count_array($i, $total);
			$classes	= $i === 1 ? array_merge(array('active'), $classes) : $classes;
			?>
			<li<?php $slideshow->the_attr('class', $classes) . $slideshow->the_attr('data'); ?>>
				<?php if($slideshow->has_permalink()): ?><a href="<?php $slideshow->the_permalink(); ?>" rel="bookmark" class="url"><?php endif; ?>
					
				<div class="entry-content">
					<div class="inner">
						<h1 class="entry-title"><?php $slideshow->the_title(); ?></h1>
						<div class="description">
							<?php $slideshow->the_content(); ?>
						</div>
					</div>
				</div>

				<?php if($slideshow->has_thumbnail()): ?>
				<div class="photo">
					<?php echo $slideshow->get_thumbnail($slideshow->get_post_type()); ?>
				</div>
				<?php endif; ?>

				<?php if($slideshow->has_permalink()): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	</div>
<!-- end of div .listing -->
</div>
<?php endif; ?>