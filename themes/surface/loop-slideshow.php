<?php if($wp_query->post_count > 0): ?>
<div id="slideshow">
	<div class="inner">
		<ul class="images">
		<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
			<?php
			$class		= class_count_attr($i, $total, array());
			$class[]	= $i === 1 ? 'active' : '';

			$p			= Surface_CPT_Slideshow::find_by_id($post->ID);
			$href		= $p->get_link_href();
			$class[]	= $p->has_link_href() ? 'has-link' : '';
			
			$animations	 = 0;
			$animations += $p->has_thumbnail() ? 1 : 0;
			$animations += $p->has_thumbnail_text() ? 1 : 0;
			?>
			<li <?php post_class(array_filter($class)); ?> data-animation-count="<?php echo $animations; ?>">
				<?php if($p->has_link_href() === true): ?><a href="<?php echo $href; ?>" rel="bookmark" class="url"><?php endif; ?>
				
				<?php if($p->has_thumbnail()): ?>
				<div class="photo image">
					<?php echo $p->get_thumbnail('slideshow-image'); ?>
				</div>
				<?php endif; ?>
					
				<div class="photo text">
					<?php if($p->has_thumbnail_text()): ?>
					<?php echo $p->get_thumbnail_text('slideshow-text'); ?>
					<?php endif; ?>
				</div>
				
				<?php if($p->has_link_href() === true): ?></a><?php endif; ?>
			</li>
		<?php $i++; endwhile; ?>
		</ul>
	<!-- end of div .inner -->
	</div>
	<!-- end of div #slideshow -->
</div>
<?php endif; ?>