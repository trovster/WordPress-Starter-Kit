<?php if(defined('ENVIRONMENT') && constant('ENVIRONMENT') !== 'dev'): ?>
<?php $link = get_bloginfo('url'); // get_permalink(); ?>
<ul class="social-buttons">
	<li class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $link; ?>">Tweet</a></li>
	<li class="facebook"><div class="fb-like" data-href="<?php echo $link; ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true" data-font="arial"></div></li>
<!-- end of ul .social-buttons -->
</ul>
<?php endif; ?>