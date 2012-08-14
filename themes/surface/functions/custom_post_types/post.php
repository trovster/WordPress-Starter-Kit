<?php require_once dirname(__FILE__) . '/_cpt.php';

class Surface_CPT_Post extends Surface_CTP {
	
	protected $_post_type = 'post';
	 
	public $categories	= null,
		   $tags		= null;

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
	 * has_category
	 * @desc	
	 * @return	boolean 
	 */
	public function has_category() {
		return $this->has_categories();
	}
	
	/**
	 * get_category
	 * @desc	
	 * @param	string	$default
	 * @param	boolean	$string
	 * @return	string|object 
	 */
	public function get_category($default = '', $string = false) {
		return $this->get_single_taxonomy($this->post->ID, 'category', $default, $string);
	}
	
	/**
	 * has_tags
	 * @desc	
	 * @return	boolean 
	 */
	public function has_tags() {
		$this->get_tags();
		
		if(!empty($this->tags)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * has_tag
	 * @desc	
	 * @return	boolean 
	 */
	public function has_tag() {
		return $this->has_tags();
	}
	
	/**
	 * get_tags
	 * @desc	
	 * @return	boolean|array
	 */
	public function get_tags() {
		if(!is_null($this->tags)) {
			return $this->tags;
		}
		else {
			$this->tags = get_the_terms($this->post->ID, 'post_tag');
			return $this->tags;
		}
		
		return false;
	}
	
	/**
	 * get_tag
	 * @desc	
	 * @param	string	$default
	 * @param	boolean	$string
	 * @return	string|object 
	 */
	public function get_tag($default = '', $string = false) {
		return $this->get_single_taxonomy($this->post->ID, 'post_tag', $default, $string);
	}
}

/**
 * Hook in to WordPress
 */
if(class_exists('Surface_CPT_Post')) {
	$cpt_post = new Surface_CPT_Post('setup');
	add_action(__FILE__, array(&$cpt_post, 'init'));
}