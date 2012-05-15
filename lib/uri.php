<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Library of URI manipulation functions
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

/**
 * Fix REQUEST_URI key for a basepath
 *
 * Create the new version of _SERVER variables
 *
 * @param array $input The input array to fix
 * @return array The new _SERVER variable
 */
function fix_uri_base (array $input = array()) {
	if ('/' === dirname($_SERVER['SCRIPT_NAME']))
		return $input;
	
	$input['REQUEST_URI_BASE'] = substr($input['REQUEST_URI'], 0, strrpos($input['REQUEST_URI'], '/'));
	$input['REQUEST_URI']      = preg_replace("~^{$input['REQUEST_URI_BASE']}~", '', $input['REQUEST_URI']);

	return $input;
}

/**
 * Correct basepath
 * @param string $uri The URI to fix
 * @return string The new URI
 */
function uri ($uri) {
	if (!array_key_exists('REQUEST_URI_BASE', $_SERVER) || '/' === $_SERVER['REQUEST_URI_BASE'])
		return $uri;

	return $_SERVER['REQUEST_URI_BASE'] . '/' . $uri;
}