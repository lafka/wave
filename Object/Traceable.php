<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Provides a static table that allows tracing a object, otherwise known as a object pool.
 *
 * Keep in mind all object entered here will not be included in garbage collection cycle 
 * unless Traceable::removeInstance() is called explicitly and refcount is zero.
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave\Object;

use Countable;

abstract class Traceable implements Countable
{
	/**
	 * Flag to indicate object storage and not just refcount
	 *
	 * @var boolean
	 */
	protected $storeObjects = false;
	/**
 	 * The array containing all the instances
 	 *
 	 * @var array
 	 */
 	public static $trace     = array();

 	/**
 	 * Register a new instance of a class
 	 *
 	 * @param [string $key The key to use for uniqueness, will use object hash if empty]
 	 * @return string The key used to store object
 	 */
 	public function instanceRegister ($key = '') {
 		$class = get_class($this);
 		$key   = empty($key) ? spl_object_hash($this) : $key;

 		if (!isset(static::$trace[$class]))
 			static::$trace[$class] = (true === $this->storeObjects) ? array() : 0;

		true  === $this->storeObjects && static::$trace[$class][$key] = $this;
		false === $this->storeObjects && static::$trace[$class]++;

 		return $key;
 	}

 	/**
 	 * Decrease the refcount for a class
 	 *
 	 * @param [string $key The key to use for uniqueness, will use object hash if empty]
 	 * @return integer The count of items of sub-class
 	 */
 	public function instanceRemove ($key = '') {
 		$class = get_class($this);
 		$key   = empty($key) ? spl_object_hash($this) : $key;

 		if (isset(static::$trace[$class]) && false === $this->storeObjects)
 			static::$trace[$class]--;
 		elseif (isset(static::$trace[$class]) && isset(static::$trace[$class][$key]))
 			unset(static::$trace[$class][$key]);
 		
 		return $this->count();
 	}

 	/**
 	 * Checks whetever an object exists
 	 *
 	 * @param string $key The string identifier for obejct
 	 * @return boolean Whetever key exists
 	 */
 	public static function instanceExists ($key = '') {
 		return (isset(static::$trace[get_called_class()]) && isset(static::$trace[get_called_class()][$key]));
 	}

 	/**
 	 * Retrieve an instance for $key
 	 *
 	 * @param string $key The string identifier for obejct
 	 * @return mixed|null The stored value of $key
 	 */
 	public static function instanceGet ($key = '') {
 		return static::instanceExists($key) ? static::$trace[get_called_class()][$key] : null;
 	}

 	/**
 	 * Check the count for sub-class
 	 *
 	 * @return integer The number of instances for sub-class
 	 */
 	public function count () {
 		$class = get_class($this);

 		if (true === $this->storeObjects)
 			return (isset(static::$trace[$class])) ? count(static::$trace[$class]) : 0;
 		else
 			return (isset(static::$trace[$class])) ? static::$trace[$class] : 0;
 	}

}