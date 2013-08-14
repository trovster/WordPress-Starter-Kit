<?php if($wp_query->post_count > 0): ?>
<?php $i = 1; $total = $wp_query->post_count; while(have_posts()): the_post(); ?>
	<?php
	$sidebar		= Classy_Sidebar::find_by_id(get_the_ID());
	$classes		= array('section');
	?>
	<div<?php $sidebar->the_attr('class', $classes) . $sidebar->the_attr('data'); ?>>
		
		<?php if($sidebar->has_permalink()): ?>
		<h3 class="section-title"><a href="<?php echo $sidebar->get_permalink(); ?>" rel="bookmark" class="more"><?php $sidebar->the_title(); ?></a></h3>
		<?php else: ?>
		<h3 class="section-title"><?php $sidebar->the_title(); ?></h3>
		<?php endif; ?>
		
		<?php if($sidebar->has_thumbnail()): ?>
		<div class="photo">
			<?php echo $sidebar->get_thumbnail($sidebar->get_post_type()); ?>
		</div>
		<?php endif; ?>
		
		<div class="section-content">
			<?php $sidebar->the_content(); ?>
		</div>
		
	</div>
<?php $i++; endwhile; ?>
<?php endif; ?>