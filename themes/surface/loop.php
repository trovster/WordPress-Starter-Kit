<?php
$isArchive = true;
if(is_single()) {
	$isArchive = false;
}
?>
<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$class		= array();
	$class[]	= ($i === 1) ? 'f' : '';
	$class[]	= ($i === $total) ? 'l' : '';
	$class[]	= (has_post_thumbnail()) ? 'has-thumbnail' : '';
	
	$type		= get_post_type();
	$tags		= get_the_term_list(0, $type . '_tag', '', ', ',  '.');
	$class[]	= !empty($tags) ? 'has-tags' : 'no-tags';
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(array_filter($class)); ?>>
		
		<?php if(is_single()): ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else: ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" class="url" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>
			
		<div class="entry-meta">
			<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true" data-font="arial"></div>
			<p class="entry-date date">Posted on <?php echo get_the_date(); ?></p>
			<?php if(!empty($tags)): ?>
			<p class="entry-tags">Tagged as <?php echo $tags; ?></p>
			<?php endif; ?>
		<!-- end of div .entry-meta -->
		</div>

		<div class="entry-content">
			<?php the_content(); ?>
		<!-- end of div .entry-content -->
		</div>

	<!-- end of div #post-<?php the_ID(); ?> -->
	</div>

<?php $i++; endwhile; ?>