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

namespace Fwt;
use \Fwt\Controller\Helper, Exception;

define( '__DS__',          DIRECTORY_SEPARATOR );
define( '__ROOT__',        __DIR__ . __DS__ );
define( '__DEBUG_ENABLED', (array_key_exists( 'WAVE_ENV', $_SERVER ) && 'dev' === $_SERVER['WAVE_ENV']) ? true : false );

include __ROOT__ . 'Fwt/Base.php';

(\constant('__DEBUG_ENABLED') && extension_loaded('xhprof')) && xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

try {
	$base = new Base();
	$base->init();
	$base->parsePackages();

	ob_start();
	//session_name( 'wave.key' );
	session_start();
	
	$parts	    = Helper::process( $_SERVER['REQUEST_URI'] );
	$controller = Helper::factory( $parts, $base );

	if ( ! $controller->hasView( $parts['view'] ) )
	{
		__debug( "Could not find view '{$parts['view']}' in '" . get_class( $controller ) . "'.", '__MAIN__' );
		__debug( 'available views: ' . implode(', ', $controller->availableViews() ), '__MAIN__' );

		Helper::loadError( 404, $controller );
	} elseif ( true === $controller->ready ) {
		$controller->loadView( $parts['view'] );
	}

	$content = ob_get_clean();

	include 'presentation/header.php';
	echo $content;
	include 'presentation/footer.php';

	// Where done
	define('__RUNTIME_DONE', true );
} catch ( LogicException $e ) {
	// This means code breakage
	header( "Status: 500" );
	echo "<div class=\"error\"><pre>
	{$e->getMessage()}<hr />
	in {$e->getFile()} on line {$e->getLine()}
	</pre><hr /><pre>{$e->getTraceAsString()}</pre></div>";
}

if (constant('__DEBUG_ENABLED') && extension_loaded('xhprof')) {
	$result = xhprof_disable();
	include_once '/usr/share/webapps/xhprof/xhprof_lib/utils/xhprof_lib.php';
    include_once '/usr/share/webapps/xhprof/xhprof_lib/utils/xhprof_runs.php';

    $run   = new \XHProfRuns_Default();
    $runid = $run->save_run($result, 'events.hackeriet.org');
    echo '<a href="'. sprintf( "http://xhprof/index.php?run=%s&source=%s", $runid, 'events.hackeriet.org') .'" target="_blank">Profiler output</a>';
}