<?php require_once dirname(__FILE__) . '/_cpt.php';

class Surface_CPT_Featured extends Surface_CTP {
	
     protected $_post_type = 'featured';

	/**
	* __construct()
	* @param	array	$options
	* return	$this
	*/
	public function __construct($options = array()) {
		parent::__construct($options);
	
		return $this;
	}
	
	/**
	 * register_post_type 
	 */
	public function register_post_type() {
		register_post_type($this->get_post_type(), array(
			'labels'	=> array(
				'name'					=> __( 'Featured' ),
				'singular_name'			=> __( 'Featured Box' ),
				'add_new'				=> __( 'Add New Box' ),
				'add_new_item'			=> __( 'Add New Box' ),
				'edit'					=> __( 'Edit Box' ),
				'edit_item'				=> __( 'Edit Box' ),
				'new_item'				=> __( 'New Box' ),
				'view'					=> __( 'View Box' ),
				'view_item'				=> __( 'View Box' ),
				'search_items'			=> __( 'Search Boxs' ),
				'not_found'				=> __( 'No Boxs found' ),
				'not_found_in_trash'	=> __( 'No Boxs found in Trash' ),
				'parent'				=> __( 'Parent Box' ),
			),
			'description'			=> 'Featured one of four boxes on the homepage.',
			'capability_type'		=> 'post',
			'public'				=> true,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'rewrite'				=> false,
			'hierarchical'			=> false,
			'supports'				=> array(
				'title',
				'editor',
				'thumbnail',
				'page-attributes'
			)
		));
		
		add_image_size($this->get_post_type() . '-image', 240, 150, true);
	}

	/**
	* custom_field_boxes
	* @desc		Assigning the custom fields to events
	*/
	public function custom_field_boxes() {
		add_meta_box($this->get_post_type() . '_specific', 'Specifics', array(&$this, 'custom_field_box_specific'), $this->get_post_type(), 'normal', 'high');
	}
	
	/**
	* custom_field_box_specific
	* @global	object	$post
	*/
	public function custom_field_box_specific($post) {
		echo self::_custom_fields_general_page_id($post);
		echo self::_custom_fields_general_link($post);
	}
	
	/**
	* manage_columns
	* @desc		Populate the row values for the new columns
	* @param	string	$column
	* @param	int		id
	*/
	public function manage_columns($column, $id) {
		$post = self::find_by_id($id);

		switch($column) {
			case 'featured_link':
				$link = $post->get_link_href();
				echo !empty($link) ? sprintf('<a href="%s">%s</a>', $link, self::_short_url($link)) : '-';
				break;
		}
	}
	
	/**
	* manage_edit_columns
	* @param	array $columns
	* @return	array
	*/
	public function manage_edit_columns($columns) {
		$date		= $columns['date'];
		
		unset($columns['date']);
		unset($columns['categories']);

		$columns['featured_link']		= 'Link';
		$columns['date']				= $date;

		return $columns;
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
}

/**
 * Hook in to WordPress
 */
if(class_exists('Surface_CPT_Featured')) {
	$cpt_featured = new Surface_CPT_Featured('setup');
	add_action(__FILE__, array(&$cpt_featured, 'init'));
}