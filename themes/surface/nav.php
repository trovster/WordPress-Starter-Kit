<?php global $navigation; ?>
<?php if(!empty($navigation) && is_array($navigation)): ?>
<ul>
<?php $i = 1; $total = count($navigation); ?>
<?php foreach($navigation as $nav): ?>
	<?php
	$class		= template_section_class($nav['page_id'], $nav['class']);
	$class		= class_count_attr($i, $total, $class);
	$pages		= null;
	if(!is_null($nav['page_id'])) {
		$pages		= wp_list_pages(array(
			'title_li'	=> '',
			'echo'		=> 0,
			'depth'		=> 1,
			'child_of'	=> $nav['page_id']
		));
	}
	$class[]	= !empty($pages) ? 'has-children' : '';
	?>
	<li<?php echo template_add_class($class); ?>><a href="<?php echo $nav['href']; ?>"><?php echo $nav['text']; ?></a><?php 
	if(!empty($pages)) {
		echo '<ul class="children">' . $pages . '</ul>';
	}
	?></li>
<?php $i++; endforeach; ?>
</ul>
<?php endif; ?>