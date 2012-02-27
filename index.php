<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Initializes Wave core and starts dispatching requests
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 * @todo lafka 27-02-2012; Object freezer for Wave.Core to avoid subsequent fs scans for all requests
 */

namespace Wave;
use \LogicException;

define( '__DEBUG_ENABLED__', (array_key_exists( 'WAVE_ENV', $_SERVER ) && 'dev' === $_SERVER['WAVE_ENV']) ? true : false );

include 'Wave/Core.php';

// Enable profiling if debug is on
(constant('__DEBUG_ENABLED__') && extension_loaded('xhprof')) && xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

try {
	$core = new Core();

	ob_start();
	session_name( 'wsk' );
	session_start();

	$authenticated = isset($_SESSION) && array_key_exists( 'user_id', $_SESSION );

	$core->parsePackages();

	$code = 404;

	// Find me some routes
	$routes = glob('*/*/Route.php');
	for ($i = 0, $c = count($routes); $i < $c; $i++) {
		$routes[$i] = substr( strtr(Autoloader::parseToPath($routes[$i]), '/', '\\'), 0, -4);
		$route = new $routes[$i]($core, null);

		if ($route->match ($_SERVER['REQUEST_URI'])) {
			$code = $route->dispatch($_SERVER['REQUEST_URI']);
			break;
		}

		unset($route);
	}

	if ($code !== 200) {
		// Do crazyness and display error
		spl_autoload_call('resources/error/' . $code);
	}

	$content = ob_get_clean();
	//	We can use autoload call to load templates
	spl_autoload_call('resources/layout/header');
	echo $content;
	spl_autoload_call('resources/layout/footer');

	define('__RUNTIME_DONE__', true );
	/*
	$parts	    = Helper::process( $_SERVER['REQUEST_URI'], ($authenticated) ? 'nodes' : null  );
	$controller = Helper::factory( $parts, $base );

	if ( ! $controller->hasView( $parts['view'] ) )
	{
		__debug( "Could not find view '{$parts['view']}' in '" . get_class( $controller ) . "'.", '__MAIN__' );
		__debug( 'available views: ' . implode(', ', $controller->availableViews() ), '__MAIN__' );
		Helper::loadError( 404, $controller );
	} else {
		__debug( "loading {$parts['package']['path']}.{$parts['controller']}->{$parts['view']}", 'main' );
		$controller->loadView( $parts['view'] );
	}
	*/
} catch ( LogicException $e ) {
	__fatalexception($e);
}


(constant('__DEBUG_ENABLED__') && extension_loaded('xhprof')) && __xhprof_output();
