<?php
$archives = '';
if(function_exists('smart_archives')) {
	$archives = smart_archives(get_the_ID(), false);
}
else {
	$archives = wp_get_archives(array(
		'type'				=> 'monthly',
		'format'			=> 'html',
		'show_post_count'	=> false,
		'echo'				=> false
	));
	if(!empty($archives)) {
		$archives = '<ol>' . $archives . '</ol>';
	}
}
?>

<?php if(!empty($archives)): ?>
<div id="archive" class="section archive">
	<h3><?php _e('Archive'); ?></h3>
	
	<div class="nav">
		<?php echo $archives; ?>
	<!-- end of div .nav -->
	</div>
<!-- end of div id #archive -->
</div>
<?php endif; ?>