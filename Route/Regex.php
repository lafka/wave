<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Abstract route for fetching based on regex
 *
 * @package	  wave.lib
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave\Route;
use Wave\Object\Traceable;

abstract class Regex extends Traceable {
	protected $regex = '^$';
	/**
	 * @var \Wave\Core
	 */
	protected $core  = null;

	/**
	 * Route for matching regexes
	 *
	 * @param \Wave\Core $core The core object 
	 * @param string $uri A refined regex for matching resources
	 * @return \Wave\Lib\Resources\Route The route
	 */
	public function __construct (\Wave\Core $core, $uri = null ) {
		$this->core = $core;
		null !== $uri && $this->regex = $uri;
		$this->instanceRegister($uri);
	}

	/**
	 * Match URI to given regex
	 * @param string $uri The uri to match against regex
	 * @return boolean Status of match
	 */
	public function match ($uri) {
		return 0 !== preg_match($this->regex, $uri);
	}

	public function __destruct () {
		unset ($this->core);
	}
}