<?php
class Html
{
	public static $doctypes = array();
	public static $html5 = false;

	/**
	 * Creates an html link
	 *
	 * @param	string	the url
	 * @param	string	the text value
	 * @param	array	the attributes array
	 * @return	string	the html link
	 */
	public static function anchor($href, $text = null, $attr = array())
	{
		// Create and display a URL hyperlink
		is_null($text) and $text = $href;

		$attr['href'] = $href;

		return Html::html_tag('a', $attr, $text);
	}

	/**
	 * Creates an html image tag
	 *
	 * Sets the alt atribute to filename of it is not supplied.
	 *
	 * @param	string	the source
	 * @param	array	the attributes array
	 * @return	string	the image tag
	 */
	public static function img($src, $attr = array())
	{
		$attr['src'] = $src;
		$attr['alt'] = (isset($attr['alt'])) ? $attr['alt'] : pathinfo($src, PATHINFO_FILENAME);
		return Html::html_tag('img', $attr);
	}

	/**
	 * Creates a mailto link.
	 *
	 * @param	string	The email address
	 * @param	string	The text value
	 * @param	string	The subject
	 * @return	string	The mailto link
	 */
	public static function mail_to($email, $text = NULL, $subject = NULL, $attr = array())
	{
		$text or $text = $email;

		$subject and $subject = '?subject='.$subject;

		return Html::html_tag('a', array(
			'href' => 'mailto:'.$email.$subject,
		) + $attr, $text);
	}

	/**
	 * Generates a html meta tag
	 *
	 * @param	string|array	multiple inputs or name/http-equiv value
	 * @param	string			content value
	 * @param	string			name or http-equiv
	 * @return	string
	 */
	public static function meta($name = '', $content = '', $type = 'name')
	{
		if( ! is_array($name))
		{
			$result = Html::html_tag('meta', array($type => $name, 'content' => $content));
		}
		elseif(is_array($name))
		{
			$result = "";
			foreach($name as $array)
			{
				$meta = $array;
				$result .= "\n" . Html::html_tag('meta', $meta);
			}
		}
		return $result;
	}

	/**
	 * Generates a html5 audio tag
	 * It is required that you set html5 as the doctype to use this method
	 *
	 * @param	string|array	one or multiple audio sources
	 * @param	array			tag attributes
	 * @return	string
	 */
	public static function audio($src = '', $attr = false)
	{
		if(is_array($src))
		{
			$source = '';
			foreach($src as $item)
			{
				$source .= Html::html_tag('source', array('src' => $item));
			}
		}
		else
		{
			$source = Html::html_tag('source', array('src' => $src));
		}
		return Html::html_tag('audio', $attr, $source);
	}

	/**
	 * Generates a html un-ordered list tag
	 *
	 * @param	array			list items, may be nested
	 * @param	array|string	outer list attributes
	 * @return	string
	 */
	public static function ul(array $list = array(), $attr = false)
	{
		return Html::build_list('ul', $list, $attr);
	}

	/**
	 * Generates a html ordered list tag
	 *
	 * @param	array			list items, may be nested
	 * @param	array|string	outer list attributes
	 * @return	string
	 */
	public static function ol(array $list = array(), $attr = false)
	{
		return Html::build_list('ol', $list, $attr);
	}

	
	/**
	 * Generates a html title tag
	 *
	 * @param	string	page title
	 * @return	string
	 */
	public static function title($content = '')
	{
		return Html::html_tag('title', array(), $content);
	}

	/**
	 * Generates a html un-ordered list tag
	 *
	 * @param	array			list items, may be nested
	 * @param	array|string	outer list attributes
	 * @return	string
	 */
	public static function nav(array $list = array(), $attr = false)
	{
		return Html::build_list('ul', $list, $attr, '', true);
	}

