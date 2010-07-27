<?php
/**
 * Wave PHP Framework
 *
 * A PHP-Based framework for creating powerfull solutions to power the web.
 * Gives a base development platform to help with rapid development controlling
 * features like url dispatching, extension handling, template systems and
 * database managment.
 *
 * Copyright (c) 2010, Olav Frengstad and contributors.
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 *	* Redistributions of source code must retain the above copyright notice, this list of
 *	conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above copyright notice, this list
 * 	of conditions and the following disclaimer in the documentation and/or other materials
 * 	provided with the distribution.
 *
 *	* Neither the name of the Wave PHP Team nor the names of its contributors may be used
 *	to endorse or promote products derived from this software without specific prior
 * 	written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY dirECT, INdirECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Definitions needed for basic runtime
 *
 * The basic definitions used by system
 * for error handling, common keywords,
 * and other things.
 *
 * @package     Wave
 * @version     0.1
 * @copyright   2010 Olav Frengstad
 * @author      Olav Frengstad
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since       Wave PHP Framework 0.1
 * @todo        phpDoc Comments
 */
namespace Wave {

if ( !defined('Wave\dir\BASEPATH') )
    return null;
}

/**
 * Definitions used by system to function
 */

/**
 * Variabels to define debug levels Calling and creating of
 * objects/functions/methods are reserved from 0 - 128.
 * Errors are from 128 an onwards everything else are dynamicly
 * created with multiplying the last debug constant with 2.
 */
namespace Wave\debug {
    /**
     * Dump system information
     *
     * The debug level for printing out information
     * regarding the system
     */
    const SYSTEM        = 0;
    /**
     * Dump debug info
     *
     * The debug level for printing out all the messages so far
     */
    const DUMP          = 1;

    /**
     * Notify of new object
     *
     * The debug level for constructing new objects
     */
    const CONSTRUCT     = 2;

    /**
     * Notify of object destruction
     *
     * The debug level for constructing new objects
     */
    const DESTRUCT      = 4;
    /**
     * Notify of function calls
     * The debug level for calling functions/methods
     */
    const CALL          = 8;

    /**
     * Notify of overload function calls
     *
     * The debug level for calling functions related to overloading
     */
    const OVERLOAD_CALL = 16;

    /**
     * Notify of loading new items
     * The debug level for loading anything ( files, componets etc
     */
    const LOAD          = 32;

    /**
     * Generic message
     *
     * A generic message to the system.
     */
    const NOTICE        = 64;

    /**
     * Panic error level
     *
     * Similar to E_ERROR, here the script
     * have encountered something which under
     * no circumstances should happend and it
     * have no idea of what to do.
     * Will stop execution of immediatly
     */
    const ERROR_PANIC   = 128 ;

    /**
     * Fatal error level
     *
     * Similar to E_ERROR, here the script
     * have encountered something which
     * is not working as it should. *
     * Will stop execution of immediatly.
     */
    const ERROR_FATAL =   256;

    /**
     * Critical error level
     *
     * Similar to E_ERROR, here the script
     * have found some vital parts of the
     * system to be missing.
     * Calls shutdown functions and garbage
     * collection and can display a nice error
     * message to end user.
     */
    const ERROR_CRITICAL    = 512;

    /**
     * Generic error level
     *
     * Similar to E_USER_ERROR, not fatal
     * but should be looked at immediatly.
     * Will not terminate script.
     */
    const ERROR             = 1024;

    /**
     * Warning error level
     *
     * Some part of the system have generated
     * an error which is should be looked at
     * it may/may-not stop system from functioning
     * Script will continue
     */
    const ERROR_WARNING     = 2048;

    /**
     * Notice error level
     *
     * Identical to E_ERROR_NOTICE, something
     * has gone wrong but it will not terminate
     * the runtime. Should be looked at in the future.
     */
    const ERROR_NOTICE      = 4096;
}

namespace Wave\state {
    /**
     * Queed state
     *
     * Nothing have started yet
     */
    const WAITING   = 1;

    /**
     * Initiated state
     *
     * The action/level is running right now
     */
    const STARTED   = 2;
    /**
     * Finished state
     *
     * Everything went fine and the action/level
     * is finisheed
     */
    const FINISHED  = 4;
    /**
     * Stopped state
     *
     * The item have been stopped externaly
     */
    const STOPPED   = 8;
    /**
     * Paused state
     *
     * Things have been paused for something
     * else to take place. Will ( most-likely )
     * resume later
     */
    const PAUSED    = 16;
    /**
     * Error state
     *
     * A action/level has stopped becouse of an error that occured.
     */
    const ERROR     = 32;
}

namespace Wave\request {
    /**
     * Access protocol
     *
     * The protocol used to access the system
     * should either be http or https
     * @todo update to use more protocols as the system learns how to handle them
     */
    define( 'PROTOCOL',             ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://' ) );
    /**
     * Full domain
     *
     * The full domain used to access current site with
     */
    define( 'DOMAIN',               PROTOCOL . $_SERVER['HTTP_HOST'] );
    /**
     * Request URI
     *
     * The requested URI used for dispatching
     */
    define( 'REQUEST_URI',          $_SERVER['REQUEST_URI'] );
    /**
     * Current URL
     *
     * The full URL used to access the current page
     */
    define( 'URL',                  DOMAIN . $_SERVER['REQUEST_URI'] );
}

namespace Wave\bootstrap {
    /**
     * Runlevel path
     *
     * The path to the local application runlevel
     */
    define('INIT',      \Wave\dir\USER . \Wave\keyword\LOCAL . '.xml' );
}

namespace Wave\event {
    /**
     * Event update identification
     *
     * Since we change how things are handled
     * under the way, we need a way to retrieve,
     * clean up and send the old data along.
     *
     * Any function who recieves this got to do that.
     */
    define('Wave\event\UPDATE',       md5('Wave\event\UPDATE') );
}