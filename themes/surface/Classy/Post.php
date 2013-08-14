<?php require_once(dirname(__FILE__) . '/Classy.php');

/**
 * Classy_Post
 * @desc	
 */

class Classy_Post extends Classy {
	
	protected $_post_type	= 'post';
	
	public $categories = null;

	/**
	 * __construct
	 * @desc	
	 * @param	array	$options
	 * @return	\Classy_Post
	 */
	public function __construct($options = array()) {
		parent::__construct($options);
		
		if($options === 'initialize') {
			add_action('admin_init',		array($this, 'action_admin_init_remove_taxonomy_menu'));
			add_action('admin_menu',		array($this, 'action_admin_menu_remove_taxonomy_boxes'));
			add_action('do_meta_boxes',		array($this, 'action_do_meta_boxes_remove_featured_image_box'));
			
			add_shortcode('latest_news',	array(&$this, 'shortcode_latest_news'));
		}
	
		return $this;
	}
	
	/**
	 * init_register_post_type
	 * @desc	Register the post type, for custom post types.
	 */
	public function init_register_post_type() {}
	
	/**
	 * init_register_taxonomies
	 * @desc	Register any taxonomies.
	 */
	public function init_register_taxonomies() {}
	
	/**
	 * init_register_images
	 * @desc	Register any image sizes.
	 *			Can also be used to setup multiple images.
	 */
	public function init_register_images() {
		add_image_size($this->get_post_type() . '-thumbnail', 300, 300, true);
		add_image_size($this->get_post_type() . '-large', 1024, 768, true);
	}
	
	/**
	 * get_options
	 * @desc	Options for WP_Query.
	 * @param	array	$options
	 * @return	array
	 */
	public static function get_options($options = array()) {
		return array_merge(array(
			'post_type'			=> 'post',
			'orderby'			=> 'date',
			'order'				=> 'DESC',
			'title_li'			=> '',
			'echo'				=> 0,
			'depth'				=> 1,
			'posts_per_page'	=> 10,
		), $options);
	}
	
	/**
	 * action_admin_menu_remove_taxonomy_boxes
	 * @desc	Remove the Category and Tags boxes from posts.
	 */
	public function action_admin_menu_remove_taxonomy_boxes() {
//		remove_meta_box('categorydiv', $this->get_post_type(), 'side');
		remove_meta_box('tagsdiv-post_tag', $this->get_post_type(), 'side');
	}
	
	/**
	 * action_do_meta_boxes_remove_featured_image_box
	 * @desc	Remove the Featured image box from posts.
	 */
	public function action_do_meta_boxes_remove_featured_image_box() {
		remove_meta_box('postimagediv', $this->get_post_type(), 'side');
	}
	
	/**
	 * action_admin_init_remove_taxonomy_menu
	 * @desc	Remove the Category and Tags boxes from the menu.
	 */
	public function action_admin_init_remove_taxonomy_menu() {
//		remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
		remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
	}
	
	
	/*********************************************************
	 * =WordPress Methods
	 * @desc	General WordPress methods.
	 *********************************************************/
	
	/**
	 * _short_url
	 * @param	string	$url
	 * @param	boolean	$removeWWW
	 * @return	string
	 */
	protected static function _short_url($url, $removeWWW = true) {
		return preg_replace('#https?://' . ($removeWWW ? '(www\.)?' : '') . '#', '', $url);
	}
	
	
	/*********************************************************
	 * =Common Methods
	 * @desc	Useful common methods.
	 *********************************************************/

	/**
	 * get_top_level_id
	 * @desc	
	 * @param	int		$parent_id
	 * @param	int		$level
	 * @return	int
	 */
	public function get_top_level_id($parent_id = NULL, $level = 1) {
		return _site_get_nav_item_id('news');
	}
	
	/**
	 * get_the_navigation
	 * @desc	
	 * @param	boolean		$include_top_level
	 * @return	string
	 */
	public function get_the_navigation($include_top_level = true) {
		$top_level_id	= $this->get_top_level_id();
//		$current_term	= get_queried_object();
		$html			= wp_list_pages(array(
			'title_li'	=> '',
			'echo'		=> 0,
			'depth'		=> 1,
			'child_of'	=> $top_level_id !== 0 ? $top_level_id : -1,
			'exclude'	=> $include_top_level === true ? $top_level_id : 0,
			'walker'	=> new Walker_Page_Active,
		));
//		$categories	= get_terms('category', array(
//			'orderby'		=> 'name',
//			'order'			=> 'ASC',
//			'hierarchical'	=> false,
//			'hide_empty'	=> false,
//			'exclude'		=> 1,
//		));
//		$posts		= get_posts(array(
//			'post_type'			=> $this->get_post_type(),
//			'orderby'			=> 'menu_order',
//			'order'				=> 'ASC',
//			'posts_per_page'	=> -1,
//		));
//		foreach($posts as $the_post) {
//			$the_post	= self::find_by_id($the_post->ID);
//			$class		= $the_post->get_the_id() === $this->get_the_ID() && $this->get_the_ID() !== 0 ? $the_post->get_attr_classes(array('active')) : $the_post->get_attr_classes();
//			$html	   .= sprintf('<li %s><a href="%s">%s</a></li>', sprintf('class="%s"', implode(' ', $class)), $the_post->get_permalink(), $the_post->get_the_title());
//		}
//		foreach($categories as $category) {
//			$classes	= array('category', 'category-' . $category->term_id);
//			$class		= is_object($current_term) && !empty($current_term->term_id) && $current_term->term_id === $category->term_id ? array_merge($classes, array('active')) : $classes;
//			$html	   .= sprintf('<li %s><a href="%s">%s</a></li>', sprintf('class="%s"', implode(' ', $class)), get_term_link($category), $category->name);
//		}
		
		$class			= array('f', 'section-title', 'page_item', sprintf('page-item-%d', $top_level_id));
		$class[]		= _site_is_section($top_level_id) && !is_category() && !is_single() ? 'active' : '';
		
		$top_level_li	= $include_top_level === true ? sprintf('<li %s><a href="%s">%s</a></li>', sprintf('class="%s"', implode(' ', $class)), get_permalink($top_level_id), get_the_title($top_level_id)) : '';
		$navigation		= sprintf('<ul>%s%s</ul>', $top_level_li, $html);
		
		return !empty($navigation) && strlen($navigation) > 9 ? sprintf('<div class="section section-navigation nav">%s</div>', $navigation) : '';
	}
	
