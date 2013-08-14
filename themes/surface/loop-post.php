<?php if($wp_query->post_count > 0): ?>
<div class="listing listing-post">
	<ul>
	<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
		<?php
		$classy_post	= Classy_Post::find_by_id(get_the_ID());
		$classes		= class_count_array($i, $total);
		$category		= $classy_post->has_category() ? $classy_post->get_category() : false;
		?>
		<li<?php $classy_post->the_attr('class', $classes) . $classy_post->the_attr('data'); ?>>

			<?php if($classy_post->has_permalink()): ?>
			<h2 class="entry-title"><a href="<?php echo $classy_post->get_permalink(); ?>" rel="bookmark" class="url"><?php $classy_post->the_title(); ?></a></h2>
			<?php else: ?>
			<h2 class="entry-title"><?php $classy_post->the_title(); ?></h2>
			<?php endif; ?>

			<div class="entry-summary">
				<?php $classy_post->the_excerpt(200); ?>
				<?php if($classy_post->has_permalink()): ?>
				<p class="more"><a href="<?php echo $classy_post->get_permalink(); ?>">Read more…<strong> about “<?php $classy_post->the_title(); ?>”</strong></a></p>
				<?php endif; ?>
			</div>

			<div class="entry-meta">
				<?php if($classy_post->has_category()): ?>
				<p>Posted on <span class="entry-date"><?php echo $classy_post->get_date('jS F Y'); ?></span> in the <a href="<?php echo get_tag_link($category); ?>" class="category"><?php echo $category->name; ?></a> category.</p>
				<?php else: ?>
				<p>Posted on <span class="entry-date"><?php echo $classy_post->get_date('jS F Y'); ?></span>.</p>
				<?php endif; ?>
			</div>
			
		</li>
	<?php $i++; endwhile; ?>
	</ul>
<!-- end of div .listing-post -->
</div>
<?php endif; ?>