				<!-- end of div .inner -->
				</div>
			<!-- end of div #content -->
			</div>

			<div id="footer">
				<div class="inner">
					
					<p>Footer</p>
					
					<div id="footer-by" class="vcard"><a href="http://www.who.co.uk" class="url"><span class="role">Website design</span> &#38; <span class="role">development</span> by <strong class="fn org">Organisation Name</strong></a></div>
					
				<!-- end of div .inner -->
				</div>
			<!-- end of div #footer -->
			</div>

		<!-- end of div #container -->
		</div>
	<!-- end of div #wrapper -->
	</div>
	
	<?php wp_footer(); ?>

	<?php
	if(function_exists('yoast_analytics') && defined('ENVIRONMENT') && constant('ENVIRONMENT') === 'live') {
		yoast_analytics();
	}
	?>
	
	<!-- social media buttons -->
	<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

	<div id="fb-root"></div>
	<script type="text/javascript">
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	</body>
</html>