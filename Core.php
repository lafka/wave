<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Class holding initialization logic.
 *
 * Initializes {autoload,shutdown,error,exception} handlers
 *
 *   Autoload:
 *   Exception: 
 *   Error:
 *     Sets a HTTP "500 Internal Server Error" status code and displays exception information.
 *   Shutdown: Calls shutdown events
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @since     0.2
 */

namespace Wave;
use \RecursiveDirectoryIterator, \LogicException, \RuntimeException, \SplFileInfo,
	\DirectoryIterator, \Exception;	

class Core
{
	/**
	 * All available packages
	 *
	 * All the 
	 * 
	 * @var array
	 */
	protected $packages = array();

	/**
	 * List up all packages available
	 *
	 * Loops through all packages and find things to autoload, replace any
	 * non-alphanumeric character with namespace seperator. This might change
	 * in the future to only be 1 character. 
	 *
	 * @return void
	 */
	public function __construct ()
	{
		require __DIR__ . '/lib/array.php';
		require __DIR__ . '/lib/fs.php';
		require __DIR__ . '/lib/dev.php';
		require 'Wave/Autoloader.php';

		__debug( 'initiated base class (' . __CLASS__ . ').', 'main' );

		$this->init();
	}
	
	/**
	 * Initialize system
	 *
	 * Registers various functions with system:
	 *   - fatal error handler
	 *   - shutdown function
	 *   - autoload functions
	 *
	 * @return void
	 * @todo olav 27-02-2012 [event]; Add initialize event after exception handler
	 */
	public function init ()
	{
		__debug( 'initializing bootstrap sequence', 'main-init' );
		//	Register  the autoloader for Wave
		new Autoloader( 'Wave', basename( __DIR__ ), true );
		// Set shutdown function
		register_shutdown_function( array( $this, 'shutdown' ) );
		// Set error handler
		set_error_handler( array( $this, 'errorHandler' ) );
		set_exception_handler( array( $this, 'exceptionHandler' ) );

		__debug( 'finished bootstrap sequence', 'main-init' );
	}

	/**
	 * Error handler function
	 *
	 *
	 * @param int $errno The error code
	 * @param string $errstr The error message
	 * @param string $errfile The file error was triggerd
	 * @param int $errline The line error was triggered
	 * @param array $errcontext Active symbol table for scope error was triggered in
	 * @return void
	 */
	public function errorHandler ( $errno, $errstr, $errfile = '', $errline = 0, array $errcontext = array() )
	{
		// This error code is not included in error_reporting
	    if ( ! __DEBUG_ENABLED__ && !(error_reporting() & $errno) ) {
	    	__debug( "{$errstr} in file {$errfile}:{$errline}", "error [{$errno}]:" );
			return;
		}

		__debug( "{$errstr} in file {$errfile}:{$errline}", "error [{$errno}]" );
	}

	public function exceptionHandler ( Exception $e )
	{
		header( "HTTP/1.1 500 Internal Server Error" );
		echo "<!DOCTYPE html>\r\n";
		echo "<html>\r\n";
		echo "<head><title>A serriouse error occured</title></head>\r\n";
		echo "<body>\r\n";
		echo "<div style=\"margin: 2em auto; width: 768px; border-bottom: 1px solid #eee;\">\r\n";
		echo "<h1>An error occured</h1>\r\n";
		echo "<p>\r\n";
		echo $e->getMessage();
		echo "</p>\r\n";
		if ( __DEBUG_ENABLED__ )
		{
			echo "<hr />\r\n";
			echo "<p>\r\n";
			echo "in file {$e->getFile()} on {$e->getLine()}";
			echo "</p>\r\n";
			echo "<pre>\r\n";
			echo $e->getTraceAsString();
			echo "</pre>\r\n";
		}
		echo "</div>\r\n";
		echo "</body>\r\n";
		echo "</html>\r\n";
	}
	
	/**
	 * Called at the end of each script
	 * 
	 * @return void
	 * @todo olav 27-02-2012 [event]; Notify event handler of shutdown
	 * @todo olav 27-02-2012 [debug]; Move debug into a shutdown event based on __DEBUG_ENABLED__ constant
	 */
	public function shutdown ()
	{
		if ( __DEBUG_ENABLED__ && null === constant('__RUNTIME_DONE__') )
		{
			__debug_output();
			__debug_globals();
		}

		if (!defined('__RUNTIME_DONE__'))
			header( "HTTP/1.1 500 Internal Server Error" );

		unset( $this );
	}

	/**
	 * Scan for packages
	 *
	 * Adds all basefolder (with exceptions*) as packages and scans them for
	 * components.
	 *
	 * * libphutil and presentation is not scanned. Will change
	 *
	 * @return void
	 */
	public function parsePackages ()
	{
		// Find and parse packages
		$dir    = new DirectoryIterator(dirname(__DIR__)); 

		foreach ( $dir as $item )
		{
			if ( ! $item->isDir() || '.' === substr($item->getFilename(), 0, 1) )
			{
				continue;
			}

			$package = Autoloader::parseToPath( $item->getFilename() );
			$this->_packages[ $package ] = Package::factory($package);

			unset( $package, $item );
		}

		unset($dir);
	}

	/**
	 * Find packages
	 * 
	 * @return array all available packages 
	 */
	public function packages ()
	{
		return $this->_packages;
	}

	public function package ($package) {
		$package = Autoloader::parseTopath($package);
		if (!isset($this->_packages[$package]))
			return false;
		return $this->_packages[$package];
	}

	public function __destruct ()
	{
		global $debugmsg;
		$keys = array_keys($this->packages);
		for ( $i = 0, $c = count($keys); $i < $c; $i++ )
			unset($this->$keys[$i]);
		if ( __DEBUG_ENABLED__ && null !== constant('__RUNTIME_DONE__') && !defined('SKIP_END_DEBUG') )
		{
			echo "<ul>\n";
			foreach ($debugmsg as $msg)
				printf('<li class="debug-line">%s</li>', $msg);
			echo "</ul>\n";

			__debug_globals();
		}
	}
}
