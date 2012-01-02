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
