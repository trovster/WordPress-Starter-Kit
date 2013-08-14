<?php if($wp_query->post_count > 0): ?>
<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$classy_post	= Classy_Post::find_by_id(get_the_ID());
	$category		= $classy_post->has_category() ? $classy_post->get_category() : false;
	?>
	<div<?php $classy_post->the_attr('class') . $classy_post->the_attr('data'); ?>>

		<h1 class="entry-title"><?php $classy_post->the_title(); ?></h1>

		<div class="entry-content">
			<?php $classy_post->the_content(); ?>
		</div>

		<div class="entry-meta">
			<?php if($classy_post->has_category()): ?>
			<p>Posted on <span class="entry-date"><?php echo $classy_post->get_date('jS F Y'); ?></span> in the <a href="<?php echo get_tag_link($category); ?>" class="category"><?php echo $category->name; ?></a> category.</p>
			<?php else: ?>
			<p>Posted on <span class="entry-date"><?php echo $classy_post->get_date('jS F Y'); ?></span>.</p>
			<?php endif; ?>			
		</div>
		
	</div>
<?php $i++; endwhile; ?>
<?php endif; ?>