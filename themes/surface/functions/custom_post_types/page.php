<?php require_once dirname(__FILE__) . '/_cpt.php';

class Surface_CPT_Page extends Surface_CTP {
	
	protected $_post_type = 'page';

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
	* custom_field_boxes
	* @desc		Assigning the custom fields to events
	*/
	public function custom_field_boxes() {
		add_meta_box($this->get_post_type() . '_secondary_content', 'Secondary Content', array(&$this, 'custom_field_box_secondary_content'), $this->get_post_type(), 'normal', 'high');
	}
	
	/**
	 * custom_field_box_secondary_content
	 * @param	object	$post 
	 */
	public function custom_field_box_secondary_content($post) {
		$post	= self::find_by_id($post->ID);
		$id		= 'secondary_content';
		$name	= 'custom_' . $id;
		$value	= $post->custom_value($id);
		
		echo self::_custom_field_editor($id, $name, $value);
	}

	/**
	* has_link_href
	* @desc	
	* @return	boolean
	*/
	public function has_link_href() {
		return true;
	}

	/**
	* get_link_href
	* @desc	
	* @param	mixed	$default
	* @return	mixed
	*/
	public function get_link_href($default = false) {
		return get_permalink($this->ID);
	}
}

/**
 * Hook in to WordPress
 */
if(class_exists('Surface_CPT_Page')) {
	$cpt_page = new Surface_CPT_Page('setup');
	add_action(__FILE__, array(&$cpt_page, 'init'));
}