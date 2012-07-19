<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$class		= class_count_attr($i, $total, array());

	$post		= Surface_CPT_Post::find_by_id($post->ID);
	$class[]	= $post->has_thumbnail() ? 'has-thumbnail' : '';
	
	$tags		= get_the_term_list(0, 'post_tag', '', ', ',  '');
	$categories	= get_the_term_list(0, 'category', '', ', ',  '');
	$class[]	= !($tags instanceof WP_Error) && !empty($tags) ? 'has-tags' : 'no-tags';
	$class[]	= !($categories instanceof WP_Error) && !empty($categories) ? 'has-categories' : 'no-categories';
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(array_filter($class)); ?>>
		
		<?php if(is_single()): ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else: ?>
			<h2 class="entry-title"><a href="<?php echo $post->get_link_href(); ?>" class="url" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>
			
		<div class="entry-meta">
			<?php if(!($categories instanceof WP_Error) && !empty($categories)): ?>
			<p class="entry-categories"><?php _e('Categories'); ?>: <?php echo $categories; ?></p>
			<?php endif; ?>
			<?php if(!($tags instanceof WP_Error) && !empty($tags)): ?>
			<p class="entry-tags"><?php _e('Tags'); ?>: <?php echo $tags; ?></p>
			<?php endif; ?>
			<p class="entry-date date"><?php _e('Posted on'); ?> <span class="value"><?php echo get_the_date(); ?></span></p>
		<!-- end of div .entry-meta -->
		</div>

		<?php if(is_single()): ?>
		<div class="entry-content">
			<?php the_content(); ?>
		<!-- end of div .entry-content -->
		</div>
		<?php else: ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		<!-- end of div .entry-summary -->
		</div>	
		<?php endif; ?>

	<!-- end of div #post-<?php the_ID(); ?> -->
	</div>

<?php $i++; endwhile; ?>