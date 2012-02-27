<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors
 * All rights reserved
 *
 * 403 Forbidden page 
 *
 * @package     wave
 * @version     0.2
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     ./LICENSE BSD 3-Clause License
 */

global $err;

$err['code']  = 403;
$err['title'] = 'You shoulden\'t be here!';
$err['text']  = 'You don\'t have the correct privileges to access this page.';

include 'template.php';
