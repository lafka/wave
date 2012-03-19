<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors
 * All rights reserved
 *
 * 401 Unauthorized error 
 *
 * @package     wave
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     ./LICENSE BSD 3-Clause License
 */

global $err, $e;

$err['code']  = 500;
$err['title'] = 'This is looks bad';
$err['text']  = 'We don\'t know excactly why, but a critical error occured.';

if (constant('__DEBUG_ENABLED__') && isset($e) && $e instanceof Exception)
	$err['text'] .= sprintf("\r\n\r\n<pre>%s\r\n\r\nIn %s on line %d with code %d\r\n</pre>", $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());

include 'template.php';
