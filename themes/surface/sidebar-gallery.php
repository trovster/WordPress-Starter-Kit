<div id="content-secondary">
	
	<?php if(class_exists('NextGEN_shortcodes')): ?>
	<?php
	// make sure this plays nicely with the gallery already shown
	set_query_var('gallery_id', get_query_var('gallery'));
	unset($GLOBALS['nggShowGallery']);
	unset($wp_query->query_vars['gallery']);
	?>
	<?php echo do_shortcode('[album 1 template=navigation]'); ?>
	<?php endif; ?>
	
<!-- end of div #content-secondary -->
</div>