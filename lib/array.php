<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Library of array functions
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

 /**
 * Finds the key based on case-insensetiv search
 *
 * @param string|int $needle The needle to search for
 * @param array $haystack Array to search through
 * @return mixed|false The key or false if not found
 */
function array_cs_search ( $needle, array $haystack )
{
	$keys = array_keys( $haystack );
	for ( $i = 0, $c = count($haystack); $i < $c; $i++ )
	{
		if ( 0 === strcasecmp($needle, $keys[$i]) )
		{
			return $keys[$i];
		}
	}

	unset( $haystack );

	return false;
}

/**
 * Compute the difference 2 arrays based on index
 *
 * @param array $arr The initial array
 * @param [ array $arr2 Additional array]
 * @return array The difference
 */
function array_diff_seq ( array $arr )
{
	$argv = func_get_args();
	$argc = func_num_args();
	$ret  = array();

	for ( $i = 0, $c = count($arr); $i < $c; $i++, $equal = false )
	{
		for ( $x = 1; $x < $argc; $x++ )
		{
			if ( !isset($argv[$x][$i]) || $argv[$x][$i] !== $arr[$i] )
			{
				$ret[$i] = $arr[$i];
			}
		}
	}

	unset( $argc, $argv, $x, $i );

	return $ret;
}

/**
 * Merge arrays with matching keys
 *
 * @param array $arr The initial array
 * @param [ array $arr2 Additional array]
 * @param [ array ... ]
 * @return array
 */
function array_merge_distinct( array $arr )
{
	$argv = func_get_args();

	array_walk( $argv, function ($array) use (&$arr) {
		$keys = array_keys( $array );

		for ( $i = 0, $c = count($keys); $i < $c; $i++ )
			if ( array_key_exists( $keys[$i], $arr ) )
				$arr[$keys[$i]] = $array[$keys[$i]];
	});

	unset( $argv );

	return $arr;
}

/**
 * Filter an array based on value matching regex
 */
function array_filter_regex ($array, $regex) {
	return array_filter( $array, function ($value) use ($regex) { 
		return 0 !== preg_match($regex, $value);
	});
}


function array_filter_regex_key ($array, $regex) {
	return array_intersect_key($array, array_flip(array_filter_regex(array_keys($array), $regex)));
}