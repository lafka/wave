<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2011, 2010 - 2011 Frengstad Web Teknologi and contributors 
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list
 *  of conditions and the following disclaimer in the documentation and/or other materials
 *  provided with the distribution.
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
 * Basic abstraction for controllers 
 *
 * @package     wave
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi 
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt\Controller\Simple;

use UnexpectedValueException, \Fwt\Base, \Fwt\Controller\Iface, \Fwt\Utils\Filesystem;

abstract class Abstraction 
{
	/**
	 * Name of package
	 * @var string
	 */
	protected $_package;

	/**
	 * The current view
	 * @var string
	 */
	protected $_view;

	protected $_views;

	/**
	 * Processed request
	 * 
	 * @var array
	 */
	protected $_request;
	
	/**
	 * The base kernel object
	 * 
	 * @var \Fwt\Base
	 */
	public $base;

	/**
	 * Set basic information about component
	 *
	 *
	 * @param array $parts URI broken up
	 * @param array $comp the information about self
	 * @param Base  $base The base object
	 * @return void
	 */
	public function __construct ( array $parts, Base $base  )
	{
		$this->_package		= $parts['package'];
		$this->_view		= (array_key_exists( 'view', $parts ) ) ? $parts['view'] : null;
		$this->_request     = $parts;
		$this->base         = $base;
		$this->_views       = $this->availableViews();

		unset ( $comp, $parts, $base );
	}

	/**
	 * Get current view
	 *
	 * @return string view name
	 * @throws \UnexpectedValueException when no view is set
	 */
	public function currentView ()
	{
		if ( empty( $this->_view ) )
		{
			throw new UnexpectedValueException( __METHOD__ . ' there is no view set, please specify more accurate' );
		}

		return $this->_view;	
	}

	/**
	 * Find all available views
	 *
	 * Searches through the controller directory and find all loadable view
	 * 
	 * @return array Set of view for this controller
	 */
	public function availableViews ()
	{
		$views = array();

		$keys = array_keys( $this->_package['components'] );

		for ( $i = 0, $c = count($this->_package['components']); $i < $c; $i++ )
		{
			$root      = buildpath( __ROOT__, $this->_package['package'] );
			$wholename = buildpath( $root, $keys[$i], '*' );

			$views     = Filesystem::find( "{$root} -type f  ! -name '.*' -and -name '*.php' -and ! -name 'Controller.php' -iwholename '{$wholename}'" );

			$length    = strlen( $wholename ) - 1;

			for ( $x = 0, $y = count($views); $x < $y; $x++ )
			{
				//	Cut the package, component, and suffix from the name
				$views[$x] = substr( $views[$x], $length, -4 );
			}
		}

		return $views;
	}

	/**
	 * Check if a given view exists within a controller
	 * 
	 * @param string $view The view to check
	 * @return string|false The path to corresponding component or false
	 */
	public function hasView ( $view = Iface::USE_CURRENT_VIEW )
	{
		if ( $view === Iface::USE_CURRENT_VIEW )
		{
			$view = $this->currentView();
		}

		return array_search( $view, $this->_views );
	}

	/**
	 * Include a view
	 * 
	 * Loads up the given view if it exists
	 * 
	 * @param string $view The view to use
	 * @return void
	 * @throws UnexpectedValueException
	 */
	public function loadView ( $view = \Fwt\Controller\Iface::USE_CURRENT_VIEW )
	{
		if ( $view === Iface::USE_CURRENT_VIEW )
		{
			$view = $this->currentView();
		} 

		if ( false !== ($key =$this->hasView( $view ) ) )
		{
			$component = array_cs_search( $this->_request['controller'], $this->_package['components']);
			
			include buildpath( __ROOT__,  $this->_package['package'], 
			                   $component, 
			                   "{$view}.php" );
			return;
		}

		throw new UnexpectedValueException( __METHOD__ . " could not load view {$view}, it was not found in " .
		                                    "'{$this->_package['package']}' package" );
	}
	
	/**
	 * By default initialization is not required
	 * 
	 * @return boolean True
	 */
	public function init ()
	{
		return true;
	}

	/**
	 * Remove object properties
	 *
	 * @return void
	 */
	public function __destruct ()
	{
		unset( $this->base );
	}
}

