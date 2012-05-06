<?php global $is_footer; ?>
<ul>
<?php $navigation = template_get_nav($is_footer); $i = 1; $total = count($navigation); ?>
<?php foreach($navigation as $nav): ?>
	<?php
	$class		= template_section_class($nav['page_id'], $nav['class']);
	$class		= class_count_attr($i, $total, $class);
	$pages		= null;
	if($is_footer && !is_null($nav['page_id'])) {
		$pages		= wp_list_pages(array(
			'title_li'	=> '',
			'echo'		=> 0,
			'depth'		=> 1,
			'child_of'	=> $nav['page_id']
		));
	}
	?>
	<li<?php echo template_add_class($class); ?>><a href="<?php echo $nav['href']; ?>"><?php echo esc_html__($nav['text']); ?></a><?php 
	if(!empty($pages)) {
		echo '<ul class="children">' . $pages . '</ul>';
	}
	?></li>
<?php $i++; endforeach; ?>
</ul>