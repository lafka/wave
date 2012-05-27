<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Annotated routes
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave\Route;

use ReflectionClass;

abstract class Annotated extends Rails {

	protected $options = array(
		//	Allow that this->var points to $_GET[var] if not set in URI
		'alignQueryParams' => true,
	);

	/**
	 * @var \Wave\Core
	 */
	protected $core  = null;


	/**
     * Initializes a route
     *
     * @param \Wave\Core $core The core object 
     * @param mixed $options Additional options to send to route
     * @return \Wave\Route\Iface
     */
	public function __construct (\Wave\Core $core, $opts = null) {
		$this->core = $core;

		$reflection = new ReflectionClass(get_called_class());
		preg_match_all('/@([A-Z]+) (.+)/', $reflection->getDocComment(), $m);

		for ($i = 0, $c = count($m[1]); $i < $c; $i++) {
			if ($m[1][$i] === $_SERVER['REQUEST_METHOD'])
				$this->registerRoute(trim($m[2][$i]), $m[1][$i]);
		}
	}

	/**
	 * Get a request parameter
	 *
	 * @param string $k The key to get
	 * @return string The value of param $k or empty string
	 */
	public function __get ($k) {
		if (!$this->options['alignQueryParams'])
			return parent::__get($k);


		if ('' === ($v = parent::__get($k)) && isset($_GET[$k]))
			return $_GET[$k];

		return $v;
	}

	/**
	 * See if there is a match for a request
	 *
	 * @param string $uri The dispatched uri
	 * @return boolean Status of match
	 */
	public function match ($uri = null) {
		$k = array_keys($this->routes);
		
		for ($i = 0, $c = count($k); $i < $c; $i++) {
			if (preg_match($this->routes[$k[$i]]['regex'], $uri, $m)) {
				$this->req['param']  = array_intersect_key($m, array_flip($this->routes[$k[$i]]['params']));
				$this->req['method'] = $_SERVER['REQUEST_METHOD'];
				$this->req['uri']    = $_SERVER['REQUEST_URI'];

				array_walk($this->req['param'], function (&$v) { $v = filter_var($v, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW); });
				return true;
			}
		}

		return false;
	}
}