<?php
/**
 * Wave PPHPPSADSAD
 * @todo fill in license
 *
 * Functions for building paths
 *
 * @package   wave
 * @version   0.1
 * @author    Olav Frengstad <olav@fwt.no>
 * @copyright Frengstad Web Teknologi
 * @license   BSD
 */

/**
 * Builds all the arguments to a path
 * 
 * Takes all arguments and builds a path using the native directory seperator
 *
 * @param string [ $elem1 Path element
 * @param string [ $elem2 Path element
 * @param string [ $elem3 Path element
 * @return string Path
 */
function buildpath ( )
{
	return implode( DIRECTORY_SEPARATOR, func_get_args() );
}

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

/**
 * Add a debug message
 *
 * @param string $msg The message line to add
 * @param string [ $label Add a label to the message ]
 * @return void
 */
function __debug ( $msg, $label = '' )
{
	if ( ! defined( '__DEBUG_ENABLED' ) )
	{
		return;
	}

	\Fwt\Utils\Debug::add( $msg, $label );
}

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