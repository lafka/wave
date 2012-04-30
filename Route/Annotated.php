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

abstract class Annotated extends Rails{

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

		for ($i = 0, $c = count($m[1]); $i < $c; $i++)
			if ($_SERVER['REQUEST_METHOD'] === $m[1][$i])
				$this->registerRoute($m[2][$i]);
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
				$this->req['param'] = array_intersect_key($m, array_flip($this->routes[$k[$i]]['params']));
				return true;
			}
		}
	}
}