<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 lt-ie10 lt-ie9 lt-ie8 lt-ie7 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 lt-ie10 lt-ie9 lt-ie8 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 lt-ie10 lt-ie9 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9 lt-ie10 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <!--<![endif]-->
<?php $classes = array(); ?>
<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		
		<!-- www.phpied.com/conditional-comments-block-downloads/ -->
		<!--[if IE]><![endif]-->

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<!-- site created by... -->
		<meta name="author" lang="en" content="Surface" />
		<meta name="language" content="en" />
		<meta name="robots" content="index,follow" />
		<meta name="revisit-after" content="28 days" />
		<meta http-equiv="imagetoolbar" content="false" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<!-- title, description, etc... -->
		<?php $desc = get_bloginfo('description'); ?>
		<title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?><?php echo (!empty($desc)) ? ' - ' . $desc : ''; ?></title>

		<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/modernizr-2.6.1.js"></script>
    	<script type="text/javascript" charset="utf-8">
	      if (typeof Modernizr === 'undefined') {
	          var docElement = document.documentElement;
	          docElement.className = docElement.className.replace(/(^|\s)no-js(\s|$)/, ' js ');
	      };
	    </script>

	    <!-- icons -->
		<link href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="<?php echo get_stylesheet_directory_uri(); ?>/apple-touch-icon.png" rel="apple-touch-icon" />

		<?php wp_head(); ?>
	</head>

	<body <?php body_class($classes); ?>>

	<div id="wrapper">
		<div id="container">

			<div id="header" class="header" role="banner">
				<div class="inner">
					<h1><a href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a></h1>

					<div class="section" id="header-contact">
						<p class="tel"><abbr class="type" title="Telephone">T:</abbr> <em class="value">01234 567 890</em></p>
						<p class="email"><abbr class="type" title="Email">E:</abbr> <a class="value" href="mailto:hello@example.com">hello@example.com</a></p>
					<!-- end of div #header-contact -->
					</div>
						
					<?php if(!empty($desc)): ?>
					<h2><?php echo $desc; ?></h2>
					<?php endif; ?>
					
				<!-- end of div .inner -->
				</div>
			<!-- end of div #header -->
			</div>
					
			<?php get_template_part('navigation'); ?>
					
			<?php get_template_part('banner'); ?>
			
			<div id="content" class="content section">
				<div class="inner">