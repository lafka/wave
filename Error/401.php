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
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

header("HTTP/1.1 401 Unauthorized");
$this->_title = 'You shoulden\'t be here!';
$this->_error = 'It seems like you tried to go a place where you don\'t have access. Please authorize yourself to continue';

include 'default.php';
