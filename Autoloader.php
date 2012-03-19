<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Autoloader class for supporting vendor and target namespaces
 *
 * <code>
 * // $autoloader = new Autoloader(<ns>, <path-to-ns>);
 * $autoloader = new Autoloader( 'Wave', 'Wave');
 * $autoloader->register('Wave', 'Wave');
 * $autoloader->register('Wave\Lib', 'Wave.Lib');
 * // Looksup Wave.Lib/Resource.php before Wave/Lib/Resource.php
 * $autoloader->load('Wave\Lib\Resource');
 * </code>
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

 namespace Wave;

 class Autoloader
 {
 	/**
 	 * Flag to additionally transliterate the namespace
 	 *
 	 * @var integer
 	 */
 	 const TRANSLITERATE_NS             = 1;
 	/**
 	 * Flag to convert underscores to namespace seperator
 	 *
 	 * @var integer
 	 */
 	const TRANSLITERATE_UNDERSCORE      = 2;
 	/**
 	 * Flag to convert underscores to namespace seperator
 	 *
 	 * @var integer
 	 */
 	const TRANSLITERATE_DOT             = 4;
 	/**
 	 * Flag to convert underscores all non alphanumeric-characters to NS separator,
 	 * contains TRANSLITERATE_DOT and TRANSLITERATE_UNDERCORE.
 	 *
 	 * @var integer
 	 */
 	const TRANSLITERATE_NONALPHANUMERIC = 14;

 	/**
 	 * Flag to controll transliteration of NS
 	 *
 	 * @var boolean
 	 */
 	protected $translitNs = 15;

 	/**
 	 * The include path for the autoloader
 	 *
 	 * @var string
 	 */
 	protected $path;

 	/**
 	 * The NS registered for this autoloader 
 	 *
 	 * @var string 
 	 */
 	protected $ns;

 	/**
 	 * Create a autoloader for a namespace at target path
 	 *
 	 * @param string $ns  The namespace to load for
 	 * @param string $path The include path to set for autoloader instance
 	 * @param string $register Register the autoloader globaly at instanciation
 	 * @return \Wave\Autoloader
 	 */
 	public function __construct ($ns = '', $path = './', $register = false) {
 		$this->ns   = $ns;
 		$this->path = $path;

 		$register && $this->register();
 	}

 	/**
 	 * Registers the autoload instance in the global scope
 	 * 
 	 * @return boolean Status of spl_autoload_register() call
 	 */
 	public function register () {
 		function_exists('__debug') && __debug("registering autoloader for {$this->ns} -> {$this->path}", 'autoload');
 		return spl_autoload_register(array($this, 'load'));
 	}

 	/**
 	 * Remove the autoload instance from global scope
 	 *
 	 * @return boolean Status of spl_autoload_unregister() call
 	 */
 	public function unregister () {
 		function_exists('__debug') && __debug("removing autoloader for {$this->ns} -> {$this->path}", 'autoload');
 		spl_autoload_unregister(array($this, 'load'));
 	}

 	/**
 	 * Takes a resource identifier and tries to load it
 	 *
 	 * @param string $resource Given resource to load
 	 * @param string $ext The fileextension to look for
 	 * @param boolean $return Flag to indicate return of path instead of loading it
 	 * @param boolean $translit Autoloader::TRANSLITERATE_UNDERSCORE | Autoloader::TRANSLITERATE_DOT | Autoloader::TRANSLITERATE_NONALPHANUMERIC | Autoloader::TRANSLITERATE_NS
 	 * @return boolean The status of the fileloading
 	 */
 	public function load ($resource, $ext = '.php', $return = false, $translit = null) {
 		null === $translit && $translit = $this->translitNs;
 		$path = static::parseTopath($resource, $translit) . $ext;

 		// Check if we working with a absolute path, otherwise prepend the package
 		// path so we can find items without specifing the full path.
 		if ( 0 !== strpos($resource, $this->ns) )
 			$path = $this->path . "/" . $path;
 		
 		$path = str_replace(strtr($this->ns, '\\', '/'), $this->path, $path);

 		if (!is_readable($path)) {
 			return false;
 		} elseif (is_dir($path)) {
 			return false;
 		}
 		
 		!$return && include $path;

 		__debug( "found resource {$resource} at {$path}", "autoload[{$this->ns}]");
 		return ($return) ? $path : true;
 	}

 	/**
 	 * Parses a given string to a filepath
 	 *
 	 * @param string $class The full classname to parse
 	 * @return string The path to class - without extension
 	 */
 	public static function parseToPath ($class, $flags = 15) {
 		$pos   = false;
 		$base  = '';
 		$conc  = (0 === ($flags & static::TRANSLITERATE_NS));

 		if ($conc && false !== ($pos = strrpos($class, '\\'))) {
 			$pos   = strrpos($class, '\\');
 			$base  = substr($class, 0, $pos );
 			$class = substr($class, $pos + 1);
 		}

 		if (0 !== ($flags & static::TRANSLITERATE_UNDERSCORE)) 
 			$class = str_replace('_', '/', $class);

 		if (0 !== ($flags & static::TRANSLITERATE_DOT)) 
 			$class = str_replace('.', '/', $class);

		if (0 !== ($flags & static::TRANSLITERATE_NONALPHANUMERIC)) 
 			$class = preg_replace('/[^a-z0-9]/i', '/', $class);

		//$input = preg_replace( '/[^a-z0-9]/i', '\\', $input );
		return str_replace('\\', '/', (true === $conc && false !== $pos? ($base . '\\' . $class) : $class));
 	}
 }