<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-search">
	<ul>
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		switch(get_post_type($post)) {
			case 'page':
				$classy_item = Classy_Page::find_by_id(get_the_ID());
				break;
			
			default:
				$classy_item = Classy_Post::find_by_id(get_the_ID());
				break;
		}
		$classes = class_count_array($i, $total);
		?>
		<li<?php $classy_item->the_attr('class', $classes) . $classy_item->the_attr('data'); ?>>
			
			<?php if($classy_item->get_post_type() === 'page'): ?>
			
			<a href="<?php echo $classy_item->get_permalink(); ?>" rel="bookmark" class="url">
				<div class="entry-summary">
					<?php $classy_item->the_excerpt(275); ?>
				</div>
			</a>
			
			<?php else: ?>
			
			<?php if($classy_item->has_permalink()): ?>
			
				<a href="<?php echo $classy_item->get_permalink(); ?>" rel="bookmark" class="url">
					<h2 class="entry-title"><?php $classy_item->the_title(); ?></h2>

					<?php if($classy_item->get_post_type() === 'post'): ?>
					<p class="entry-date"><span class="type">Date:</span> <span class="value"><?php echo $classy_item->get_date('d-m-Y'); ?></span></p>
					<?php endif; ?>

					<div class="entry-summary">
						<?php $classy_item->the_excerpt(275); ?>
					</div>
				</a>
			
			<?php else: ?>
			
				<?php if($classy_item->get_post_type() === 'post'): ?>
				<p class="entry-date"><span class="type">Date:</span> <span class="value"><?php echo $classy_item->get_date('d-m-Y'); ?></span></p>
				<?php endif; ?>

				<div class="entry-summary">
					<?php $classy_item->the_excerpt(275); ?>
				</div>
			
			<?php endif; ?>
			
			<?php endif; ?>
			
		</li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div .listing-search -->
</div>
<?php endif; ?>