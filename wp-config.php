<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

ini_set('upload_max_filesize', '18M');
ini_set('post_max_size', '18M');
ini_set('max_execution_time', '300');

switch(strtolower($_SERVER['HTTP_HOST'])) {
	case 'example.dev':
	case 'www.example.dev':
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'db_example');
		define('DB_USER', '');
		define('DB_PASSWORD', '');
		define('ENVIRONMENT', 'local');
		
		define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content' );
		define('WP_ROOT_DIR', dirname(__FILE__));
		
		define('WP_SITEURL', 'http://example.dev');
		define('WP_HOME', 'http://example.dev');
		define('WP_CONTENT_URL', 'http://example.dev/wp-content');
		define('WP_CDN', 'http://example.dev');
		
		define('WP_DEBUG', true);
		define('SAVEQUERIES', true);
		define('WP_CACHE', false);
		define('WP_MEMORY_LIMIT', '64M');
		define('WP_POST_REVISIONS', false);
		define('WPCF7_LOAD_JS', true);
		define('WPCF7_LOAD_CSS', false);
		define('WPCF7_AUTOP', false);
		define('WPCF7_SHOW_DONATION_LINK', false);
		define('NGG_SKIP_LOAD_SCRIPTS', false);
		
		define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGES_JS', true);
		break;
	
	case 'example.staging':
		define('DB_HOST', 'localhost');
		define('DB_NAME', 'db_staging');
		define('DB_USER', '');
		define('DB_PASSWORD', '');
		define('ENVIRONMENT', 'staging');
		
		define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content' );
		define('WP_ROOT_DIR', dirname(__FILE__));
		
		define('WP_SITEURL', 'http://example.staging');
		define('WP_HOME', 'http://example.staging');
		define('WP_CONTENT_URL', 'http://example.staging/wp-content');
		define('WP_CDN', 'http://example.staging');
		
		define('WP_DEBUG', false);
		define('WP_CACHE', false);
		define('WP_MEMORY_LIMIT', '64M');
		define('WP_POST_REVISIONS', false);
		define('WPCF7_LOAD_JS', true);
		define('WPCF7_LOAD_CSS', false);
		define('WPCF7_AUTOP', false);
		define('WPCF7_SHOW_DONATION_LINK', false);
		define('NGG_SKIP_LOAD_SCRIPTS', false);
		
		define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGES_JS', true);
		break;

	default:
		define('DB_HOST', 'localhost');
		define('DB_NAME', '');
		define('DB_USER', '');
		define('DB_PASSWORD', '');
		define('ENVIRONMENT', 'live');
		
		define('WP_CONTENT_DIR', dirname(__FILE__) . '/wp-content' );
		define('WP_ROOT_DIR', dirname(__FILE__));
		
		define('WP_SITEURL', 'http://www.example.co.uk');
		define('WP_HOME', 'http://www.example.co.uk');
		define('WP_CONTENT_URL', 'http://www.example.co.uk/wp-content');
		define('WP_CDN', 'http://static.example.co.uk');
		
		define('WP_DEBUG', false);
		define('WP_CACHE', true);
		define('WP_MEMORY_LIMIT', '64M');
		define('WP_POST_REVISIONS', false);
		define('WPCF7_LOAD_JS', true);
		define('WPCF7_LOAD_CSS', false);
		define('WPCF7_AUTOP', false);
		define('WPCF7_SHOW_DONATION_LINK', false);
		define('NGG_SKIP_LOAD_SCRIPTS', false);
		
		define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
		define('ICL_DONT_LOAD_LANGUAGES_JS', true);
		break;
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'W?JGIDlog*]<PbV$Oz^UV50|4@Q!S2.JD9[2(t2]=NU0dP2t>rzBoKb`<~2u@>]S');
define('SECURE_AUTH_KEY',  'b!z ^W;jHBvrnR0ooYE&(x#1,#gf,Ya;DXh/eSO1qNe^iI}2`swJyQJ2$dqC%BVG');
define('LOGGED_IN_KEY',    'Z]X8yf/o)f2&a)!yTB%7n!W:1x*}/5M>q(@BQ{[8xHD^O{e]03W:_T6:OKhs0,45');
define('NONCE_KEY',        '[cw+^e-?hDV;2A/?|[AoVbQ?-K?=$u/ D]d$e{yW1{!SspTt:)zROZA%Kh:%}^kX');
define('AUTH_SALT',        'y)s5[[i}p V2#:_`[:M/`06}V7*Aa4zP([5f-eb6B@K2PZ]}oq{(QGr]]wp?[R+T');
define('SECURE_AUTH_SALT', 'oWbM<N 6Y/RvfuB?gK8;fhBvWx^8-3#UzIbga| 4!8cAxq-l:SnnC2=N[b;:+hs@');
define('LOGGED_IN_SALT',   'H$r&/T5#@t!~{`afP3jE](b5C2O_S]3^u?aQ@.x?QH`p5}w*GCCi)Q)8roDibhB~');
define('NONCE_SALT',       'igvYy_>`7`7A= F{Zh@M*oN S4A{Ln^AHh3DG];G]ZJKQsagf:cMu:%uwt9Pe3M=');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if(!defined('WP_DEBUG')) {
	define('WP_DEBUG', false);
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
