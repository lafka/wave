<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors
 * All rights reserved
 *
 * 404 not found error page
 *
 * @package     wave
 * @version     0.2
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license	    ./LICENSE BSD 3-Clause License
 */
 
global $err;

$err['code']  = 404;
$err['title'] = 'Ooops! Are you lost?';
$err['text']  = 'We are sorry to conclude that what you where looking for is not here, you should try a different door.....';

include 'template.php';
