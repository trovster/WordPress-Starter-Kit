<?php require_once(dirname(__FILE__) . '/Classy.php');

/**
 * Classy_Slideshow
 * @desc	
 */

class Classy_Slideshow extends Classy {
	
	protected $_post_type	= 'slideshow';

	/**
	 * __construct
	 * @desc	
	 * @param	array	$options
	 * @return	\Classy_Slideshow
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
				'name'					=> 'Slideshow',
				'singular_name'			=> 'Slideshow',
				'add_new'				=> 'Add New Slideshow Box',
				'add_new_item'			=> 'Add New Slideshow Box',
				'edit'					=> 'Edit Slideshow Box',
				'edit_item'				=> 'Edit Slideshow Box',
				'new_item'				=> 'New Slideshow Box',
				'view'					=> 'View Slideshow Box',
				'view_item'				=> 'View Slideshow Box',
				'search_items'			=> 'Search Slideshow Boxes',
				'not_found'				=> 'No Slideshow Boxes found',
				'not_found_in_trash'	=> 'No Slideshow Boxes found in Trash',
				'parent'				=> 'Parent Slideshow Box',
			),
			'description'			=> 'Slideshow box',
			'capability_type'		=> 'post',
			'public'				=> true,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'register_meta_box_cb'	=> false,
			'taxonomies'			=> array(),
			'supports'				=> array(
				'title',
//				'editor',
//				'author',
				'thumbnail',
				'excerpt',
//				'trackbacks',
//				'custom-fields',
//				'comments',
//				'revisions',
				'page-attributes',
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
		add_image_size($this->get_post_type(), 2000, 560, true);
	}
	
	/**
	 * get_options
	 * @desc	Options for WP_Query.
	 * @param	array	$options
	 * @return	array
	 */
	public static function get_options($options = array()) {
		return array_merge(array(
			'post_type'			=> 'slideshow',
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
			$query->query_vars['meta_query'] = array_merge($query->query_vars['meta_query'], array(
				array(
					'key'		=> '_thumbnail_id',
					'compare'	=> '!=',
					'value'		=> ''
				),
			));
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
	 * the_content
	 * @desc	Optionally adds the secondary column if available.
	 * @param	object	$the_page
	 * @return	string
	 */
	public function the_content($more_link_text = NULL, $stripteaser = false) {
		return $this->the_excerpt();
	}
	
	
	/*********************************************************
	 * =Common Methods
	 * @desc	Useful common methods.
	 *********************************************************/
	
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
			'slideshow_text'		=> 'Text',
			'slideshow_thumbnail'	=> 'Image',
			'slideshow_link'		=> 'Link',
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
			case 'slideshow_thumbnail':
				echo $classy_post->has_thumbnail() ? $classy_post->get_thumbnail('thumb') : '-';
				break;
			
			case 'slideshow_link':
				echo $classy_post->has_permalink() ? $classy_post->get_permalink() : '-';
				break;
			
			case 'slideshow_text':
				echo $classy_post->has_excerpt() ? $classy_post->get_the_excerpt() : '-';
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
	public static function find_by_slug($slug, $post_type = 'slideshow') {
		return parent::find_by_slug($slug, $post_type);
	}

}

/**
 * Hook in to WordPress
 */
if(class_exists('Classy_Slideshow')) {
	$classy_slideshow = new Classy_Slideshow('initialize');
}