<?php require_once(dirname(__FILE__) . '/Classy.php');

/**
 * Classy_Page
 * @desc	
 */

class Classy_Page extends Classy {
	
	protected $_post_type	= 'page',
			  $_attachments	= null;

	/**
	 * __construct
	 * @desc	
	 * @param	array	$options
	 * @return	\Classy_Page
	 */
	public function __construct($options = array()) {
		parent::__construct($options);
		
		if($options === 'initialize') {
			add_filter(sprintf('manage_pages_columns', $this->get_post_type()),			array($this, 'filter_manage_column_listing'));
			add_action(sprintf('manage_pages_custom_column', $this->get_post_type()),	array($this, 'action_manage_column_value'), 10, 2);
			add_action('do_meta_boxes',													array($this, 'action_do_meta_boxes_remove_featured_image_box'));
			
//			add_action('attachments_register',	array($this, 'action_attachments_register_menu'));
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
		add_image_size('banner-landscape', 1300, 560, true);
		add_image_size('page-listing', 222, 120, true);
		
		if(class_exists('MultiPostThumbnails')) {
			new MultiPostThumbnails(array(
				'label'		=> 'Banner Image',
				'id'		=> $this->get_post_type() . '-banner-landscape',
				'post_type'	=> $this->get_post_type()
			));
			
			new MultiPostThumbnails(array(
				'label'		=> 'Page Listing Image',
				'id'		=> $this->get_post_type() . '-page-listing-image',
				'post_type'	=> $this->get_post_type()
			));
		}
	}
	
	/**
	 * action_do_meta_boxes_remove_featured_image_box
	 * @desc	Remove the Featured image box from posts.
	 */
	public function action_do_meta_boxes_remove_featured_image_box() {
		remove_meta_box('postimagediv', $this->get_post_type(), 'side');
	}
	
	
	/*********************************************************
	 * =Common Methods
	 * @desc	Useful common methods.
	 *********************************************************/
	
	/**
	 * get_attr_classes
	 * @origin	get_post_class
	 * @desc	Get the post class, with any optional classes passed as an option.
	 *			Add a class if the page template is 'menu'.
	 * @param	array	$classes
	 * @return	array
	 */
	public function get_attr_classes($classes = array()) {
		if($this->get_template(true) === 'page-menu') {
			$classes[] = 'is-menu';
		}
		
		return parent::get_attr_classes($classes);
	}
	
	
	/*********************************************************
	 * =Specific
	 * @desc	Page specific methods.
	 *********************************************************/
	/**
	 * get_template
	 * @desc	Returns the page template
	 * @param	boolean	$trim	Optionally remove the .php suffix
	 * @return	string
	 */
	public function get_template($trim = false) {
		$value = $this->get_custom_value('template', '_wp_page_');

		if($trim === true) {
			$value = rtrim($value, '.php');
		}

		return $value;
	}
	
	/**
	 * the_content
	 * @desc	Optionally adds the secondary column if available.
	 * @param	object	$the_page
	 * @return	string
	 */
	public function the_content($more_link_text = NULL, $stripteaser = false) {
		if($this->has_second_column()) {
			$content_primary	= apply_filters('the_content', $this->get_the_content());
			$content_secondary	= apply_filters('the_content', $this->get_second_column());
			$the_content		= sprintf('<div class="entry-content columns"><div class="column column-1">%s</div><div class="column column-2">%s</div></div>', $content_primary, $content_secondary);
		}
		else {
			$content_primary	= apply_filters('the_content', $this->get_the_content());
			$the_content		= sprintf('<div class="entry-content">%s</div>', $content_primary);
		}
		
		echo $the_content;
	}
	
	/**
	 * has_second_column
	 * @desc	Check whether a second column exists.
	 * @return	boolean
	 */
	public function has_second_column() {
		return $this->has_custom_value('second_column');
	}
	
	/**
	 * get_second_column
	 * @desc	Retrieve the second column.
	 * @return	string
	 */
	public function get_second_column() {
		return $this->has_second_column() ? $this->get_custom_value('second_column') : '';
	}
	
	/**
	 * the_second_column
	 * @desc	Output the second column.
	 * @output	string
	 */
	public function the_second_column() {
		echo $this->get_second_column();
	}
	
	/**
	 * get_thumbnail_caption
	 * @desc	Retrieve the thumbnail caption (post excerpt).
	 *			Split the caption up if it contains '/'.
	 * @return	string
	 */
	public function get_thumbnail_caption() {
		$caption	= parent::get_thumbnail_caption();
		$captions	= explode('/', $caption);
		
		if(count($captions) > 1) {
			$total		= count($captions);
			$i			= 1;
			$caption	= '';
			foreach($captions as $text) {
				$classes	= array('caption');
				$classes[]	= $i === 1 ? 'f' : '';
				$classes[]	= $i === $total ? 'l' : '';
				$caption	.= sprintf('<span class="%s">%s</span>', implode(' ', $classes), trim($text));
				$i++;
			}
			$classes	= array('captions', 'count-' . $total);
			$caption	= sprintf('<div class="%s">%s</div>', implode(' ', $classes), $caption);
		}
		
		return $caption;
	}
	
	/**
	 * get_sub_page_options
	 * @desc	Options for WP_Query.
	 * @param	array	$options
	 * @return	array
	 */
	public function get_sub_page_options($options = array()) {
		return array_merge($this->get_children_options($options), array(
			'post_type'		=> 'page',
			'order'			=> 'ASC',
			'orderby'		=> 'menu_order',
			'post_parent'	=> $this->post->ID,
			'meta_query'	=> array(
				array(
					'key'		=> 'page_page-listing_thumbnail_id',
					'compare'	=> '!=',
					'value'		=> ''
				),
			)
		));
	}
	
	/**
	 * has_section_title
	 * @desc	
	 * @return	boolean
	 */
	public function has_section_title() {
		return $this->has_custom_value('section_title');
	}
	
	/**
	 * get_section_title
	 * @desc	
	 * @return	boolean
	 */
	public function get_section_title() {
		return $this->has_section_title() ? $this->get_custom_value('section_title') : '';
	}
	
	/**
	 * has_section_text
	 * @desc	
	 * @return	boolean
	 */
	public function has_section_text() {
		return $this->has_custom_value('section_text');
	}
	
	/**
	 * get_section_text
	 * @desc	
	 * @return	boolean
	 */
	public function get_section_text() {
		return $this->has_section_text() ? apply_filters('the_content', $this->get_custom_value('section_text')) : '';
	}
	
	/**
	 * get_section_intro
	 * @desc	Build up the HTML for the section intro
	 * @param	int		$page_id
	 * @return	string
	 */
	public function get_section_intro($page_id = null) {
		if(!is_null($page_id)) {
			$page = Classy_Page::find_by_id($page_id);
		}
		else {
			$page = $this;
		}
		
		$title	= $page->has_section_title() ? $page->get_section_title() : $page->get_the_title();
		$text	= $page->has_section_text() ? $page->get_section_text() : '';
		
		return sprintf('<h1>%s</h1> %s', $title, $text);
	}
	
	/**
	 * the_section_intro
	 * @desc	
	 * @output	string
	 */
	public function the_section_intro($page_id = null) {
		echo $this->get_section_intro($page_id);
	}
	
	/**
	 * has_banner_link
	 * @desc	
	 * @return	boolean
	 */
	public function has_banner_link() {
		return $this->has_custom_value('banner_link');
	}
	
	/**
	 * get_banner_link
	 * @desc	
	 * @return	boolean
	 */
	public function get_banner_link() {
		return $this->get_custom_value('banner_link');
	}
	
	/**
	 * has_banner_title
	 * @desc	
	 * @return	boolean
	 */
	public function has_banner_title() {
		return $this->has_custom_value('banner_title');
	}
	
	/**
	 * get_banner_title
	 * @desc	
	 * @return	boolean
	 */
	public function get_banner_title() {
		return $this->has_banner_title() ? $this->get_custom_value('banner_title') : '';
	}
	
	/**
	 * has_banner_text
	 * @desc	
	 * @return	boolean
	 */
	public function has_banner_text() {
		return $this->has_custom_value('banner_text');
	}
	
	/**
	 * get_banner_text
	 * @desc	
	 * @return	boolean
	 */
	public function get_banner_text() {
		return $this->has_banner_text() ? apply_filters('the_content', $this->get_custom_value('banner_text')) : '';
	}
	
	/**
	 * has_banner_image
	 * @desc	
	 * @return	boolean
	 */
	public function has_banner_image() {
		return class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($this->get_post_type(), $this->get_post_type() . '-banner-landscape', $this->get_the_ID());
	}
	
	/**
	 * get_banner_image
	 * @desc	
	 * @param	string			$size
	 * @param	string|array	$attr
	 * @return	string 
	 */
	public function get_banner_image($size = 'banner-landscape', $attr = '') {
		if($this->has_banner_image()) {
			return MultiPostThumbnails::get_the_post_thumbnail($this->get_post_type(), $this->get_post_type() . '-banner-landscape', $this->get_the_ID(), $size);
		}
		
		return '';
	}
	
	/**
	 * has_banner
	 * @desc	
	 * @return	boolean
	 */
	public function has_banner() {
		return $this->has_banner_image();
	}
	
	/**
	 * get_banner
	 * @desc	Build up the HTML for the banner, including optional link.
	 * @param	string	$size
	 * @return	string
	 */
	public function get_banner($size = 'banner-landscape') {
		$image	= false;
		$html	= '';
		
		if(_site_is_section('homepage')) {
			$html .= Classy::loop(Classy_Slideshow::get_options(), 'loop', 'slideshow');
			$html .= Classy::loop(Classy_Featured::get_options(), 'loop', 'featured');
		}
		elseif($this->has_banner_image()) {
			$title		= $this->has_banner_title() ? sprintf('<h1 class="entry-title">%s</h1>', $this->get_banner_title()) : '';
			$text		= $this->has_banner_text() ? sprintf('<div class="description">%s</div>', $this->get_banner_text()) : '';
			$content	= sprintf('<div class="entry-content"><div class="inner">%s%s</div></div>', $title, $text);
			$banner		= sprintf('%s <div class="photo">%s</div>', $content, $this->get_banner_image($size));
			
			$image		= $this->has_banner_link() ? sprintf('<a href="%s">%s</a>', $this->get_banner_link(), $banner) : $banner;
			
			$html .= sprintf('<div class="banner"><div class="inner">%s</div></div>', $image);
		}
		
		return $html;
	}
	
	/**
	 * the_banner
	 * @desc	Outputs the banner image, including optional link.
	 * @param	string	$size
	 * @output	string
	 */
	public function the_banner($size = 'banner-landscape') {
		echo $this->get_banner($size);
	}
	
	/**
	 * has_listing_image
	 * @desc	
	 * @return	boolean
	 */
	public function has_listing_image() {
		return class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail($this->get_post_type(), $this->get_post_type() . '-listing', $this->get_the_ID());
	}
	
	/**
	 * get_listing_image
	 * @desc	
	 * @param	string			$size
	 * @param	string|array	$attr
	 * @return	string 
	 */
	public function get_listing_image($size = 'page-listing', $attr = '') {
		if($this->has_listing_image()) {
			return MultiPostThumbnails::get_the_post_thumbnail($this->get_post_type(), $this->get_post_type() . '-listing', $this->get_the_ID(), $size);
		}
		
		return '';
	}
	
	/**
	 * has_children
	 * @desc	Checks whether this page has any sub-pages.
	 * @param	array	options
	 * @return	boolean
	 */
	public function has_children($options = array()) {
		return count($this->get_children($options)) > 0 ? true : false;
	}
	
	/**
	 * get_children_options
	 * @desc	Get the sub-page get_children_options for this page.
	 * @param	array	options
	 * @return	array
	 */
	public function get_children_options($options = array()) {
		$args = array_merge(array(
			'sort_order'	=> 'ASC',
			'sort_column'	=> 'menu_order',
			'hierarchical'	=> 1,
			'authors'		=> '',
			'child_of'		=> 0,
			'parent'		=> -1,
			'exclude_tree'	=> '',
			'number'		=> '',
			'offset'		=> 0,
			'post_type'		=> 'page',
			'post_status'	=> 'publish'
		), $options);

		$args['child_of']	= $this->post->ID;
		$args['parent']		= $this->post->ID;
		
		return $args;
	}
	
	/**
	 * get_children
	 * @desc	Get the sub-pages for this page.
	 * @param	array	options
	 * @return	array
	 */
	public function get_children($options = array()) {
		_D($this->get_children_options($options), false);
		return get_pages($this->get_children_options($options));
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
	
	
	/*********************************************************
	 * =Attachments
	 * @desc	Attachment plugin methods.
	 *********************************************************/
	
	/**
	 * action_attachments_register_menu
	 * @desc	Register the menu attachmnents.
	 * @see		https://github.com/jchristopher/attachments#create-custom-instances
	 * @param	object	$attachments
	 */
	public function action_attachments_register_menu($attachments) {
		$attachments->register('menu', array(
			'label'         => 'Menus',
			'post_type'     => array('page'),
			'position'      => 'side',
			'priority'      => 'high',
			'filetype'      => null,
			'note'          => 'Attach your menu files…',
			'append'        => true,
			'button_text'   => __('Attach Files', 'attachments'),
			'modal_text'    => __('Attach', 'attachments'),
			'router'        => 'browse', // browse or upload
			'fields'        => array(
				array(
				  'name'      => 'title',
				  'type'      => 'text',
				  'label'     => __('Title', 'attachments'),
				  'default'   => 'title',
				),
			),
		));
	}
	
	/**
	 * has_menu_attachments
	 * @desc	Check whether the menu attachments exist for the page.
	 * @return	boolean
	 */
	public function has_menu_attachments() {
		if(is_null($this->_attachments)) {
			$this->_attachments = new Attachments('menu', $this->get_the_ID());
		}

		return $this->_attachments->exist();
	}
	
	/**
	 * get_menu_attachments
	 * @desc	Return the menu attachments for the page.
	 * @return	\Attachments
	 */
	public function get_menu_attachments() {
		if(is_null($this->_attachments)) {
			$this->_attachments = new Attachments('menu', $this->get_the_ID());
		}

		return $this->_attachments;
	}
	
	/**
	 * get_menu_attachment
	 * @desc	Return an individual menu attachment.
	 * @return	\Attachments
	 */
	public function get_menu_attachment($index = null) {
		if($this->has_menu_attachments()) {
			return is_null($index) ? $this->get_menu_attachments()->get() : $this->get_menu_attachments()->get_single($index);
		}
		return false;
	}
	
	/**
	 * get_menu_attachment_list
	 * @desc	Built up a HTML list of the menu attachments.
	 * @return	string
	 */
	public function get_menu_attachment_list() {
		$title	= '';
		$list	= array();
		
		if($this->has_menu_attachments()) {
			while($this->get_menu_attachments()->get()) {
				$list[] = sprintf('<li><a href="%1$s" download>%2$s</a></li>', $this->get_menu_attachments()->url(), $this->get_menu_attachments()->field('title'));
			}
		}
		
		return !empty($list) ? sprintf('<div class="attachments attachments-menu"><h3>%1$s</h3><ul>%2$s</ul></div>', $title, implode("\r\n", $list)) : '';
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
	public function action_admin_init_meta_box() {
		if(class_exists('MultiPostThumbnails')) {
			add_meta_box($this->get_post_type() . '_banner_info', 'Banner Info', array($this, 'meta_box_page_banner_info'), $this->get_post_type(), 'normal', 'high');
		}
//		add_meta_box($this->get_post_type() . '_section_intro', 'Section Introduction', array($this, 'meta_box_page_section_intro'), $this->get_post_type(), 'normal', 'high');
	}
	
	/**
	 * meta_box_page_banner_info
	 * @desc	Custom meta box.
	 * @param	object	$post
	 */
	public function meta_box_page_banner_info($post) {
		$classy_page = self::find_by_id($post->ID);
		
		echo self::_meta_field_html($classy_page->get_custom_value('banner_title'), 'banner_title', 'Title', true, 'text');
		echo self::_meta_field_html($classy_page->get_custom_value('banner_text'), 'banner_text', 'Text', true, 'textarea');
//		echo self::_meta_field_html($classy_page->get_custom_value('banner_link'), 'banner_link', 'URL', true, 'text');
	}
	
	/**
	 * meta_box_page_section_intro
	 * @desc	Custom meta box.
	 * @param	object	$post
	 */
	public function meta_box_page_section_intro($post) {
		$classy_page = self::find_by_id($post->ID);
		
		echo self::_meta_field_html($classy_page->get_custom_value('section_title'), 'section_title', 'Title', true, 'text');
		echo self::_meta_field_html($classy_page->get_custom_value('section_text'), 'section_text', 'Text', true, 'textarea');
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
	public static function find_by_slug($slug, $post_type = 'page') {
		return parent::find_by_slug($slug, $post_type);
	}

}

/**
 * Hook in to WordPress
 */
if(class_exists('Classy_Page')) {
	$classy_page = new Classy_Page('initialize');
}