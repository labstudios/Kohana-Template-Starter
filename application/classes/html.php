<?php defined('SYSPATH') or die('No direct script access.');

class HTML extends Kohana_HTML {
    
    public static function script($file, array $attributes = NULL, $protocol = NULL, $index = FALSE)
	{
		if (strpos($file, '://') === FALSE)
		{
			// Add the base URL
			$file = URL::base($protocol, $index).$file;
		}

		// Set the script link
		$attributes['src'] = $file;

		// Set the script type
		//$attributes['type'] = 'text/javascript';

		return '<script'.HTML::attributes($attributes).'></script>';
	}
    
    public static function style($file, array $attributes = NULL, $protocol = NULL, $index = FALSE)
	{
		if (strpos($file, '://') === FALSE)
		{
			// Add the base URL
			$file = URL::base($protocol, $index).$file;
		}

		// Set the stylesheet link
		$attributes['href'] = $file;

		// Set the stylesheet rel
		$attributes['rel'] = 'stylesheet';

		// Set the stylesheet type
		//$attributes['type'] = 'text/css';

		return '<link'.HTML::attributes($attributes).' />';
	}
}