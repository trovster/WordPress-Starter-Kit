<?php

/*   
Component: Custom Post Type
Description: Base class for custom post types
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

class Surface_CTP {
	
	protected $_post_type,
			  $_custom		= null,
			  $_attachments	= null;

	/**
	* __construct()
	* @param	array	$options
	* return	$this
	*/
	public function __construct($options = array()) {
		if($options === 'setup') {
			add_action('init',			array(&$this, 'register_post_type'));
			add_action('admin_menu',	array(&$this, 'menu_remove_menu'));
			add_action('admin_init',	array(&$this, 'custom_field_boxes'));
			add_action('save_post',		array(&$this, 'custom_fields_update'));
			add_action('pre_get_posts', array(&$this, 'pre_get_posts'));

			add_action('manage_posts_custom_column', array(&$this, 'manage_columns'), 10, 2);
			add_filter(sprintf('manage_edit-%s_columns', $this->get_post_type()), array(&$this, 'manage_edit_columns'));
			add_filter(sprintf('manage_edit-%s_sortable_columns', $this->get_post_type()), array(&$this, 'manage_sortable_columns'));
		}
		elseif(is_array($options)) {
			foreach($options as $key => $value) {
				$this->$key = $value;
			}
		}
	
		return $this;
	}


	/**
	* __set()
	* @return mixed
	*/
	public function __set($key, $value) {
		if(method_exists($this, 'set_' . $key)) {
			return $this->{'set_' . $key}($value);
		}
		else {
			$this->{$key} = $value;
		}
		return $this;
	}

	/**
	* __get()
	* @return mixed
	*/
	public function __get($key) {
		if(method_exists($this, 'get_' . $key)) {
			return $this->{'get_' . $key}();
		}
		elseif(isset($this->{$key})) {
			return $this->{$key};
		}
		return null;
	}
	
	/**
	 * set_post
	 * @param	object	$post
	 * @return	Object 
	 */
	public function set_post($post) {
		$this->_post	= $post;
		$this->custom	= $post->ID;
		
		return $this;
	}
	
	/**
	 * get_post
	 * @return	Object 
	 */
	public function get_post() {
		return $this->_post;
	}

	/**
	 * get_post_type
	 * @return	string 
	 */
	public function get_post_type() {
		if(!empty($this->_post)) {
			return $this->_post->post_type;
		}
		return (string) $this->_post_type;
	}
	
	
	/**
	 * set_custom
	 * @param	int	$id
	 * @return	Object
	 */
	public function set_custom($id) {
		$this->_custom = get_post_custom($id);
		
		return $this;
	}
	
	/**
	 * get_custom
	 * @return	array 
	 */
	public function get_custom() {
		return $this->_custom;
	}
	
	/**
	 * has_custom_value
	 * @param	string	$key
	 * @param	string	$prefix
	 * @return	boolean 
	 */
	public function has_custom_value($key, $prefix = 'custom_') {
		return !empty($this->_custom[$prefix . $key][0]);
	}
	
	/**
	 * custom_value
	 * @param	string	$key
	 * @param	string	$prefix
	 * @return	string 
	 */
	public function custom_value($key, $prefix = 'custom_') {
		if($this->has_custom_value($key, $prefix)) {
			return $this->_custom[$prefix . $key][0];
		}
		return '';
	}

	/**
	* custom_value_boolean
	* @desc		Standardises any "featured" custom fields, which are boolean
	* @param	string	$key
	* @param	string	$prefix
	* @return	boolean
	*/
	public function custom_value_boolean($key, $prefix = 'custom_') {
		$value = $this->custom_value($key, $prefix);
		
		return $value === 'true' ? true : false;
	}

	/**
	* has_link_href
	* @desc	
	* @return	boolean
	*/
	public function has_link_href() {
		return $this->has_custom_value('page_id') || $this->has_custom_value('link_url');
	}

	/**
	* get_link_href
	* @desc	
	* @param	mixed	$default
	* @return	mixed
	*/
	public function get_link_href($default = false) {
		if($this->has_custom_value('page_id')) {
			return get_permalink($this->custom_value('page_id'));
		}
		elseif($this->has_custom_value('link_url')) {
			return $this->custom_value('link_url');
		}
		
		return $default;
	}
	
	/**
	 * has_thumbnail
	 * @desc	
	 * @return	boolean 
	 */
	public function has_thumbnail() {
		return has_post_thumbnail($this->post->ID);
	}
	
	/**
	 * get_thumbnail
	 * @desc	
	 * @param	string			$size
	 * @param	string|array	$attr
	 * @return	string 
	 */
	public function get_thumbnail($size = 'post-thumbnail', $attr = '') {
		if($this->has_thumbnail()) {
			return get_the_post_thumbnail($this->post->ID, $size, $attr);
		}
		return '';
	}
	
	/**
	 * get_date
	 * @desc
	 * @param	string		$d
	 * @return	string|null 
	 */
	public function get_date($d = '') {
		return get_the_date($d);
	}
	
	/**
	 * get_the_excerpt
	 * @desc	
	 * @param	int		$length
	 * @param	string	$append
	 * @return	string 
	 */
	public function get_the_excerpt($length = 12, $append = '…') {
		$excerpt = $this->post->post_excerpt;
		
		if(is_numeric($length)) {
			$excerpt = self::truncate_words($excerpt, $length, $append);
		}
		
		$excerpt = apply_filters('get_the_excerpt', $excerpt);
		
		return $excerpt;
	}
	
	/**
	 * the_excerpt
	 * @desc	
	 * @param	int		$length
	 * @param	string	$append
	 * @return	string 
	 */
	public function the_excerpt($length = 12, $append = '…') {
		echo apply_filters('the_excerpt', $this->get_the_excerpt($length, $append));
	}

	/**
	* truncate_words
	* @desc
	* @param	string	$words
	* @param	int		$limit
	* @param	int		$append
	* @return	string 
	*/
	public static function truncate_words($words, $limit, $append = ' …') {
		$limit = $limit + 1;
		$words = explode(' ', $words, $limit);

		array_pop($words);

		return implode(' ', $words) . $append;
	}
	
	/**
	 * has_attachments
	 * @desc	
	 * @return	boolean 
	 */
	public function has_attachments() {
		if(function_exists('attachments_get_attachments')) {
			$this->set_attachments();
			
			return count($this->_attachments) > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * set_attachments
	 * @desc	
	 * @return	Object 
	 */
	public function set_attachments() {
		if(function_exists('attachments_get_attachments')) {
			$this->_attachments = attachments_get_attachments($this->post->ID);
		}
		
		return $this;
	}
	
	/**
	 * get_attachments
	 * @desc	Return the HTML of the image attachments
	 * @return	string
	 */
	public function get_attachments() {
		$lis	= array();
		
		if($this->has_attachments()) {
			$total	= count($this->_attachments);
			$i		= 1;
			foreach($this->_attachments as $attachment) {
				$class	 = class_count_attr($i, $total);
				$class[] = $i === 1 ? 'active' : '';
				$class	 = array_filter($class);

				$lis[] = '<li' . template_add_class($class) . '><img src="' . $attachment['location'] . '" alt="' . __($attachment['title']) . '" title="" /></li>';
				$i++;
			}
		}
		
		return count($lis) > 0 ? '<ul>' . implode("\r\n", $lis) . '</ul>' : '';
	}

	/**
	 * get_single_taxonomy
	 * @desc	
	 * @param	int		$id
	 * @param	string	$taxonomy
	 * @param	string	$default
	 * @param	boolean	$string
	 * @return	string|object 
	 */
	public function get_single_taxonomy($id, $taxonomy, $default = '', $string = false) {
		$taxonomies	= get_the_terms($id, $taxonomy);
		$return		= (object) array(
			'term_id'	=> 0,
			'name'		=> $default,
			'slug'		=> sanitize_title($default)
		);

		if(!empty($taxonomies)) {
			$return = array_shift($taxonomies);
		}

		return ($string === true) ? $return->name : $return;
	}
	
	/**
	 * get_taxonomy_type
	 * @desc	Return a flat array of taxonomy values based on type (name, slug, term_id)
	 *			self::get_taxonomy_type(get_the_terms($id), 'slug');
	 * @param	array	$taxonomy
	 * @param	string	$type
	 * @return	array
	 */
	public static function get_taxonomy_type($taxonomy, $type) {
		$ids = array();
		if(is_array($taxonomy)) {
			foreach($taxonomy as $item) {
				if(is_array($item) && array_key_exists($type, $item)) {
					$ids[] = $item[$type];
				}
				elseif(is_object($item)) {
					$ids[] = $item->{$type};
				}
			}
		}
		return $ids;
	}
	
	/**
	 * register_post_type 
	 */
	public function register_post_type() {}
	
	/**
	* menu_remove_menu
	*/
	public function menu_remove_menu() {}
	
	/**
	* pre_get_posts
	* @desc	Restrict posts
	*/
	public function pre_get_posts(&$query) {
		$type	= !empty($query->query_vars['post_type']) ? $query->query_vars['post_type'] : false;
		$update	= !is_admin() && !is_preview() && is_string($type) && $type === $this->get_post_type();

		if(empty($query->query_vars['meta_query'])) {
			$query->query_vars['meta_query'] = array();
		}
		elseif(!empty($query->query_vars['meta_query']) && !is_array($query->query_vars['meta_query'])) {
			$query->query_vars['meta_query'] = array($query->query_vars['meta_query']);
		}

		if(empty($query->query_vars['tax_query'])) {
			$query->query_vars['tax_query'] = array();
		}
		elseif(!empty($query->query_vars['tax_query']) && !is_array($query->query_vars['tax_query'])) {
			$query->query_vars['tax_query'] = array($query->query_vars['tax_query']);
		}
		
		if($update) {
			$query->set('post_type', $this->get_post_type());
		}
	}

	/**
	* custom_field_boxes
	* @desc		Assigning the custom fields
	*/
	public function custom_field_boxes() {}
	
	/**
	* custom_field_box_specific
	* @global	object	$post
	*/
	public function custom_field_box_specific($post) {}
	
	/**
	* custom_fields_update
	* @desc		Custom fields are saved here.
	*			Custom fields must be prefixed with custom_ to be saved automatically
	* @see		http://codex.wordpress.org/Function_Reference/update_post_meta
	* @param	int		$post_id
	* @return	int		$post_id
	*/
	public function custom_fields_update($post_id) {
		// cycle through each posted meta item and save
		// by default only saves custom fields which are prefixed with custom_
		foreach($_POST as $key => $value) {
			if(strpos($key, 'custom_') !== false) {
				$current_data	= get_post_meta($post_id, $key, true);
				$new_data		= !empty($_POST[$key]) ? $_POST[$key] : null;

				if(is_null($new_data)) {
					delete_post_meta($post_id, $key);
				}
				else {
					// add_post_meta is called if not already set
					// @see http://codex.wordpress.org/Function_Reference/update_post_meta
					if(strpos($key, 'date') !== false) {
						$new_data = strtotime($new_data); // convert to timestamp
					}

					update_post_meta($post_id, $key, $new_data, $current_data);
				}
			}
		}

		return $post_id;
	}

	/**
	* manage_columns
	* @desc		Populate the row values for the new columns
	* @param	string	$column
	* @param	int		id
	*/
	public function manage_columns($column, $id) {}
	
	/**
	* manage_edit_columns
	* @param	array $columns
	* @return	array
	*/
	public function manage_edit_columns($columns) {
		return $columns;
	}

	/**
	* manage_sortable_columns
	* @desc		Sorting of the new columns
	* @param	array	$columns
	* @return	string 
	*/
	public function manage_sortable_columns($columns) {
		return $columns;
	}
	
	/**
	 * _custom_field_list
	 * @desc	Loops through fields and generates the custom box HTML
	 * @param	object	$post
	 * @param	array	$fields 
	 */
	protected static function _custom_field_list($post, $fields) {
		$post = self::find_by_id($post->ID);
		
		foreach($fields as $field) {
			$value	= $post->custom_value($field);
			$label	= str_replace('_', ' ', $field);
			$label	= ucwords($label);
			$label	= str_replace(array('Url', 'Id'), array('URL', 'ID'), $label);
			$id		= $field;
			$name	= 'custom_' . $field;
			$class	= '';
			$type	= 'text';
			$type	= strpos($name, 'url') !== false ? 'url' : $type;
			$type	= strpos($name, 'date') !== false ? 'date' : $type;

			echo self::_custom_field_html($id, $name, $label, $value, $class, $type);
		}
	}
	
	/**
	 * _custom_field_html
	 * @desc	Build admin specific custom fields
	 * @param	string	$id
	 * @param	string	$name
	 * @param	string	$label
	 * @param	string	$value
	 * @param	string	$class
	 * @param	string	$type
	 * @return	string 
	 */
	protected static function _custom_field_html($id, $name, $label, $value, $class = '', $type = null) {
		$type	= is_null($type) ? 'text' : $type;
		$html	= '';

		if($type === 'textarea') {
			$html .= '<p><label for="' . $id . '">' . $label . ':</label><br />' . "\r\n";
			$html .= "\t" . '<textarea style="width:90%;" type="' . $type . '" id="' . $id . '" name="' . $name . '"' . (!empty($class) ? ' class="' . $class . '"' : '') . '>' . $value . '</textarea>';
			$html .= '</p>' . "\r\n";
		}
		elseif($type === 'checkbox') {
			$html .= '<p class="checkbox" style="padding-top: 5px;">';
			$html .= '<input type="hidden" name="' . $name . '" value="false" />';
			$html .= '<input style="margin-right: 5px; margin-top: 0;" id="' . $id . '" value="true" type="' . $type . '" name="' . $name . '"' . ($value === true ? ' checked="checked"' : '') . ' />';
			$html .= '<label for="' . $id . '" style="font-weight: bold;">' . $label . '</label>';
			$html .= '</p>';
		}
		else {
			$html .= '<p><label for="' . $id . '">' . $label . ':</label><br />' . "\r\n";
			$html .= "\t" . '<input style="width:90%;" type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '"' . (!empty($class) ? ' class="' . $class . '"' : '') . ' />';
			$html .= '</p>' . "\r\n";
		}

		return $html;
	}
	
	/**
	 * _custom_field_editor
	 * @desc	
	 * @param	string	$id
	 * @param	string	$name
	 * @param	string	$value
	 * @return	string 
	 */
	protected static function _custom_field_editor($id, $name, $value) {
		return wp_editor($value, str_replace('_', '', $id), array(
			'tinymce'			=> true,
			'media_buttons'		=> true,
			'textarea_name'		=> $name,
			'textarea_rows'		=> 10,
			'editor_class'		=> '',
		));
	}

	/**
	 * _custom_field_select_join
	 * @desc	
	 * @param	string	$custom_post_type
	 * @param	string	$option				// the post property to show in the <option></option>
	 * @param	string	$value				// current value, if present
	 * @param	string	$name
	 * @param	string	$id
	 * @param	string	$default			// text that is the default, none selected option
	 * @return	string	$html
	 */
	protected static function _custom_field_select_join($custom_post_type, $option, $value, $name, $id = null, $default = 'Select…') {
		$html	= '';
		$id		= !is_string($id) ? $name : $id; // general, id the same as name

		// find the posts based on the custom_post_type
		$custom_posts	= get_posts(array(
			'numberposts'	=> -1,
			'orderby'		=> 'post_title',
			'order'			=> 'ASC',
			'post_type'		=> $custom_post_type
		));

		$html .= '<select name="' . $name . '" id="' . $id . '" style="min-width: 200px;">';
		$html .= '<option value="">' . esc_attr(__($default)) . '</option>';
		
		foreach($custom_posts as $custom_post) {
			$selected = ($custom_post->ID == $value) ? ' selected="selected"' : '';
			$html .= '<option value="' . $custom_post->ID . '"' . $selected . '>' . $custom_post->{$option} . '</option>';
		}
		
		$html .= '</select>';

		return $html;
	}
	
	/**
	 * _custom_fields_general_page_id
	 * @param	object	$post
	 * @param	string	$id
	 * @param	string	$label 
	 * @return	string 
	 */
	protected static function _custom_fields_general_page_id($post, $id = 'custom_page_id', $label = 'Select page') {
		$html		= '';
		$post		= self::find_by_id($post->ID);
		$page_id	= $post->custom_value($id, '');

		$html .= '<p><label for="' . $id . '">' . $label . ':</label><br />';
		$html .= wp_dropdown_pages(array(
			'depth'				=> 0,
			'child_of'			=> 0,
			'selected'			=> !empty($page_id) ? $page_id : 0,
			'echo'				=> false,
			'name'				=> $id,
			'show_option_none'	=> 'None',
		));
		
		return $html;
	}
	
	/**
	 * _custom_fields_general_link
	 * @param	object	$post
	 * @param	string	$id
	 * @param	string	$label 
	 * @return	string 
	 */
	protected static function _custom_fields_general_link($post, $id = 'custom_link_url', $label = 'Link URL') {
		$html		= '';
		$post		= self::find_by_id($post->ID);
		$value		= $post->custom_value($id, '');

		$html .= self::_custom_field_html($id, $id, $label, $value, 'url', 'url');
		
		return $html;
	}
	
	/**
	 * forge
	 * @param	array	$data
	 * @return	instance 
	 */
	public static function forge($data) {
		return new static($data);
	}
	
	/**
	 * find_by_id
	 * @param	int	$id
	 * @return	mixed 
	 */
	public static function find_by_id($id) {
		$post = get_post($id);
		
		if(is_object($post)) {
			return self::forge(array(
				'post'	=> $post
			));
		}
		
		return false;
	}
}