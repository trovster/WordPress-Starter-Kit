<?php
$options	= array(
	'base'			=> str_replace(99999, '%#%', get_pagenum_link(99999)),
	'format'		=> '?page=%#%',
	'total'			=> $wp_query->max_num_pages,
	'current'		=> max(1, get_query_var('paged')),
	'show_all'		=> false,
	'end_size'		=> 1,
	'mid_size'		=> 2,
	'prev_next'		=> true,
	'prev_text'		=> __('Previous'),
	'next_text'		=> __('Next'),
	'type'			=> 'array'
);
$pagination = paginate_links($options);
?>
<?php if(!empty($pagination)): ?>
<div class="pagination nav">
	<ul>
	<?php $i = 1; $total = count($pagination); foreach($pagination as $item): ?>
		<?php
		$class		= class_count_attr($i, $total, array());
		$class[]	= $i === $total && $options['current'] && ($options['current'] < $options['total'] || -1 == $options['total']) ? 'next' : '';
		$class[]	= $i === 1 && $options['current'] && 1 < $options['current'] ? 'previous' : '';
		$class[]	= preg_match('#current#', $item) ? 'current' : '';
		$item		= preg_replace('#\>([0-9]+)</#i', '>'.template_prefix_number('$1', 0).'</', $item);
		?>
		<li<?php echo template_add_class($class); ?>><?php echo $item; ?></li>
	<?php $i++; endforeach; ?>
	</ul>
<!-- end of div .pagination -->
</div>
<?php endif; ?>