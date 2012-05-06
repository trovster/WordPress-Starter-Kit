<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <!--<![endif]-->
<?php $classes = array(); ?>
<head>
		<meta charset="<?php bloginfo('charset'); ?>" />

                                                                         <!--:-.>
                                                                       .+syyyyssyyo.
                                                                      -yyyyys`.syyys`
                                                                      /yyyyyo `osys:
              .-//+++/:-``-----------.   .-------. --------- `-///:..-oyyyyyo---```.://+++//:-`        `-:/+++/:-`      `-:////:-.
            :syyyyyssyyyyyyysyyyyyyyy/  `oyyyyyyyo`syyyyyyyy+ys+syyysyyyyyyyyyys-+sys+//+syyyyyo.    -oyyyyyyyyyyyo. `:syyyyyyyyyys/`
           /yyyyys.   `-+yyy+.-syyyyy/   .-oyyyyyo ..:yyyyyyy-`+yyyyy-oyyyyyo-.-oyyyyo.   -yyyyys   +yyyyys-` ./syyy/syyyys-``.+yyyyy:
           oyyyyyy+:-.`  .::.  oyyyyy/     +yyyyyo   `syyyyy/ `oyyyy/ +yyyyyo   :syyys.   .yyyyyy` /yyyyyy.  -yyyyyyyyyyyy:    `syyyyy.
           :yyyyyyyyyyyso/-`   oyyyyy/     +yyyyyo   `syyyyy:   .--`  +yyyyyo    `.--.-:/oyyyyyyy` syyyyyo   .syyyyyyyyyyyssssssyyyyyy/
            ./oyyyyyyyyyyyys/  oyyyyy/     +yyyyyo   `syyyyy:         +yyyyyo   ./osyyyyo/syyyyyy``syyyyyo    `-:-./yyyyyy+///////////-
           ://- .-:/osyyyyyyy: oyyyyy/     +yyyyyo   `syyyyy:         +yyyyyo  -yyyyo/.` :yyyyyyy` syyyyys`     -::+yyyyyy-      `----`
           oyyy/`     :yyyyyy: +yyyyys`   -syyyyyo   .syyyyy:        `oyyyyyo` +yyyyy+.`.syyyyyyy..syyyyyy+`   .syys+yyyyyo.    .oyyy+
           oyyyyys+++osyyyys/  -syyyyyyooss+yyyyyyssssyyyyyyyss/   :ssyyyyyyysssyyyys/sooyysyyyyyyyysoyyyyysoooyyys- -syyyyysoosyyys/
           /oo--/osyyyyso+:`    ./osyyso+-`.+++++++o+++++++++++/   -++++++++++++-/oyyyyso:``:oyyyys/` .:+syyyyys+-`    ./osyyyysa-->


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

		<!-- title, description, etc... -->
		<?php $desc = get_bloginfo('description'); ?>
		<title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?><?php echo (!empty($desc)) ? ' - ' . $desc : ''; ?></title>

		<script type="text/javascript" src="<?php  echo get_stylesheet_directory_uri(); ?>/js/modernizr-2.5.3.js"></script>
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

			<div id="header" class="header">
				<div class="inner">
					<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
					<?php if(!empty($desc)): ?>
					<h2><?php echo $desc; ?></h2>
					<?php endif; ?>
				<!-- end of div .inner -->
				</div>
			<!-- end of div #header -->
			</div>

			<div id="nav" class="nav">
				<div class="inner">
					<?php get_template_part('nav'); ?>
				<!-- end of div .inner -->
				</div>
			<!-- end of div #nav -->
			</div>

			<div id="content" class="content section">
				<div class="inner">