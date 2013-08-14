				<!-- end of div .inner -->
				</div>
			<!-- end of div #content -->
			</div>
			
			<?php echo Classy::loop(Classy_Logo::get_options(), 'loop', 'logo'); ?>

			<div id="footer" role="contentinfo">
				<div class="inner">
					
					<p>Copyright <?php echo date('Y'); ?></p>
					
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

	</body>
</html>