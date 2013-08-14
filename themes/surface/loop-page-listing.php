<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-page">
	<ul>
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$classy_page	= Classy_Page::find_by_id(get_the_ID());
		$classes		= class_count_array($i, $total);
		?>
		<li<?php $classy_page->the_attr('class', $classes) . $classy_page->the_attr('data'); ?>>
			
			<?php if($classy_page->has_permalink()): ?><a href="<?php echo $classy_page->get_permalink(); ?>" rel="bookmark" class="url"><?php endif; ?>
				
			<div class="section-title">
				<h2 class="entry-title"><?php $classy_page->the_listing_title(); ?></h2>
			</div>
			
			<?php if($classy_page->has_listing_image()): ?>
			<div class="photo">
				<?php echo $classy_page->get_listing_image(); ?>
			</div>
			<?php endif; ?>
			
			<div class="entry-summary">
				<?php $classy_page->the_excerpt(100); ?>
			</div>
			
			<?php if($classy_page->has_permalink()): ?></a><?php endif; ?>

		</li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div .listing-page -->
</div>
<?php endif; ?>