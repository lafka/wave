<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Rails like routes
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave\Route;

use LogicExcpetion;

/**
 * @property-read string $$rails_param The value of rails_param or empty string
 */
class Rails {

	/**
	 * @var \Wave\Core
	 */
	protected $core  = null;

	/**
	 * Additional options
	 *
	 * @var array
	 */
	protected $opts = array();

	/**
	 * List of registered routes
	 *
	 * @var array
	 */
	protected $routes = array();

	/**
	 * Request information
	 *
	 * @var array
	 */
	protected $req     = array(
		'method' => 'UNKNOWN',
		'uri'    => '',
		'param'  => array(),
	);

	/**
     * Initializes a route
     *
     * @param \Wave\Core $core The core object 
     * @param mixed $options Additional options to send to route
     * @return \Wave\Route\Iface
     */
	public function __construct (\Wave\Core $core, $opts = null) {
		$this->core = $core;
		$this->opts = $opts;

		$this->req['method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'cli';
		$this->req['uri']    = isset($_SERVER['REQUEST_URI'])    ? $_SERVER['REQUEST_URI']    : '';
	}

	/**
	 * Get a request parameter
	 *
	 * @param string $k The key to get
	 * @return string The value of param $k or empty string
	 */
	public function __get ($k) {
		return (isset($this->req['param'][$k])) ? $this->req['param'][$k] : '';
	}

	/**
	 * See if there is a match for a request
	 *
	 * @param string $uri The dispatched uri
	 * @return boolean Status of match
	 */
	public function match ($uri = null) {
		throw new LogicExcpetion("Rails route is not implemented yet....");
	}

	/**
	 * Register a route
	 *
	 * @param string $route The new route to add
	 * @param string $method Method to match on or null to perform on all
	 * @return string Key to use to refetch route from {@link Rails::$routes}
	 */
	protected function registerRoute ($route, $method = null) {
		$key = md5($method . "-" . $route);

		preg_match_all('~/:([^/]+)?~', $route, $params);

		$this->routes[$key] = array(
			'route'  => $route,
			'regex'  => "#^" . preg_replace('~/:([^/]+)~', '/(?P<\1>[^/]+)', $route) . "#",
			'method' => $method,
			'params' => $params[1],
		);

		unset($params, $route, $method);

		return $key;
	}
}