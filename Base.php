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
use DirectoryIterator, LogicException, RuntimeException, SplFileInfo;

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
	protected $_packages = array();

	/**
	 * List up all packages available
	 *
	 * Loops through all packages and find things to autoload
	 *
	 * @return void
	 */
	public function __construct ()
	{
		require 'lib.php';
		//	Prioritize our autoloader
		spl_autoload_register( array( __CLASS__, 'autoload' ), true, true );

		$dir = new DirectoryIterator( __ROOT__ ); 

		foreach ( $dir as $item )
		{
			if ( $item->isDir() && 0 !== strpos( $item->getFilename(), '.' ) )
			{
				$package = preg_replace( '/[^a-z0-9]/i', '\\', $item->getFilename() );
                              
				$this->_packages[ $package ] = $this->package( $item, true, $package );
				unset( $package );
			}
			
			unset( $item );
		}

		unset( $dir );
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
	 * @return true 
	 * @throws LogicException when file not found
	 */
	public static function autoload ( $class )
	{
		$package = null;
		if ( false !== stripos( $class, '\\' ) ) 
		{
			$package = substr( $class, 0, strpos( '\\', $class ) );

			//  Namespaces are directly found in the path
			$class = str_replace( '\\', '/', $class );
		}
		
		$file = "{$class}.php";

		if( ! is_readable( __ROOT__ . $file ) )
		{
			throw new LogicException( "Could not find class {$class}, should be located in " . __ROOT__ );
		}
		
		include __ROOT__ . $file;
	
		return true;
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

	/**
	 * Fetch information about a package
	 *
	 * @param SplFileInfo|string $package The SplFileInfo or string represenation of package
	 * @param boolean $regenerate Flag to force regneration of package information
	 * @param string $alias Alias to real package 
	 * @return array Information about the package
	 */
	public function package ( $package, $regnerate = false, $alias = null )
	{
		static $skip = array('controller', '.git');

		$info = array(
			'package' => (string) $package,
		);

		//	Check that the package is found by previous directory iteration
		if ( array_key_exists( $info['package'], $this->packages() ) && false === $this->_packages[$info['package']] )
		{
			throw new RuntimeException( __METHOD__ . " there is not such package: {$info['package']}....." );
			return false;
		}

		if ( true !== $regnerate && array_key_exists( $info['package'], $this->packages() ) )
		{
				return $this->_packages[ $info['package'] ];
		}

		//	Get path to reinstance iterator so we don't need to make 
		$path = ($package instanceof DirectoryIterator) ? $package->getPathname() : __ROOT__ . $package;

		if ( ! is_dir( $path ) )
		{
			throw new RuntimeException( __METHOD__ . " could not find package {$info['package']} in path " . __ROOT__ );
		}

		$package = new DirectoryIterator( $path );

		unset( $path );
		
		foreach ( $package as $item )
		{
			if ( $item->isDir() && ! $item->isDot() && ! in_array( (string) $item, $skip ) )
			{
				$info['components'][(string) $item->current()] = $this->component( $info['package'], $item->getFilename(), true );
			}
		}

		unset( $package, $item );

		return $info;
	}

	/**
	 * Find information about a component
	 *	
	 * @param string $package the package to look within
	 * @param DirectoryIterator|string the component to scan
	 * @param boolean $regenerate don't use cached info
	 * @param boolean $lookuppackage if package don't exists, try to find it
	 * @return array component info
	 */
	public function component ( $package, $component, $regenerate = false, $lookuppackage = false )
	{
		static $setKey  = array('controller');

		$info = array(
			'package'	=> (string) $package,
			'component'  => (string) $component,
			'controller' => false,
			'views'		 => array(),
		);
		
		if ( !array_key_exists( $package, $this->packages() ) )
		{
			if ( true === $lookuppackage )
			{
				//	Regnerate recursivly
				$this->package( $package, true );
			}
		}
		
		if ( ! $component instanceof DirectoryIterator )
		{
			if ( ! is_dir( __ROOT__ . $info['package'] . __DS__ . $info['component'] ) )
			{
				throw new RuntimeException( __METHOD__ . " could not find component {$info['package']}.{$info['component']}");
			}

			$component = new DirectoryIterator( __ROOT__ . $info['package'] . __DS__ . $info['component'] );
		}
		
		foreach ( $component as $item )
		{
//			var_dump( $item->getPathname() );
			if ( $item->isDir() || $item->isDot() )
			{
				continue;
			} elseif ( in_array( $item->getBasename('.php'), $setKey ) ) {
				$info[ $item->getBasename('.php') ] = true;
			} elseif ( '.php' === substr($item->getBasename(), -4) ) {
				$info[ 'views' ][ substr( $item->getBasename(), 0, -4) ] = true;
			}
		}

		unset ( $component, $package );

		return $info;
	}

	/**
	 * Finds the componenets
	 *
	 * @param array $packages the selected packages to search
	 * @return array registered components
	 */
	public function components ( array $packages = null )
	{

	}
}
