<?php 
/**
Template Page for the album overview

Follow variables are useable :

	$album     	 : Contain information about the album
	$galleries   : Contain all galleries inside this album
	$pagination  : Contain the pagination content

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/
?>
<?php if(!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php if(!empty ($galleries)): ?>
<div class="nav">
	<ul>
	<?php $gallery_id = get_query_var('gallery_id'); ?>
	<?php $total = count($galleries); $i = 1; foreach($galleries as $gallery): ?>
		<?php
		$class		= class_count_attr($i, $total, array());
		$class[]	= $gallery_id === $gallery->gid ? 'active' : '';
		?>
		<li<?php echo template_add_class($class); ?>>
			<a href="<?php echo $gallery->pagelink ?>" rel="bookmark" class="url"><?php echo esc_html($gallery->title); ?></a>
		</li>
	<?php $i++; endforeach; ?>
	</ul>
<!-- end of div .nav -->
</div>
<?php endif; ?>