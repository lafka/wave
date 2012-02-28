<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Library of development functionality
 *
 * @package	  wave 
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  /LICENSE BSD 3-Clause License
 * @since     0.2
 */
global $debugmsg;
$debugmsg = array();
/**
 * Add a debug message
 *
 * @param string $msg The message line to add
 * @param string [ $label Add a label to the message ]
 * @return void
 */
function __debug ( $msg, $label = '' ) {
	global $debugmsg;
	if ( ! defined( '__DEBUG_ENABLED__' ) )
	{
		return;
	}

	$debugmsg[] = ( !empty($label) ? "<b>{$label}: </b>" : '' ) . $msg;
}

/**
 * Output debug information
 *
 * @param string $line Output template to use with sprintf
 * @param string $wrap Output template to use with sprintf
 * @return void
 */
function __debug_output ($line = "\t<li>%s</li>\n", $wrap = "<ul>\n%s\n</ul>") {
	global $debugmsg;
	$out = '';

	for ($i = 0, $c = count($debugmsg); $i < $c; $i++)
		$out .= sprintf($line, $debugmsg[$i]);
	
	printf($wrap, $out);
	unset($out);
}

/**
 * Outputs SESSION, COOKIES, POST and GET in a formated manner
 * 
 * @return void
 */
function __debug_globals () {
	echo "<div id=\"debug\">\r\n";

		echo "<div id=\"session\">\r\n";
			echo "<h4>Session</h4>\r\n";
			var_dump( $_SESSION );
		echo "</div>\r\n";

		echo "<div id=\"cookies\">\r\n";
			echo "<h4>Cookies</h4>\r\n";
			var_dump( $_COOKIES );
		echo "</div>\r\n";

		echo "<div id=\"post\">\r\n";
			echo "<h4>Post</h4>\r\n";
			var_dump( $_POST );
		echo "</div>\r\n";

	echo "</div>\r\n";
}

/**
 * Display a fatal error for exception
 *
 * @param Exception $e The exception caught
 * @return void
 */
function __fatalexception (Exception $e) {
	ob_end_clean();
	// This means code breakage
	header( "HTTP/1.1 500 Internal Server Error" );
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "\t<head>\n";
	echo "\t<title>A seriouse error occured</title>\n";
	echo "\t</head>\n";
	echo "\t<body>\n";
	echo "\t\t<div class=\"error\"><pre>
	{$e->getMessage()}<hr />
	in {$e->getFile()} on line {$e->getLine()}
	</pre><hr /><pre>{$e->getTraceAsString()}</pre></div>";
	echo "\t</body>\n";
	echo "</html>\n";
}

function __xhprof_enable () {
	if (extension_loaded('xhprof'))
		return xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

	return;
}

function __xhprof_output () {
	if (extension_loaded('xhprof')) {
		$result = xhprof_disable();
		include_once '/usr/share/webapps/xhprof/xhprof_lib/utils/xhprof_lib.php';
	    include_once '/usr/share/webapps/xhprof/xhprof_lib/utils/xhprof_runs.php';

	    $run   = new \XHProfRuns_Default();
	    $runid = $run->save_run($result, $_SERVER['SERVER_NAME']);
	    echo '<a href="'. sprintf( "http://xhprof/index.php?run=%s&source=%s", $runid, $_SERVER['SERVER_NAME']) .'" target="_blank">Profiler output</a>';
	}
}