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