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
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

header("HTTP/1.1 403 Forbidden");
$this->_title = 'You shoulden\'t be here!';
$this->_error = 'You don\'t have the correct privileges to access this page.';

include 'default.php';
