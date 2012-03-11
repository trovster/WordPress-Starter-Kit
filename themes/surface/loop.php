<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$class		= array();
	$class[]	= ($i === 1) ? 'f' : '';
	$class[]	= ($i === $total) ? 'l' : '';
	$class[]	= (has_post_thumbnail()) ? 'has-thumbnail' : '';
	
	$type		= get_post_type();
	$tags		= get_the_term_list(0, 'post_tag', '', ', ', '.');
	$categories	= get_the_term_list(0, 'category', '', ', ', '.');
	$class[]	= !empty($tags) ? 'has-tags' : 'no-tags';
	$class[]	= !empty($categories) ? 'has-categories' : 'no-categories';
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(array_filter($class)); ?>>
		
		<?php if(is_single()): ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else: ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" class="url" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>
			
		<div class="entry-meta">
			<?php if(!empty($categories)): ?>
			<p class="entry-categories">Categories: <?php echo $categories; ?></p>
			<?php endif; ?>
			<?php if(!empty($tags)): ?>
			<p class="entry-tags">Tags: <?php echo $tags; ?></p>
			<?php endif; ?>
			<p class="entry-date date">Posted on <?php echo get_the_date(); ?></p>
		<!-- end of div .entry-meta -->
		</div>

		<div class="entry-content">
			<?php the_content(); ?>
		<!-- end of div .entry-content -->
		</div>

	<!-- end of div #post-<?php the_ID(); ?> -->
	</div>

<?php $i++; endwhile; ?>