<?php
/**
Template Page for the gallery listing

Follow variables are useable :

	$gallery     : Contain all about the gallery
	$images      : Contain all images, path, title
	$pagination  : Contain the pagination content
	$current     : Contain the selected image
	$prev/$next  : Contain link to the next/previous gallery page

 You can check the content when you insert the tag <?php var_dump($variable) ?>
 If you would like to show the timestamp of the image ,you can use <?php echo $exif['created_timestamp'] ?>
**/
?>
<?php if(!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php if(!empty($gallery)): ?>
<div class="gallery">
	<ul class="images">
	<?php $date_format = get_option('date_format'); ?>
	<?php $total = count($images); $i = 1; foreach($images as $image): ?>
	<?php if(!$image->hidden): ?>
		<?php
		$class			= class_count_attr($i, $total, array('image', 'hentry'));
		$class[]		= ($image->pid == $current->pid) ? 'active' : '';
		$title			= trim($image->alttext);
		$description	= trim($image->description);
		$created		= !empty($image->meta_data['created_timestamp']) ? date($date_format, strtotime($image->meta_data['created_timestamp'])) : '';
		?>
		<li id="image-<?php echo $image->pid ?>"<?php echo template_add_class($class); ?>>
			<a href="<?php echo $image->imageURL ?>" rel="<?php echo $gallery->name; ?>" title="<?php echo $image->alttext ?>">
				<img src="<?php echo $image->thumbURL ?>" alt="<?php echo $image->alttext ?>" title="" />
				<?php if(!empty($title)): ?>
				<h2 class="entry-title"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if(!empty($description)): ?>
				<p class="entry-summary"><?php echo esc_html($description); ?></p>
				<?php endif; ?>
				<?php if(!empty($created)): ?>
				<p class="entry-created"><?php echo esc_html($created); ?></p>
				<?php endif; ?>
			</a>
		</li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<!-- end of div .gallery -->
</div>
<?php endif; ?>