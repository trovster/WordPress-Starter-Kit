<?php
$taxonomy	= 'category';
$options	= array(
	'title_li'			=> '',
	'hide_empty'		=> true,
	'echo'				=> 0,
	'show_count '		=> false,
	'hierarchical'		=> false,
	'taxonomy'			=> $taxonomy,
	'depth'				=> 1, // only top level
	'show_option_none'	=> false, //'No categories.'
);
$total		= count(get_terms($taxonomy, $options));
$categories = wp_list_categories($options);
?>
<?php if(!empty($categories)): ?>
<div id="taxonomy-categories" class="section taxonomy">
	<h3>Categories <span class="total"><?php echo $total; ?></span></h3>
	<ul>
		<?php echo $categories; ?>
	</ul>
<!-- end of div #taxonomy-categories -->
</div>
<?php endif; ?>

<?php
$taxonomy	= 'post_tag';
$options	= array(
	'title_li'			=> '',
	'hide_empty'		=> true,
	'echo'				=> 0,
	'show_count '		=> false,
	'hierarchical'		=> false,
	'taxonomy'			=> $taxonomy,
	'depth'				=> 1, // only top level
	'show_option_none'	=> false, //'No tags.'
);
$total		= count(get_terms($taxonomy, $options));
$tags		= wp_list_categories($options);
?>
<?php if(!empty($tags)): ?>
<div id="taxonomy-tags" class="section taxonomy">
	<h3>Tags <span class="total"><?php echo $total; ?></span></h3>
	<ul>
		<?php echo $tags; ?>
	</ul>
<!-- end of div #taxonomy-tags -->
</div>
<?php endif; ?>