<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Route which takes multiple routes and goes through them to look for a match
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave\Route;
use Wave\Object\Traceable, \Countable, \RuntimeException;

/**
 * <code>
 * $route  = new CycleRoute($core);
 * $route->addCycle( new ResourceRoute($core), new ContentRoute($core)  );
 * $route->match($_SERVER['REQUEST_URI']) && $code = $route->dispatch($_SERVER['REQUEST_URI']);
 * @todo olav 2012-03-09; Make traversable
 */
class Cycle extends Traceable implements Countable {

	/**
	 * The current route being matched
	 * @var \Wave\Route\Iface
	 */
	protected $pointer;

	/**
	 * Lis of routes
	 * @var array
	 */
	protected $routes = array();

	/**
	 * @var \Wave\Core
	 */
	protected $core  = null;

	/**
	 * Cycling routes
	 *
	 * @param \Wave\Core $core The core object 
	 * @param \Wave\Route\Iface $routeA optional A route to add to the cycle
	 * @param \Wave\Route\Iface $routeB optional A route to add to the cycle
	 * @param \Wave\Route\Iface $routeC optional A route to add to the cycle
	 */
	public function __construct (\Wave\Core $core, $uri = null ) {
		if ($uri instanceof Iface)
			$routes = array_filter_class(func_get_args(), 'Iface');
			
		null !== $uri && $this->regex = $uri;
	}

	/**
	 * Add a route to the cycle
	 *
	 * @param \Wave\Route\Iface $route The route to add into the cycle
	 * @return void
	 */
	public function addCycle () {
		$this->routes = array_merge($this->routes, array_filter_class(func_get_args(), '\Wave\Route\Iface'));
	}

	/**
	 * Matches a route 
	 *
	 * Loops through all defined routes and 
	 *
	 * @param string $uri The uri to match against regex
	 * @return boolean Status of match
	 */
	public function match ($uri) {
		for ($i = 0, $c = count($this); $i < $c; $i++) {
			if ($this->routes[$i]->match ($_SERVER['REQUEST_URI'])) {
				$this->pointer = $this->routes[$i];
				return true;
			}
		}

		return false;
	}

	/**
	 * Dispatches the route if any found
	 *
	 * @param string $uri The URI to dispatch
	 * @return integer HTTP status code
	 * @throws RuntimeException If no route was found
	 */
	public function dispatch ($uri) {
		if (!$this->pointer instanceof Iface)
			throw new RuntimeException("No route was set");
		
		return $this->pointer->dispatch($uri);
	}

	/**
	 * Count number of registered routes
	 *
	 * @return integer The number of routes available in cycle
	 */
	public function count () {
		return count($this->routes);
	}

	public function __destruct () {
		unset ($this->core, $this->route);
	}
}