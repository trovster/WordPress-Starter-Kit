<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$class		= class_count_attr($i, $total, array());

	$the_post	= Surface_CPT_Post::find_by_id(get_the_ID());
	$class[]	= $the_post->has_thumbnail() ? 'has-thumbnail' : '';
	$class[]	= $the_post->has_tags() ? 'has-tags' : 'no-tags';
	$class[]	= $the_post->has_categories() ? 'has-categories' : 'no-categories';
	?>
	<div <?php post_class(array_filter($class)); ?>>
		
		<?php if(is_single()): ?>
			<h1 class="entry-title"><?php $the_post->the_title(); ?></h1>
		<?php else: ?>
			<h2 class="entry-title"><a href="<?php echo $the_post->get_link_href(); ?>" class="url" rel="bookmark"><?php $the_post->the_title(); ?></a></h2>
		<?php endif; ?>
			
		<div class="entry-meta">
			<?php if($the_post->has_categories()): ?>
			<p class="entry-categories"><span class="type"><?php _e('Categories'); ?>:</span> <?php echo Surface_CPT_Post::get_taxonomy_list($the_post->get_categories(), '', ', ', ''); ?></p>
			<?php endif; ?>
			<?php if($the_post->has_tags()): ?>
			<p class="entry-tags"><span class="type"><?php _e('Tags'); ?>:</span> <?php echo Surface_CPT_Post::get_taxonomy_list($the_post->get_tags(), '', ', ', ''); ?></p>
			<?php endif; ?>
			<p class="entry-date date"><span class="type"><?php _e('Posted on'); ?></span> <span class="value"><?php echo $the_post->get_the_date(); ?></span></p>
		<!-- end of div .entry-meta -->
		</div>

		<?php if(is_single()): ?>
		<div class="entry-content">
			<?php $the_post->the_content(); ?>
		<!-- end of div .entry-content -->
		</div>
		<?php else: ?>
		<div class="entry-summary">
			<?php $the_post->the_excerpt(); ?>
		<!-- end of div .entry-summary -->
		</div>	
		<?php endif; ?>

	<!-- end of div.hentry -->
	</div>

<?php $i++; endwhile; ?>