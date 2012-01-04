<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the Wave PHP Team nor the names of its contributors may be used
 * to endorse or promote products derived from this software without specific prior
 * written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS AS IS AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Basic runtime component for routing 
 *
 * @package	  wave 
 * @version	  0.1 
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt;
use RecursiveDirectoryIterator, LogicException, RuntimeException, SplFileInfo,
	DirectoryIterator;	

class Base
{
	/**
	 * All available packages
	 *
	 * A 2-dimensional array with following structure: 
	 *
	 * package => [ folder1 => [ comp1, comp2, .. n ],
	 *              folder2 => [ comp4, comp1, .. n ] ]
	 *
	 * Where 'package' is the first part of folder name, and 'folder*' is the
	 * actual folder name. So wave-auth/user will become this:
	 *
	 * wave => [ wave-auth => [ user ] ].
	 *
	 * @var array
	 */
	protected static $_packages = array();

	/**
	 * A list of URI's with dedicated controllers.
	 *
	 * key matches the URI prefix, and value is a reference to package 
	 * 
	 * @var array
	 */
	protected static $_uriMatch = array();

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
		require 'lib.php';
		require 'Utils/Debug.php';

		__debug( 'initiated base class (' . __CLASS__ . ').', __METHOD__ );

		//	Prioritize our autoloader
		spl_autoload_register( array( __CLASS__, 'autoload' ), true, true );

		$dir    = new DirectoryIterator( __ROOT__ ); 

		foreach ( $dir as $item )
		{
			if ( ! $item->isDir() || preg_match( "#^\.|^libphutil|^presentation|/nbproject#", $item->getFilename() ) )
			{
				continue;
			}

			$package = preg_replace( '/[^a-z0-9]/i', '\\', $item->getFilename() );
			static::$_packages[ $package ] = $this->package( $item, true, $package );

			unset( $package, $item );
		}

		unset( $iter, $dir );
	}
	
	/**
	 * Autoload with namespaces
	 *
	 * This does not comply with PSR-0 since there are multiple classpaths that
	 * is used within Wave. Instead we look by default in a PSR-0 compliant way
	 * if the class is found, if not a scan of all packages is done.
	 * 
	 * In more practical terms this means a a package name can be substituted
	 * on runtime. E.g wave-auth is really the wave package. This does only
	 * work with names concatenated with hyphens (everything after the first
	 * hyphen will be discarded)
	 *
	 * $param string $class The classname to load
	 * @return bool Status of autoload
	 */
	public static function autoload ( $class, array $packages = null )
	{
		$package = null;
		if ( false !== stripos( $class, '\\' ) ) 
		{
			if ( ! ($package = static::findPackageForClass( $class, array_keys( static::$_packages ) ) ) )
			{
				__debug( "could not find package for class {$class}", __METHOD__ );
			} else {
				$package = static::$_packages[$package]['path'];
			}

		}

		if ( null !== $package )
		{
			$pathlist = explode( '\\', $class );
			$file = call_user_func_array( 'buildpath', array_replace( $pathlist, array( $package ) ) ) . '.php';
		} else {
			$file = str_replace( '\\', '/', $class) . '.php';
		}


		if( is_readable( __ROOT__ . $file ) )
		{
			
			__debug( "loaded class '{$class}' from file '{$file}'", __METHOD__ );
			include __ROOT__ . $file;
			return true;
		}

		__debug( "could not autoload class {$class} (tried: {$file}", __METHOD__ );
		return false;
	}

	/**
	 * Find packages
	 * 
	 * @return array all available packages 
	 */
	public function packages ()
	{
		return static::$_packages;
	}

	/**
	 * Find the package where class belongs
	 *
	 * Searches through $haystack after $needle
	 *
	 * @param string $needle The classname to find package for
	 * @param array  $haystack Array with available package names as value
	 * @return string|null The package name or false if not found
	 */
	public static function findPackageForClass( $needle, array $haystack )
	{
		$targetNs = substr_replace( $needle,  '', strrpos( $needle, '\\' ) );

		if ( isset($haystack[$targetNs]) )
		{
			return static::$_packages[$targetNs]['package'];
		}

		$depth = substr_count( $needle, '\\' );

		// Filter out all packages that does not start with $needle or that
		// have a more specific namespace.
		$haystack = array_filter( $haystack, function ($v) use ($needle, $depth) {
			$occur = substr_count( $v, '\\' ); 
			
			return ( 0 === strpos( $needle, $v ) && 
			         $occur <= $depth ); 
		} );

		unset( $chars, $depth, $targetNs, $needle );

		usort( $haystack, function ( $a, $b ) { return strlen($b) - strlen($a); } );

		return array_key_exists( 0, $haystack ) ? $haystack[0] : null;
	}

	/**
	 * Fetch information about a package
	 *
	 * @param SplFileInfo|string $package The SplFileInfo or string represenation of package
	 * @param boolean $regenerate Flag to force regneration of package information <unused>
	 * @param string $alias Alias to real package 
	 * @return array Information about the package
	 */
	public function package ( $package, $regnerate = false, $alias = null )
	{
		$alias = null === $alias ? $package : $alias;
		$info = array(
			'package'    => $alias,
			'path'       => $package instanceof SplFileInfo ? $package->getFilename() : $package,
			'components' => array(),
		);

		unset( $alias );

		__debug( "parsing package '{$info['package']}' from path '{$info['path']}'", __METHOD__ );
	
		if ( ! $package instanceof SplFileInfo && ! is_dir( __ROOT__ . $package) )
		{
			throw new RuntimeException( __METHOD__ . " could not find package '{$info['package']}' in path '{$path}'" ); 
		}

		unset( $package );

		$raw = Utils\Filesystem::find( " {$info['path']} -type f -name 'Controller.php' ! -wholename '*/.*'" );

		$components = array();

		for ( $i = 0, $c = count($raw); $i < $c; $i++ )
		{
			// Fix namespaces and paths
			$key   = str_replace( $info['path'], '', $raw[$i] );
			$key   = trim( strtolower( $key ), '/' );
			$key   = preg_replace( '#/[^/]+$#', '', $key );
			$value = str_replace( $info['path'], $info['package'], $raw[$i] );

			static::$_uriMatch[$key]               = $value;
			$info['components'][$key] = str_replace( '/', '\\', $value );
		}

		unset( $key, $value, $raw );

		return $info;
	}
}
