<?php if($wp_query->post_count > 0): ?>
<div class="section listing listing-post listing-post-latest">
	<h3>Latest News</h3>
	<ul>
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$classy_post	= Classy_Post::find_by_id(get_the_ID());
		$classes		= class_count_array($i, $total);
		?>
		<li<?php $classy_post->the_attr('class', $classes) . $classy_post->the_attr('data'); ?>>
			<?php if($classy_post->has_permalink()): ?><a href="<?php echo $classy_post->get_permalink(); ?>" rel="bookmark" class="url"><?php endif; ?>

			<h2 class="entry-title listing-title"><?php $classy_post->the_title(); ?></h2>
				
			<p class="entry-date"><span class="key">Date:</span> <span class="value"><?php $classy_post->the_date('jS F Y'); ?></span></p>
			
			<div class="entry-summary">
				<?php $classy_post->the_excerpt(100); ?>
			</div>

			<?php if($classy_post->has_permalink()): ?></a><?php endif; ?>
		</li>
		<li class="<?php echo implode(' ', class_count_clear_array($i)); ?>"></li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div .listing-post -->
</div>
<?php endif; ?>