	/**
	 * Generates the html for the list methods
	 *
	 * @param	string	list type (ol or ul)
	 * @param	array	list items, may be nested
	 * @param	array	tag attributes
	 * @param	string	indentation
	 * @param	boolean	is nav
	 * @return	string
	 */
	protected static function build_list($type = 'ul', Array $list = array(), $attr = false, $indent = '', $is_nav = false)
	{
		if ( ! is_array($list))
		{
			$result = false;
		}

		$out = '';
		foreach ($list as $key => $val)
		{
			$sub = '';
			if(!is_array($val)) {
				$val = array(
					'content'	=> $val,
					'attr'		=> array()
				);
			}

			if(!isset($val['attr'])) {
				$val['attr'] = array();
			}

			if(!isset($val['attr']['class'])) {
				$val['attr']['class'] = array();
			}
			if(!is_array($val['attr']['class'])) {
				$val['attr']['class'] = array($val['attr']['class']);
			}
			$val['attr']['class'] = trim(implode(' ', $val['attr']['class']));

			if(isset($val['sub'])) {
				$sub .= Html::build_list($type, $val['sub'], '', $indent."\t\t").$indent."\t";
			}

			$out .= $indent."\t".Html::html_tag('li', $val['attr'], $val['content'] . $sub).PHP_EOL;
		}
		$result = $indent.Html::html_tag($type, $attr, PHP_EOL.$out.$indent).PHP_EOL;
		return $result;
	}

	/**
	 * Generates a html span tag
	 *
	 * @param	array|string	tag attributes
	 * @param	string			the content
	 * @return	string
	 */
	public static function span($content, $attr = array())
	{
		return Html::html_tag('span', $attr, $content);
	}

	/**
	 * Applied the markdown filter on text
	 *
	 * @param	string
	 * @return	string
	 */
	public static function markdown($text)
	{
		return function_exists('markdown') ? markdown($text) : $text;
	}

	/**
	 * Create an address
	 *
	 * @param	array			array of key => value / type => value
	 * @param	array|string	tag attributes
	 * @return	string
	 */
	public static function address($array, $attr)
	{
		$valid_types = array('org fn', 'fn org', 'street-address', 'extended-address', 'locality', 'region', 'postal_code', 'postal-code', 'country');
		
		if(is_object($array) && method_exists($array, 'to_array')) {
			$array = $array->to_array();
		}
		
		if(!is_array($array)) {
			throw new Exception('Address must be an array');
		}
		
		// standardise the array keys
		$standarised = array();
		foreach($array as $key => $value) {
			switch(\Str::lower($key)) {
				case 'street_address':
					$type = 'street-address';
					break;
				
				case 'extended_address':
					$type = 'extended-address';
					break;
				
				case 'postal_code':
					$type = 'postal-code';
					break;
				
				default:
					$type = str_replace(array('_'), array('-'), $key);
					break;
			}
			
			$standarised[$type] = $value;
		}
		
		// filter for the correct types
		if(!empty($standarised['property-number']) && !empty($standarised['street-address'])) {
			$standarised['street-address'] = $standarised['property-number'] . ' ' . $standarised['street-address'];
		}
		
		$html			= '';
		//$standarised	= Arr::filter_keys($standarised, $valid_types);
		foreach($standarised as $type => $value) {
			$html .= \Html::span($value, array('class' => $type)).PHP_EOL;
		}
		
		return Html::html_tag('address', $attr, $html);
	}
	
	/**
	 * Create a XHTML tag
	 *
	 * @param	string			The tag name
	 * @param	array|string	The tag attributes
	 * @param	string|bool		The content to place in the tag, or false for no closing tag
	 * @return	string
	 */
	public static function html_tag($tag, $attr = array(), $content = false)
	{
		$has_content = (bool) ($content !== false and $content !== null);
		$html = '<'.$tag;

		$html .= ( ! empty($attr)) ? ' '.(is_array($attr) ? self::array_to_attr($attr) : $attr) : '';
		$html .= $has_content ? '>' : ' />';
		$html .= $has_content ? $content.'</'.$tag.'>' : '';

		return $html;
	}
	
	/**
	 * Takes an array of attributes and turns it into a string for an html tag
	 *
	 * @param	array	$attr
	 * @return	string
	 */
	public static function array_to_attr($attr)
	{
		$attr_str = '';

		if ( ! is_array($attr))
		{
			$attr = (array) $attr;
		}

		foreach ($attr as $property => $value)
		{
			// Ignore null values
			if (is_null($value))
			{
				continue;
			}

			// If the key is numeric then it must be something like selected="selected"
			if (is_numeric($property))
			{
				$property = $value;
			}

			$attr_str .= $property.'="'.$value.'" ';
		}

		// We strip off the last space for return
		return trim($attr_str);
	}
}