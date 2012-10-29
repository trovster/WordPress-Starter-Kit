<?php if($wp_query->post_count > 0): ?>
<div id="slideshow">
	<div class="inner">
		<ul class="images">
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$class		= class_count_attr($i, $total, array());
			$class[]	= $i === 1 ? 'active' : '';

			$slideshow	= Surface_CPT_Slideshow::find_by_id(get_the_ID());
			$class[]	= $slideshow->has_link_href() ? 'has-link' : '';
			
			$animations	 = 0;
			$animations += $slideshow->has_thumbnail() ? 1 : 0;
			$animations += $slideshow->has_thumbnail_text() ? 1 : 0;
			?>
			<li <?php post_class(array_filter($class)); ?> data-animation-count="<?php echo $animations; ?>">
				<?php if($slideshow->has_link_href()): ?><a href="<?php echo $slideshow->get_link_href(); ?>" rel="bookmark" class="url"><?php endif; ?>
				
				<?php if($slideshow->has_thumbnail()): ?>
				<div class="photo image">
					<?php echo $slideshow->get_thumbnail($slideshow->get_post_type() . '-image'); ?>
				</div>
				<?php endif; ?>
					
				<?php if($slideshow->has_thumbnail_text()): ?>
				<div class="photo text">
					<?php echo $slideshow->get_thumbnail_text($slideshow->get_post_type() . '-text'); ?>
				</div>
				<?php endif; ?>
				
				<?php if($slideshow->has_link_href()): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	<!-- end of div .inner -->
	</div>
	<!-- end of div #slideshow -->
</div>
<?php endif; ?>