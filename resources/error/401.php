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

global $err;

$err['code']  = 401;
$err['title'] = 'You shoulden\'t be here!';
$err['text']  = 'It seems like you tried to go a place where you don\'t have access. Please authorize yourself to continue';

include 'template.php';
