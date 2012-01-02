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
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

header("HTTP/1.1 404 Not Found");
$this->_title = 'Ooops! Are you lost?';
$this->_error = 'We are sorry to conclude that what you where looking for is not here, you should try a different door.....';

include 'default.php';
