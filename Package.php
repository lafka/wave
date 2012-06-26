<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Basic runtime component for routing 
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 * @todo olav 28-02-2012; Issue when re-creating package - object should be stored as a traceable but it does not reload from \Wave\Object\Traceable correctly
 */
namespace Wave;

use \RuntimeException, \RecursiveIteratorIterator, \RecursiveDirectoryIterator, \DirectoryIterator, \FilesystemIterator,
    \Wave\Object\Traceable;

/**
 * @property-read \Wave\Autoloader $autoloader The autoloader for this package
 */
class Package extends Traceable {
	public $path;

	public $package;

	public static $packageList;
	/**
	 * Autoloader instance
	 *
	 * @var \Wave\Autoloader
	 */
	protected $_autoloader;

	/**
	 * Construct a new package based on path
	 *
	 * @param string $path The relative path to package from current working directory
	 * @return \Wave\Package
	 */
	public function __construct ($path) {
		if ( !is_dir($path) )
			throw new RuntimeException("Given path was did not exists or was not a directory");

		$this->path    = $path;
		$this->package = str_replace('/', '\\', Autoloader::parseToPath($path));

		__debug("constructing package {$this->package} from path: '{$this->path}'", 'package');

		$this->_autoloader = new Autoloader($this->package, $path, true);
		$this->storeObjects = true;
	}

	/**
	 * Load up a series of packages
	 *
	 * @param string $path The relative path to package from current working directory
	 * @return \Wave\Package The package instance - will reuse old object if possible
	 * @throws RuntimeException If $path does not exists or is not directory
	 */
	public static function factory ($path) {
		if ( 0 === count(static::$packageList) ) {
			static::$packageList = static::packageList();
			arsort(static::$packageList);
		}
		//	Replace directory separator with . so it can be used directly with the regex
		$regex = strtr(Autoloader::parseToPath($path),'/', '.');
		$path  = strtr($regex, '.', '\\');

		$items = array_filter_regex_key( static::$packageList, "~^{$regex}(?:[^a-z0-9]|$)~i");
		$keys  = array_keys($items);

		count($items) && __debug( 'found nested packages: ' . implode(', ', $keys), 'package' );

		for ($i = 0, $c = count($items); $i < $c; $i++) {
			if (static::instanceExists($keys[$i]))
				continue;

			$class = '\Wave\Package';
			if (is_readable($items[$keys[$i]] . '/Package.php')) {
				__debug(sprintf("package %s: using %s for package handler", $path, $items[$keys[$i]] . '/Package.php'));
				$class = str_replace('/', '\\', Autoloader::parseToPath($path) . '/' . 'Package');
				class_exists($class) || include $items[$keys[$i]] . '/Package.php';
			} elseif (is_readable($items[$keys[$i]] . '.php')) {
				__debug(sprintf("package %s: using %s for package handler", $path, $items[$keys[$i]] . '.php'));
				include $items[$keys[$i]] . '.php';


				$tmp = str_replace('/', '\\', Autoloader::parseToPath($items[$keys[$i]]));

				//	The bootstrap might not be a class, therefor we check for the class and if not
				//	pass control back to the default package handler.
				if (class_exists($tmp, false))
					$class = $tmp;

				unset($tmp);
			}
			$obj = new $class($items[$keys[$i]]);
			$obj->instanceRegister($keys[$i], __CLASS__);
		}

		return static::instanceGet($path);
	}

	/**
	 * Runs through all the packages contained within root directory
	 * 
	 * @return array List of all the packages 
	 */
	public static function packageList () {
		$dir    = new DirectoryIterator(dirname(__DIR__)); 
		$list   = array();

		foreach ( $dir as $item ) {
			if ( !$item->isDir() || $item->isDot() || '.' === substr($item->getFilename(), 0, 1) )
				continue;

			$list[str_replace('/', '\\', Autoloader::parseToPath($item->getFilename(), 15))] = $item->getFilename();
		}

		unset( $dir );

		return $list;
	}

	public function __get ($key) {
		if ('autoloader' === $key)
			return $this->_autoloader;	

		return;
	}

	public function __destruct () {
		if ( 0 === $this->count() ) {}
		$this::instanceRemove(__CLASS__);

		unset ($this->_autoloader);
	}


 }