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
function array_cs_search( $needle, array $haystack )
{
	$keys = array_keys( $haystack );
	for ( $i = 0, $c = count($haystack); $i < $c; $i++ )
	{
		if ( 0 === strcasecmp($needle, $keys[$i]) )
		{
			return $needle;
		}
	}

	unset( $haystack );

	return false;
}