	/**
	 * get_attr_classes
	 * @origin	get_post_class
	 * @desc	Get the post class, with any optional classes passed as an option.
	 * @param	array	$classes
	 * @return	array
	 */
	public function get_attr_classes($classes = array()) {
		$classes = parent::get_attr_classes($classes);
		
		if($this->has_permalink()) {
			$classes[] = 'has-link';
		}
		else {
			$classes[] = 'has-no-link';
		}
		
		return $classes;
	}
	
	/**
	 * has_categories
	 * @desc	
	 * @return	boolean 
	 */
	public function has_categories() {
		$this->get_categories();
		
		if(!empty($this->categories)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * has_category
	 * @desc	
	 * @return	boolean 
	 */
	public function has_category() {
		return $this->has_categories();
	}
	
	/**
	 * get_categories
	 * @desc	
	 * @return	boolean|array
	 */
	public function get_categories() {
		if(!is_null($this->categories)) {
			return $this->categories;
		}
		else {
			$this->categories = get_the_terms($this->post->ID, 'category');
			return $this->categories;
		}
		
		return false;
	}
	
	/**
	 * get_category
	 * @desc	
	 * @param	string	$default
	 * @param	boolean	$string
	 * @return	string|object 
	 */
	public function get_category($default = '', $string = false) {
		return self::get_single_taxonomy($this->post->ID, 'category', $default, $string);
	}
	
	/**
	 * shortcode_latest_news
	 * @desc	
	 * @param	array	$atts
	 * @return	string
	 */
	public function shortcode_latest_news($atts) {
		$args = shortcode_atts(array(
			'posts_per_page'	=> 3
		), $atts);
		
		return Classy::loop(Classy_Post::get_options($args), 'loop', 'post-latest');
	}
	
	
	/*********************************************************
	 * =Admin Listing
	 * @desc	Default actions and filters called for
	 *			listing of columns on the admin area.
	 *********************************************************/
	
	/**
	 * filter_manage_column_listing
	 * @desc	Add extra columns to the admin listing screen.
	 * @param	array	$columns
	 * @return	array
	 */
	public function filter_manage_column_listing($columns) {
//		unset($columns['categories']);
		unset($columns['tags']);
		unset($columns['comments']);
		
		$columns = array_merge(array(
			'cb'					=> $columns['cb'],
			'title'					=> $columns['title'],
		), $columns);
		
		return $columns;
	}
	
	/**
	 * filter_manage_column_sorting
	 * @desc	Sort any columns on the admin listing screen.
	 * @param	array	$columns
	 * @return	array
	 */
	public function filter_manage_column_sorting($columns) {
		return $columns;
	}
	
	/**
	 * action_manage_column_value
	 * @desc	Output the values for the extra columns.
	 * @param	string	$column
	 * @param	int		$post_id
	 */
	public function action_manage_column_value($column, $post_id) {
		$classy_post	= self::find_by_id($post_id);
		
		switch($column) {}
	}
	
	
	/*********************************************************
	 * =Admin Boxes
	 * @desc	Default actions and filters called for adding
	 *			extra content / boxes in the admin area.
	 *********************************************************/

	/**
	* action_admin_init_meta_box
	* @desc		Assign the meta box.
	*/
	public function action_admin_init_meta_box()  {}
	
	
	/*********************************************************
	 * =Finding Methods
	 * @desc	Turn the basic data in to Classy objects.
	 *********************************************************/
	
	/**
	 * find_by_slug
	 * @desc	Find a post by 'slug'.
	 * @param	string	$slug
	 * @return	mixed 
	 */
	public static function find_by_slug($slug, $post_type = 'post') {
		return parent::find_by_slug($slug, $post_type);
	}

}

/**
 * Hook in to WordPress
 */
if(class_exists('Classy_Post')) {
	$classy_post = new Classy_Post('initialize');
}