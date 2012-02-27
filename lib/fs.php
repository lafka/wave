<?php
/**
 * Wave PHP
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Functions for filesystem functions
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
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