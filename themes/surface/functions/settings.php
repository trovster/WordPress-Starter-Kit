<?php

/*   
Component: Settings
Description: WordPress setting options
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/
class Surface_Settings {
	
	public static $social_networks = array(
		'twitter' => array(
			'id'	=> 'social_twitter',
			'title'	=> 'Twitter URL',
		),
		'facebook' => array(
			'id'	=> 'social_facebook',
			'title'	=> 'Facebook URL',
		),
		'youtube' => array(
			'id'	=> 'social_youtube',
			'title'	=> 'YouTube URL',
		),
	);
	
	/**
	 * register
	 * @desc	Add the settings menu and initialise the settings
	 */
	public function register() {
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'admin_init'));
	}
	
	/**
	 * admin_menu
	 * @desc	Add the option pages
	 */
	public function admin_menu() {
		add_options_page('Social Network Settings', 'Social Networks', 'manage_options', 'surface_settings_social_page', array($this, 'render_social_page'));
	}
	
	/**
	 * admin_init
	 * @desc	Register the settings
	 */
	public function admin_init() {
		// completely new page
		add_settings_section(
			'surface_settings_social_page',
			'Social Network Settings',
			array($this, 'render_social_section'),
			'surface_settings_social_page'
		);

		// all the social network fields and settings
		foreach(self::$social_networks as $network) {
			$label = sprintf('<label for="%s">%s</label>', $network['id'], $network['title']);
			add_settings_field($network['id'], $label, array($this, 'render_social_section_field'), 'surface_settings_social_page', 'surface_settings_social_page', $network);
			register_setting('surface_settings_social_page', $network['id']);
		}
	}
	
	/**
	 * render_section
	 * @desc	Section introduction
	 * @param	array	$args
	 */
	public function render_social_section() {
		echo '<p class="description">Add the social network URLs which you want to use.</p>';
	}
	
	/**
	 * render_social_section_field
	 * @desc	Input fields
	 * @param	array	$args
	 */
	public function render_social_section_field($args) {
		echo sprintf('<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $args['id'], esc_attr(get_option($args['id'])));
	}
	
	/**
	 * render_social_page
	 * @desc	Page which displays the social network settings
	 */
	public function render_social_page() {
		?>
		<div class="wrap">
			<h2>Social Settings</h2>
			<form action="options.php" method="post">
				<?php settings_fields('surface_settings_social_page'); ?>
				<?php do_settings_sections('surface_settings_social_page'); ?>
				<?php submit_button('Save Settings'); ?>
			</form>
		</div>
		<?php
	}
	
	/**
	 * has_social
	 * @desc	Checks whether a social network option has been set
	 * @example	Surface_Settings::has_social('twitter'); // true or false
	 * @param	string	$key
	 * @return	boolean
	 */
	public static function has_social($key) {
		$value = '';
		
		if(array_key_exists($key, self::$social_networks)) {
			$value = get_option(self::$social_networks[$key]['id'], false);
		}
		
		return !empty($value) ? true : false;
	}
	
	/**
	 * get_social
	 * @desc	Returns the social network option, if it was set
	 * @example	Surface_Settings::has_social('twitter'); // http://twitter.com/username
	 * @param	string	$key
	 * @return	string
	 */
	public static function get_social($key) {
		return self::has_social($key) ? get_option(self::$social_networks[$key]['id']) : '';
	}
	
}

/**
 * Hook in to WordPress
 */
if(class_exists('Surface_Settings')) {
	$surface_settings = new Surface_Settings();
	$surface_settings->register();
}