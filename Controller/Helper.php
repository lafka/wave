<?php
/**
 * {PROJECT_NAME}
 *
 * Copyright (c) 2011, Olav Frengstad and contributors.
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
: * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Helper functions for controller
 *
 * @package     wave 
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt\Controller;

use \Fwt\Base, RuntimeException;

abstract class Helper
{
	const DefaultController = "frontpage";

	/**
	 * Loop through package list to find load a controller
	 * 
	 * @param array $parts Uri parts to use
	 * @param Base  $base The base object
	 * @return Iface returns a controller
	 * @throws RuntimeException if no controller can be found
	 */
	public static function factory ( array & $parts, Base $base )
	{
		$targetpackage = false;
		$controller    = false;

		if ( array_key_exists( $parts['controller'], $base->uriMatch ) )
		{
			$controller = $base->uriMatch[$parts['controller']];
		} else {
			$targetpackage       = $base->package( 'Fwt' );
			$controller          = '\\Fwt\\Error\\Controller';
			$parts['controller'] = 'Error';
			$parts['view']       = '404';
			$parts['package']    = $targetpackage;
		}

		if ( class_exists( $controller ) )
		{
			$controller = new $controller( $parts, $base );
			return $controller;
		}

		throw new RuntimeException( __METHOD__ . " could not create new controller factory for '{$controller}'" );
	}

	/**
	 *
	 * @param Iface $controller the controller to use for processing
	 * @return array parts of url
	 */
	public static function process ( $uri, Iface $controller = null )
	{
		// Remove trailing slashes
		if ( 0 === stripos( $uri, '/' ) )
		{
			$uri = substr( $uri, 1 );
		}

		// Remove trailing slashes
		if ( '/' === substr( $uri, -1 ) )
		{
			$uri = substr( $uri, 0, -1 );
		}
		
		if ( empty( $uri ) )
		{
			//	Return default controller
			return array( 
				'controller' => static::DefaultController,
				'view'       => 'default',
			);
		}

		$uri               = explode( '/', $uri );
		$uri['controller'] = array_shift( $uri );
		$uri['view']       = ( count($uri) > 1 ) ? array_shift( $uri ) : 'default';

		return $uri;
	}
		
	/**
	 * Load error view
     *
     * Tries to look for a view with the given $statusCode in the current
     * $controller - if given. If the view is not found it will try to load
     * the view from the error component.
     *
     * A HTTP 404 error will be displayed if the view is not to be found any
     * of the controllers available.
	 *
	 * @param integer $statusCode HTTP status code
	 * @param \Fwt\Controller\Iface|null $controller The original controller
	 * @return void
	 */
	public static function loadError( $statusCode, \Fwt\Controller\Iface $controller = null )
	{
		if ( null !== $controller && $controller->hasView( $statusCode ) )
		{
			$controller->loadView( $statusCode );
		} else {
			//	Load default error package view
			$default = array(
				'controller' => 'error',
				'view'       => $statusCode
			);

			$proxy = \Fwt\Controller\Helper::factory( $default, $controller->base );
			if ( $proxy->hasView( $statusCode ) )
			{
				$proxy->loadView( $statusCode );
			} else {
				$proxy->loadView( 404 );
			}

            unset( $proxy, $default );
		}

        unset( $controller, $statusCode );
	}

}

