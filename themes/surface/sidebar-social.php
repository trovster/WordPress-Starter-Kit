<div class="social-icons">
	<ul>
		<?php if(Surface_Settings::has_social('twitter')): ?>
		<li class="twitter"><a href="<?php echo Surface_Settings::get_social('twitter'); ?>" rel="external" title="Follow <?php bloginfo('name'); ?> on “Twitter”"><span></span>Twitter</a></li>
		<?php endif; ?>
		<?php if(Surface_Settings::has_social('facebook')): ?>
		<li class="facebook"><a href="<?php echo Surface_Settings::get_social('facebook'); ?>" rel="external" title="Follow <?php bloginfo('name'); ?> on “Facebook”"><span></span>Facebook</a></li>
		<?php endif; ?>
		<?php if(Surface_Settings::has_social('youtube')): ?>
		<li class="youtube"><a href="<?php echo Surface_Settings::get_social('youtube'); ?>" rel="external" title="Follow <?php bloginfo('name'); ?> on “YouTube”"><span></span>YouTube</a></li>
		<?php endif; ?>
		<li class="l rss"><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to the <?php bloginfo('name'); ?> RSS Feed"><span></span>RSS</a></li>
	</ul>
<!-- end of div .social-icons -->
</div>