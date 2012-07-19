<?php
$top_level_id	= get_top_level_id(get_the_ID());
$pages			= wp_list_pages(array(
	'title_li'	=> '',
	'echo'		=> 0,
	'depth'		=> 1,
	'child_of'	=> $top_level_id !== 0 ? $top_level_id : -1,
	'walker'	=> new Walker_Page_Active,
));
?>
<?php if(!empty($pages)): ?>
<div id="content-nav" class="nav">
	<h2><?php echo get_the_title($top_level_id); ?></h2>
	<ul>
		<?php echo $pages; ?>
	</ul>
<!-- end of div #content-nav -->
</div>
<?php endif; ?>