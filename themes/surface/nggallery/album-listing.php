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

<?php if(!empty($galleries)): ?>
<div class="galleries">
	<h1>Select a Gallery</h1>
	
	<ul class="images">
	<?php $total = count($galleries); $i = 1; foreach($galleries as $gallery): ?>
		<?php
		$class			= class_count_attr($i, $total, array('gallery', 'hentry'));
		$title			= trim($gallery->title);
		$description	= trim($gallery->galdesc);
		?>
		<li id="gallery-<?php echo $image->pid ?>"<?php echo template_add_class($class); ?>>
			<a href="<?php echo $gallery->pagelink ?>" rel="bookmark" class="url">
				<img src="<?php echo $gallery->previewurl ?>" alt="" title="" />
				<h2 class="entry-title"><?php echo esc_html($title); ?></h2>
				<?php if(!empty($description)): ?>
				<p class="entry-summary"><?php echo esc_html($description); ?></p>
				<?php endif; ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
<!-- end of div .galleries -->
</div>
<?php endif; ?>