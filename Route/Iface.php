<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Basic dispatching capabilities based on a uri match
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since       0.2
 */

namespace Wave\Route;

/**
 * Interface that should be implemented by the different route types
 * All the routes is responsible for matching a request URI to their
 * own, if there is a match a subsequent call to dispatch can be issued
 * to process the request. 
 */
interface Iface {
    /**
     * Initializes a route
     *
     * @param string $uri The dispatched uri
     * @return \Wave\Route\Iface
     */
	public function __construct (\Wave\Core $core, $uri = null);

	/**
	 * See if there is a match for a request
	 *
	 * @param string $uri The dispatched uri
	 * @return boolean Status of match
	 */
	public function match ($uri);

	/**
	 * Perform logic for this route
	 *
	 * @param string $uri The URI to dispatch
	 * @return integer HTTP status code
	 */
	public function dispatch ($uri);
}
