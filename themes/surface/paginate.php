<?php global $wp_query;
$options	= array(
	'base'			=> str_replace(99999, '%#%', get_pagenum_link(99999)),
	'format'		=> '?page=%#%',
	'total'			=> $wp_query->max_num_pages,
	'current'		=> max(1, get_query_var('paged')),
	'show_all'		=> false,
	'end_size'		=> 1,
	'mid_size'		=> 2,
	'prev_next'		=> true,
	'prev_text'		=> _site_is_section('news') ? 'Newer Articles' : (is_search() || _site_is_section('events') ? 'Previous' : 'Newer'),
	'next_text'		=> _site_is_section('news') ? 'Older Articles' : (is_search() || _site_is_section('events') ? 'Next' : 'Older'),
	'type'			=> 'array'
);
$pagination = paginate_links($options);
?>
<?php if(!empty($pagination)): ?>
<div class="pagination nav">
	<ol>
	<?php $i = 1; $total = count($pagination); foreach($pagination as $item): ?>
		<?php
		$class		= class_count_array($i, $total, array());
		$class[]	= $i === $total && $options['current'] && ($options['current'] < $options['total'] || -1 == $options['total']) ? 'next' : '';
		$class[]	= $i === 1 && $options['current'] && 1 < $options['current'] ? 'previous' : '';
		$class[]	= preg_match('#current#', $item) ? 'current' : '';
		?>
		<li class="<?php echo implode(' ', $class); ?>"><?php echo $item; ?></li>
	<?php $i++; endforeach; ?>
	</ol>
<!-- end of div .pagination -->
</div>
<?php endif; ?>