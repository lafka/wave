<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Template for error pages
 *
 * @package	  wave
 * @version	  0.2
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  ./LICENSE BSD 3-Clause License
 */

global $err;

$codes = array(
	401 => 'Unauthorized',
	403 => 'Forbidden',
	404 => 'Not Found',
	500 => 'Internal Server Error',
);
/*
$codes = array(
	400 => 'Bad Request',
	401 => 'Unauthorized',
	402 => 'Payment Required',
	403 => 'Forbidden',
	404 => 'Not Found',
	405 => 'Method Not Allowed',
	406 => 'Not Acceptable',
	407 => 'Proxy Authentication Required',
	408 => 'Request Timeout',
	409 => 'Conflict',
	410 => 'Gone',
	411 => 'Length Required',
	412 => 'Precondition Failed',
	413 => 'Request Entity Too Large',
	414 => 'Request-URI Too Long',
	415 => 'Unsupported Media Type',
	416 => 'Requested Range Not Satisfiable',
	417 => 'Expectation Failed',
*/


header("HTTP/1.1 {$err['code']} {$codes[$err['code']]}");

?>
	<div class="error">
		<h1><?php echo $err['title']; ?></h1>

		<p>
			<?php echo $err['text']; ?>	
		</p>

		<?php
		if ( defined('__DEBUG_ENABLED__') && true === __DEBUG_ENABLED__ )
		{
			if  ( isset($err['exception']) && $err['exception'] instanceof Exception )
			{
				echo "<hr />";
				echo "{$$err['exception']->getMessage()}\r\nIn {$$err['exception']->getFile()} on line {$$err['exception']->getLine()}";
				echo "<hr />";
			}
			echo "<pre>";
			debug_print_backtrace();
			echo "</pre>";
		}
?>
	</div>