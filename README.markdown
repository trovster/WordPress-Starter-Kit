#WordPress Starter Kit#

A starter kit to develop bespoke WordPress websites using a child theme. Includes a basic framework, wp-config.php and .htaccess.

The .htaccess file is based on the [HTML5 Boilerplate](http://html5boilerplate.com) and includes the WordPress permalink code.

The wp-config.php has contains configs (local, staging and live) allowing the same codebase to be used in three different environments.

##Theme##

The child theme includes basic CSS, including [normalize.css](http://necolas.github.com/normalize.css/) and JavaScript, including jQuery
and [Modernizr](http://www.modernizr.com), jQuery plugins [Cycle](http://jquery.malsup.com/cycle/) and [Fancybox](http://fancyapps.com/fancybox/)
and a small bespoke framework which loads code based on `body` classes.

###Custom Post Types###

The framework theme includes two custom post types, these are;

* Featured Boxes
* Slideshow

##Functions##

There are numerous functions which help speed up development of the theme, these are found in functions.general.php and functions.taxonomy.php.
There is bespoke archive functionality, which is used in sidebar-archive.php.

Function files can be easily loaded by using the file format function.xxx.php.