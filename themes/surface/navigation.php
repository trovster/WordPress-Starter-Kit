<?php $navigation = _site_get_navigation(); ?>
<?php $i = 1; $total = count($navigation); ?>

<div id="nav" class="nav" role="navigation">
	<div class="inner">
		<ul>
		<?php foreach($navigation as $nav_key => $nav_item): ?>
			<?php
			$classes	= class_count_array($i, $total, $nav_item['class']);
			$classes	= _site_is_section($nav_key) ? array_merge(array('active'), $classes) : $classes;
			$pages		= null;
			if(!is_null($nav_item['page_id'])) {
				$pages = wp_list_pages(array(
					'title_li'	=> '',
					'echo'		=> 0,
					'depth'		=> 1,
					'child_of'	=> $nav_item['page_id']
				));
			}
			$classes[] = !empty($pages) ? 'has-children' : '';
			?>
			<li class="<?php echo implode(' ', $classes); ?>"><a href="<?php echo $nav_item['href']; ?>"><?php echo $nav_item['text']; ?></a><?php !empty($pages) ? printf('<ul class="children">%s</ul>', $pages) : ''; ?></li>
		<?php $i++; endforeach; ?>
		</ul>
	<!-- end of div .inner -->
	</div>
</div>