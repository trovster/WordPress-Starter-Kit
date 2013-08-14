<?php require_once(dirname(__FILE__) . '/Classy.php');

/**
 * Classy_Sidebar
 * @desc	
 */

class Classy_Sidebar extends Classy {
	
	protected $_post_type	= 'sidebar';

	/**
	 * __construct
	 * @desc	
	 * @param	array	$options
	 * @return	\Classy_Sidebar
	 */
	public function __construct($options = array()) {
		parent::__construct($options);

		return $this;
	}
	
	/**
	 * init_register_post_type
	 * @desc	Register the post type, for custom post types.
	 */
	public function init_register_post_type() {
		register_post_type($this->get_post_type(), array(
			'labels' => array(
				'name'					=> 'Sidebars',
				'singular_name'			=> 'Sidebar',
				'add_new'				=> 'Add New Sidebars',
				'add_new_item'			=> 'Add New Sidebar',
				'edit'					=> 'Edit Sidebars',
				'edit_item'				=> 'Edit Sidebar',
				'new_item'				=> 'New Sidebar',
				'view'					=> 'View Sidebars',
				'view_item'				=> 'View Sidebar',
				'search_items'			=> 'Search Sidebars',
				'not_found'				=> 'No Sidebars found',
				'not_found_in_trash'	=> 'No Sidebars found in Trash',
				'parent'				=> 'Parent Sidebar',
			),
			'description'			=> 'Sidebars',
			'capability_type'		=> 'post',
			'public'				=> true,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'has_archive'			=> false,
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'register_meta_box_cb'	=> false,
			'taxonomies'			=> array(),
			'supports'				=> array(
				'title',
				'editor',
//				'author',
				'thumbnail',
//				'excerpt',
//				'trackbacks',
//				'custom-fields',
//				'comments',
//				'revisions',
//				'page-attributes',
//				'post-formats',
			),
		));
	}
	
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
		add_image_size($this->get_post_type(), 206, 600, true);
	}
	
	/**
	 * get_options
	 * @desc	Options for WP_Query.
	 * @param	array	$options
	 * @return	array
	 */
	public static function get_options($options = array()) {
		return array_merge(array(
			'post_type'			=> 'sidebar',
			'orderby'			=> 'menu_order',
			'order'				=> 'ASC',
			'title_li'			=> '',
			'echo'				=> 0,
			'depth'				=> 1,
			'posts_per_page'	=> -1,
			'meta_query'		=> array(),
		), $options);
	}
	
	/**
	* pre_get_posts
	* @desc	Restrict posts
	*/
	public function pre_get_posts(&$query) {
		parent::pre_get_posts($query);

		if(!empty($query->query_vars) && !empty($query->query_vars['post_type']) && $query->query_vars['post_type'] === $this->get_post_type() && !is_admin()) {
			$query->query_vars['meta_query'] = array_merge($query->query_vars['meta_query'], array());
		}
	}
	
	
	/*********************************************************
	 * =WordPress Methods
	 * @desc	General WordPress methods.
	 *********************************************************/
	
	/**
	 * get_permalink
	 * @desc	Checks whether a permalink is set for this post type.
	 * @return	boolean
	 */
	public function has_permalink() {
		return $this->has_custom_value('link');
	}

	/**
	 * get_permalink
	 * @desc	
	 * @param	boolean	$leavename
	 * @return	string
	 */
	public function get_permalink($leavename = false) {
		return $this->has_permalink() ? $this->get_custom_value('link') : '#';
	}

	
	/**
	 * _short_url
	 * @param	string	$url
	 * @param	boolean	$removeWWW
	 * @return	string
	 */
	protected static function _short_url($url, $removeWWW = true) {
		return preg_replace('#https?://' . ($removeWWW ? '(www\.)?' : '') . '#', '', $url);
	}
	
	/**
	 * has_items
	 * @desc	
	 * @param	$the_post	object
	 * @param	$options	array
	 * @return	boolean
	 */
	public static function has_items($the_post = null, $options = null) {
		if(!is_array($options)) {
			$options = self::get_options();
		}
		
		$items		= self::get_items($options);
		$default	= self::get_default_items($the_post);
		
		return count($items) + count($default);
	}
	
	/**
	 * get_items
	 * @desc	
	 * @param	array	$options
	 * @return	array
	 */
	public static function get_items($options) {
		return get_posts($options);
	}
	
	/**
	 * get_default_items()
	 * @desc	Dynamic sidebars, such as latest news.
	 *			Or 'same all the time' such as social links.
	 * @param	$the_post	object
	 * @return	array
	 */
	public static function get_default_items($the_post = null) {
		$options = array(
			'announcement' => array(
				'label'	=> 'Announcement'
			),
			'calendar' => array(
				'label'	=> 'Calendar'
			),
			'post-latest' => array(
				'label'	=> 'Latest News × 3 (Feed)'
			),
		);
		
		if(is_page_template('page-section-landing.php')) {
			unset($options['announcement']);
		}
		
		if(!is_null($the_post) && is_object($the_post)) {
			// find the sidebars this post has
			foreach($options as $key => $option) {
				if(!$the_post->get_custom_value_boolean($key . '_enabled')) {
					unset($options[$key]); // unset any unused sidebars for the post
				}
			}
		}
		
		return $options;
	}

	/**
	 * get_ids
	 * @desc	Returns an array of sidebar IDs, on a per page basis
	 * @return	array
	 */
	public static function get_ids($the_post) {
		$sidebar_ids	= $the_post->get_custom_value('sidebar_ids');
		$sidebar_ids	= is_array($sidebar_ids) ? $sidebar_ids : unserialize($sidebar_ids);
		$sidebar_ids	= is_array($sidebar_ids) ? array_filter($sidebar_ids, array('Classy_Sidebar', '_array_filter_ids')) : array();

		return $sidebar_ids;
	}
	
	/**
	 * _array_filter_ids
	 * @desc	Filter the IDs, removing the false values
	 * @param	string	$item
	 * @return	boolean
	 */
	private static function _array_filter_ids($item) {
		return $item === 'false' ? false : true;
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
		return -1;
	}
	
	/**
	 * get_the_navigation
	 * @desc	
	 * @param	boolean		$include_top_level
	 * @return	string
	 */
	public function get_the_navigation($include_top_level = true) {
		return '';
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
		$columns = array_merge(array(
			'cb'					=> $columns['cb'],
			'title'					=> $columns['title'],
			'sidebar_thumbnail'		=> 'Image',
			'sidebar_link'			=> 'Link',
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
		
		switch($column) {
			case 'sidebar_thumbnail':
				echo $classy_post->has_thumbnail() ? $classy_post->get_thumbnail('thumb') : '-';
				break;
			
			case 'sidebar_link':
				echo $classy_post->has_permalink() ? $classy_post->get_permalink() : '-';
				break;
		}
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
	public function action_admin_init_meta_box()  {
		$post_types = array('page');

		if(self::has_items() > 0) {
			foreach($post_types as $post_type) {
				add_meta_box($this->get_post_type() . '_sidebar_list', 'Sidebar Items', array(&$this, 'meta_box_page_sidebar_list'), $post_type, 'advanced', 'high');
			}
		}
		
		add_meta_box($this->get_post_type() . '_link', 'Link', array($this, 'meta_box_page_link'), $this->get_post_type(), 'advanced', 'high');
	}
	
	/**
	 * meta_box_page_content_column
	 * @desc	Custom meta box, which is an WYSIWYG editor.
	 * @param	object	$post
	 */
	public function meta_box_page_link($post) {
		$classy_page = self::find_by_id($post->ID);
		
		echo self::_meta_field_html($classy_page->get_custom_value('link'), 'link', 'URL', true, 'text');
	}
	
	/**
	 * meta_box_page_details
	 * @desc	Custom meta boxes.
	 * @param	object	$post
	 */
	public function meta_box_page_details($post) {}
	
	/**
	 * meta_box_page_sidebar_list
	 * @desc	List the sidebar items as checkboxes for assignment.
	 * @param	object	$post
	 */
	public function meta_box_page_sidebar_list($post) {
		$items		= array();
		$the_post	= self::find_by_id($post->ID);

		// loop through the 'static' sidebar options
		foreach(self::get_default_items() as $key => $options) {
			$id			= $key . '_enabled';
			$name		= '_site_' . $id; 
			$value		= 'true';
			$checked	= $the_post->get_custom_value_boolean($id) ? true : false;
			$label		= $options['label'];
//			
			$input		= '<input type="hidden" name="' . $name . '" value="false" />';
			$input     .= '<input id="' . $key . '" value="' . $value . '" type="checkbox" name="' . $name . '"' . ($checked ? ' checked="checked"' : '') . ' />';
			$label		= '<label for="' . $key . '">' . $label . '</label>' . "\r\n";
			
			$items[]	= $input . $label;
		}

		// currently selected/enabled sidebars
		$sidebar_ids = self::get_ids($the_post);

		// loop through the user generated sidebars
		foreach(self::get_items(self::get_options()) as $sidebar) {
			$sidebar	= self::find_by_id($sidebar->ID);
			
			$id			= 'sidebar_id_' . $sidebar->get_the_ID();
			$name		= '_site_sidebar_ids[]';
			$value		= $sidebar->get_the_ID();
			$checked	= is_array($sidebar_ids) ? in_array($value, $sidebar_ids) : false;
			$label		= $sidebar->get_the_title();
//			
			$input		= '<input type="hidden" name="' . $name . '" value="false" />';
			$input     .= '<input id="' . $id . '" value="' . $value . '" type="checkbox" name="' . $name . '"' . ($checked ? ' checked="checked"' : '') . ' />';
			$label		= '<label for="' . $id . '">' . $label . '</label>' . "\r\n";
			
			$items[]	= $input . $label;
		}
		
		$html = '<p class="sub">Select which components appear on the page in the sidebar area.</p>';
		$html .= !empty($items) ? sprintf('<ul class="sidebar-items checkbox-list"><li>%s</li></ul>', implode('</li>' . "\r\n" . '<li>', $items)) : '';
		
		echo $html;
	}
	
	
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
	public static function find_by_slug($slug, $post_type = 'sidebar') {
		return parent::find_by_slug($slug, $post_type);
	}

}

/**
 * Hook in to WordPress
 */
if(class_exists('Classy_Sidebar')) {
	$classy_sidebar = new Classy_Sidebar('initialize');
}