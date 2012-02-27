<?php
/**
 * Wave PHP
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Functions for building paths
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 */

/**
 * Creates the namespace from $input
 *
 * Replaces all non-alphanumeric characters with namespace separator.
 *
 * @param string $package The input to divide into namespace sequence
 * @param boolean $array Flag to return as a array or string
 * @return string|array|false The namespace representation depending on $array or false if failed
 */
function parseNamespace ( $input, $array = false )
{
	$input = preg_replace( '/[^a-z0-9]/i', '\\', $input );

	return true === $array ? explode( '\\', $input ) : $input;
}