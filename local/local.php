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
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Functions needed for basic runtime
 *
 * The bare minimum functions needed to retrieve
 * information and code from external places so
 * that the system can extend itself.
 *
 * @package     Wave
 * @version     0.1
 * @copyright   2010 Olav Frengstad
 * @author      Olav Frengstad
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since       Wave PHP Framework 0.1
 * @todo        phpDoc Comments
 */
 

namespace Wave;

if ( !defined('\Wave\dir\BASEPATH') )
    return null;

require \Wave\dir\LOCAL . \Wave\type\DEFINITION . \Wave\dir\EXT;
message( \Wave\debug\LOAD, \Wave\dir\LOCAL . \Wave\type\DEFINITION . \Wave\dir\EXT );
/**
 * Load exceptions
 *
 * Add a dependency for the exceptions library.
 * The only problem is that this is bad way since
 * we are now requiring it through the file handler
 * and not the library handler. But its only simple
 * class definitions.
 */
dependency( \Wave\DIR\LOCAL . \Wave\TYPE\LIBRARY . \Wave\DIR\SEP . 'exceptions.' . \Wave\TYPE\LIBRARY . \Wave\DIR\EXT, \Wave\TYPE\FILE );

/**
 * Load interfaces
 *
 * Add a dependency for the interfaces from local.
 * This is a very bad way of doing things becouse
 * we are now requiring it through the file handler
 * and not the library handler. But its only simple
 * class definitions.
 */
dependency( \Wave\dir\LOCAL . \Wave\type\LIBRARY . \Wave\dir\SEP . \Wave\TYPE\INTERFACES . '.' . \Wave\TYPE\LIBRARY . \Wave\DIR\EXT, \Wave\TYPE\FILE );
/**
 * Load basic component handler
 *
 * Add a dependency for the default component
 * handler used by the system.
 */
dependency( \Wave\dir\LOCAL . \Wave\type\COMPONENT . \Wave\dir\SEP . \Wave\type\COMPONENT . '.' . \Wave\type\COMPONENT . \Wave\dir\EXT, \Wave\type\FILE );

/**
 * Provides a base for implementing the
 * Singleton Design Pattern.
 *
 * @package Wave
 * @author Olav Frengstad
 * @version 0.1
 * @abstract
 * @since Framework 0.1
 * @uses SingletonInterface
 */
abstract class SingletonBase implements SingletonInterface
{
    private static $__instance;

    /**
     * Creates singleton instance
     *
     * Creates 1 instance of the class based
     * on the Singleton design pattern. So on
     * the second initiation it will throw
     * FwCoreException error.
     *
     * @access public
     * @final
     * @return void
     * @since Framework 0.1
     */
    final public function __construct()
    {
        if( null !== self::$__instance )
        {
            message( \Wave\debug\ERROR_FATAL, __CLASS__ . '::' . __METHOD__ . '();Singleton only allows for one instance of itself.' );
        } else {
            if( debug( \Wave\debug\CONSTRUCT ) )
            {
                message( \Wave\debug\CONSTRUCT, get_called_class() );
            }
            self::$__instance = $this;
        }
    }

    /**
     * Don't allow cloning
     *
     * Throws a nice error compared to the
     * <b>Fatal error: Call to protected SingletonBase:__clone() from context</b>
     * you would get for setting proteced
     *
     * @access public
     * @final
     * @return void
     * @since Framework 0.1
     */
    final public function __clone() {
        message( \Wave\debug\ERROR_FATAL, __CLASS__ . '::' . __METHOD__ . '(); Singleton only allows for one instance of itself.' );
    }

    /**
     * Fetches the object variable
     * 
     * All classes should have been
     * loaded before since there is
     * no checkup if there is an actual
     * object
     *
     * @access public
     * @return object instance of class
     * @static
     * @final
     */
    final public static function instance() {
        return self::$__instance;
    }
}

/**
 * Write message to the console
 *
 * Used for debugging purpose during development
 * it should never be called when in a production
 * enviroment so usualy you would enclose the call
 *
 * Writes information based on what debug level is
 * set in the config.
 *
 * @example if( debug( \Wave\debug\LOAD ) ) {<br />&nbsp;&nbsp;&nbsp;message( \Wave\debug\LOAD, __FILE__ );<br />}
 * @param <type> $level
 * @param <type> $message
 * @todo write register integration
 */
function message( $level = \Wave\debug\DUMP, $text = '' ) {
    /**
     * List of items for messages
     * @static
     * @uses SplQueue
     */
    static $messages;

    if( $messages === null )
    {
        $messages = new \SplQueue(\SplQueue::IT_MODE_KEEP );
    }

    /**
     * Check the level for matching debug
     * constants
     */
    switch( $level ) {
    case \Wave\debug\ERROR_PANIC:
    case \Wave\debug\ERROR_FATAL:
    case \Wave\debug\ERROR_CRITICAL:
        $exception = 'Exception';
        if( class_exists( '\Wave\CoreException', false ) ) {
            $exception = '\Wave\CoreException';
        }
        message( \Wave\DEBUG\OVERLOAD_CALL, "<b>{$level}</b> {$text}" );
        throw new $exception( $text );
        break;
    case \Wave\debug\LOAD:
        if( is_string( $text ) && strlen( $text ) > 0 )
        {
            $text = extract_file( $text );
        }
        break;
    case \Wave\debug\DUMP:
        $html = <<<EOT
        <table class="list">
            <tr class="head">
                <td class="time">TIME</td>
                <td class="type">TYPE</td>
                <td class="meta">META</td>
                <td class="mesg">MESSAGE</td>
            </tr>
EOT;
        foreach( $messages as $item )
        {   
            $html .= <<<EOT
            <tr>
                <td class="time">{$item[0]}</td>
                <td class="type">{$item[1]}</td>
                <td class="meta">{$item[2]}</td>
                <td class="mesg">{$item[3]}</td>
            </tr>
EOT;
                
        }

        $html .= '<table class="list">';
		$html .= '	<tr class="head">';
        $html .= '		<td>Sys</td>';
        $html .= '		<td>Print</td>';
		$html .= '		<td>Construct</td>';
		$html .= '		<td>Destruct</td>';
		$html .= '		<td>Call</td>';
		$html .= '		<td>Overload</td>';
		$html .= '		<td>Load</td>';
		$html .= '		<td>Notice</td>';
		$html .= '		<td>E_PANIC</td>';
		$html .= '		<td>E_FATAL</td>';
		$html .= '		<td>E_CRITICAL</td>';
		$html .= '		<td>E_ERROR</td>';
		$html .= '		<td>E_WARNING</td>';
		$html .= '		<td>E_NOTICE</td>';
		$html .= '	</tr>';
		$html .= '	<tr class="center">';
        $html .= '		<td>' . \Wave\debug\SYSTEM . '</td>';
        $html .= '		<td>' . \Wave\debug\DUMP . '</td>';
		$html .= '		<td>' . \Wave\debug\CONSTRUCT . '</td>';
		$html .= '		<td>' . \Wave\debug\DESTRUCT . '</td>';
		$html .= '		<td>' . \Wave\debug\CALL . '</td>';
		$html .= '		<td>' . \Wave\debug\OVERLOAD_CALL . '</td>';
		$html .= '		<td>' . \Wave\debug\LOAD . '</td>';
		$html .= '		<td>' . \Wave\debug\NOTICE . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR_PANIC . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR_FATAL . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR_CRITICAL . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR_WARNING . '</td>';
		$html .= '		<td>' . \Wave\debug\ERROR_NOTICE . '</td>';
		$html .= '	</tr>';
		$html .= '</table>';

        $e = new \Exception( $html );
        $e->title = 'Debug information';
        unset( $html );
        include( \Wave\dir\PRESENTATION . \Wave\keyword\LOCAL . \Wave\dir\SEP . 'page.error.php' );
        return;
        break;
    case \Wave\debug\SYSTEM:
        break;
    }

    $trace = debug_backtrace();

    if( \Wave\debug\CONSTRUCT === $level && empty( $text ) ) {
        $text = called_info( $trace, false, false );
    }

    $messages->push( array( 0 => get_microtime(), $level, called_info( $trace, false, true ), $text ) );
    unset( $trace );
    
    return;
}

/**
 * Checks for debug allowance
 *
 * Are we allowed to print out messages
 * on the given $level
 * @param int $level of debug information
 * @return bool true if allowed, false if not
 * @todo use Memento Design Pattern to preserve memory usage with Message calls
 */
function debug( $level = null )
{
    /**
     * Check DEBUG definition
     * 
     * If there is no DEBUG definition
     * or its set to true. Someone haven't
     * done their work properly and debug ALL
     * 
     * @see DEBUG
     */
    if( !defined('\DEBUG') || \Wave\DEBUG === true )
    {
        return true;
    }

    return true;

    /**
     * Check allowance for all
     *
     * If no parameters are defined allow all.
     */
    if( $level === null )
        return ( function_exists('settings') ? settings( 'debug' ) : true );

    if( function_exsits('settings') && is_numeric( $level ) )
    {
        if( settings('debug') > $level )
        {
            return true;
        } else {
            return true;
        }
    }
    /**
     * Return with invalid parameters
     * 
     * Allow all debugging
     */
    return true;
}

/**
 * Formats backtrace into a pattern
 *
 * @param array $backtrace debug_backtrace() data
 * @param bool $method only get class/type/method
 * @param bool $formated return formated data
 * @return string formated string or empty if nothing is done.
 */
function called_info( array & $backtrace, $method = false, $formated = true ) {

    if( !empty( $backtrace ) ) {
        $return = '';
        
        $copy = $backtrace[1];
        unset( $backtrace );

        $class      = ( array_key_exists('class', $copy)    ? $copy['class']                : '' );
        $type       = ( array_key_exists('type', $copy)     ? $copy['type']                 : '' );
        $function   = ( array_key_exists('function', $copy) ? $copy['function']             : 'global' );
        $file       = ( array_key_exists('file', $copy)     ? extract_file($copy['file'])   : 'unknown' );
        $line       = ( array_key_exists('line', $copy)     ? $copy['line']                 : 'unknown' );
        unset( $copy );

        if( $formated )
        {
            return ( (   $method )
                        ? $class . $type . $function
                        : "Called from <b>{$class}{$type}{$function}</b> in <b>{$file}</b> around line {$line}"
            );
        }
    } else {
        $return = '';
    }
}

/**
 * Extracts the file name from path
 *
 * Splits the argument by directory seperator
 * and returns the end of it or if available
 * gets relative path from basepath
 *
 * @param string $file full path
 * @return string file name relativ from BASEPATH
 */
function extract_file( $file = '' ) {
    if( !is_string( $file ) )
    {
        throw new FwWrongValueException( 'extract_file(); Expected first argument to be of type <b>string</b>. Recieved ' . gettype( $file ) );
    }

    /**
     * Fetch path
     *
     * Return the path either relative to basepath
     * or just the last segment of the string.
     *
     */
    if( false !== strpos( $file, \Wave\dir\BASEPATH ) ) {
        $file = str_replace(\Wave\dir\BASEPATH, '', $file);
    } else {
        $seg = explode( \Wave\dir\SEP, $file );
        $file = end( $seg );
        unset( $seg );
    }
    return $file;
}

/**
 * Fetches microtime
 *
 * Fetches microtime without real seconds and unix timestamp
 */
function get_microtime()
{
    //	Create id from microtime exclude seconds and unix datestamp
    $microtime = microtime();
    $parts = explode( ' ',  $microtime );
    unset( $microtime );

    $part = current( $parts );
    unset( $parts );

    $id = explode( '.', $part);
    unset( $part );

    $value = $id[1];
    unset( $id );

    return $value;
}

/**
 * Adds a dependency to current file
 *
 * Tells the core which files it needs to run
 * and then load them
 *
 * Since we have to start with the basic storage
 * possibity before we can start loading packages.
 * We store everything in the static variable until
 * recieving the FW_EVENT_LOAD_REGISTER
 *
 * @param mixed     $dep        array( $package, $dep ) or string $dep to look for
 * @param string    $keyword    what catalog to look for
 * @param bool      $autorun    automaticly load it, or return in
 * @return true                 if loaded false otherwise
 * @staticvar array $dep        temporary register list
 * @todo add support for classes & components
 * @todo better integration with load_dependency
 */
function dependency( $dep, $keyword = '', $autorun = true ) {
    if( debug( \Wave\debug\CALL ) ) {
        message(\Wave\debug\CALL, __FUNCTION__);
    }

    /**
     * Default register catalogs
     */
    static $__register = array(
        \Wave\type\FILE        => array(),
        \Wave\type\COMPONENT   => array(),
        \Wave\type\CLASSES       => array(),
        /**
         * How can we manipulate this variable
         * from the global scope, libraries are
         * automaticly loaded in runtime and not
         * included since they are not a neccesary
         * part of the system
         */
        \Wave\type\LIBRARY     => array(),
    );

    try {
        $register = load_class('\Wave\Core\Register', false);
    } catch( Exception $e ) {
        message( \Wave\DEBUG\ERROR_WARNING, $e->getMessage() );
    }

    if( \Wave\event\UPDATE === $dep ) {
        $register->call( $__register );
        unset( $__register );
        unset( $dep );
        unset( $keyword );
        return;
    }

    /**
     * Check for Register class
     *
     * If non is found, check for local
     * temporary register. Notifies the
     * system that there is no Register
     * available.
     *
     * If the Register Component is not
     * found, it will try to use the local
     * static variable for temp storage.
     */
    if( $register ) {
        
    } elseif( !isset( $__register ) ) {
        message( \Wave\debug\CRITICAL, 'Could not load class <b>Register</b>fC<br />The temp register was removed. No place to store data.' );
    } else {
        /**
         * We will check the local register
         */
        if( array_key_exists( $keyword, $__register ) ) {
            /**
             * Catalog was found, check if its for files
             */
            if( \Wave\type\FILE === $keyword ) {
                $file   = $dep;
               
                $dep    = crc32( $file );

                if( array_key_exists( $dep, $__register[$keyword] ) ) {
                    unset( $keyword );
                    unset( $file );
                    unset( $dep );
                    return true;
                }

                
                if( load_dependency( $file, \Wave\type\FILE ) ) {
                    $__register[$keyword][$dep] = true;
                    unset( $keyword );
                    unset( $file );
                    unset( $dep );
                    
                    return true;
                }
            } elseif( load_component( $keyword ) ) {
                return load_component($keyword)->call($dep);
            } else {
                if (function_exists('load_' . $keyword)) {
                    return call_user_func('load_'.$keyword, $dep);
                } else {
                    message( \Wave\debug\ERROR_FATAL, 'No way to handle dependencies for type: <b>' . $keyword . '</b>' );
                }
            }
        } else {
            message( \Wave\debug\ERROR_NOTICE, 'Tried to lookup non-existing register catalog: <b>' . $keyword . '</b> from local register.' );
        }
    }

    $called = debug_backtrace();

    message( \Wave\debug\ERROR_CRITICAL, 'Missing dependency <em>' . $dep . '</em> from catalog <em>' . $keyword  . '</em>.' );

    unset( $called );
    return false;
}

/**
 * Loads a dependency
 *
 * Loads a type of component
 *
 * @param string $dep dependency to load
 * @param string \Wave\TYPE constant
 * @todo write \Wave\TYPE's to use components
 */
function load_dependency( $dep, $keyword = '' ) {
    if( debug( \Wave\debug\CALL ) )
        message( \Wave\debug\CALL, $dep );

    switch ($keyword) {
    case \Wave\type\FILE:
        $path = $dep;
        if( is_readable( $path ) )
        {
            return load_file( $path );
        } else {
            message( \Wave\debug\ERROR_CRITICAL, 'File <b>' . extract_file( $path ) . '</b> not found.' );
        }
        break;
    case \Wave\type\COMPONENT:
        message( \Wave\debug\ERROR_PANIC, 'No support for <b>component</b> dependency yet.' );
        break;
    case \Wave\type\CLASSES:
        message( \Wave\debug\ERROR_PANIC, 'No support for <b>class</b> dependency yet');
    case \Wave\type\LIBRARY:
        message( \Wave\debug\ERROR_PANIC, 'No support for <b>libraries</b> dependency yet');
        break;
    case \Wave\type\DEFINITION:
        message( \Wave\debug\ERROR_PANIC, 'No support for <b>definition</b> dependency yet');
        break;
    case \Wave\type\INTERFACES:
        message( \Wave\debug\ERROR_PANIC, 'No support for <b>interface</b> dependency yet');
        break;
    default:
        message( \Wave\debug\ERROR_PANIC, 'No support for dependencies using strategic pattern yet.' );
        break;
    }

    return false;
}

function load_file( $file, $run = true ) {
    message(\Wave\debug\CALL, __FUNCTION__ );
    static $__files = \Wave\state\WAITING;

    if( \Wave\state\WAITING === $__files ) {
        $__files = array_map( 'crc32', get_required_files() );
    }

    /**
     * Path check
     *
     * Check if its a relative path
     * convert to absolute path.
     */
    if( !is_absolute($file) )
    {
        /**
         * Add to basepath
         * 
         * For now add to basee path
         *
         * @todo Going on a wild goose chase here
         * but debug_bactrace is memory intensive
         * we should find a better way, but
         * the only way to place the origins
         * of the call is to use a backtrace.
         */
        $file = BASEPATH . $file;
    }

    $hash = crc32( $file );

    if( array_key_exists( $hash, $__files ) ) {
        /**
         * The file has already been included
         */
        message( \Wave\debug\NOTICE, 'file already included ' . $file );
        return true;
    } else {
        if( !is_readable( $file ) ) {
            message( \Wave\debug\ERROR_CRITICAL, 'File ' . extract_file( $file ) . ' was not found or is not readable.' );
        }
        
        if( $run ) {
            require $file;
            $__files[$hash] = true;
             message( \Wave\debug\LOAD, $file );
            return $__files[$hash];
        } else {
            $raw = file_get_contents( $file );
            $__files[$hash] = $raw;
             message( \Wave\debug\LOAD, $file );
            unset( $raw );
            return $raw;
        }
    }
}

/**
 * Loads a class
 * 
 * Works as a singleton for classes,
 * anything parsed by this will only
 * be loaded once.
 * 
 * It looks up the file then creates
 * a new instance of the class and 
 * reference it to the Register.
 *
 * @param string    $class      name of class to load
 * @param bool      $required   kill the script if class not found
 * @param bool      $queue      callback which will be updated
 * @return object               instance of class or false if not found
 * @staticvar array $__classes  local register for classes using spl_object_hash
 * @staticvar array $__keys     local map for class name => spl_object_hash
 * @todo write integration to register and
 * @todo see last paragraph of comments
 */
function load_class( $class, $required = true, $queue = false )
{
    message(\Wave\debug\CALL, __FUNCTION__);
    static $__classes   = array();
    static $__keys      = array();

    if( $class === 'dump' ) {
        foreach( $__keys as $key => $value ) {
            echo "$key => $value<br />";
        }
        return;
    }

    message( \Wave\debug\CALL, 'Looking for <b>' . $class . '</b> instance.' );

    if( !is_string( $class ) ) {
        throw new \Wave\WrongValueTypeException('load_class(); excpects 1st argument to be <em>string</em>. Recieved type <b>' . gettype($class) . '</b>' );
    }

    if( !is_bool( $queue ) && !is_string( $queue ) ) {
        throw new \Wave\WrongValueTypeException('load_class(); excpects 3d argument to be <em>bool false</em> or string. Recieved type <b>' . gettype($queue) . '</b>' );
    }

    /**
     * Check class existance
     *
     * If we don't find the class try to autoload the file.
     */
    try {
        if( !class_exists( $class) )
        {
            spl_autoload( $class );
        }
    } catch( \LogicException $e ) {
       $level = ( $required )
                    ? \Wave\DEBUG\ERROR_CRITICAL
                    : \Wave\DEBUG\ERROR_WARNING;

       \Wave\message( $level, 'Could not load class <b>' . $class . '</b>');
       return false;
    }

//    queue_notify( \Wave\EVENT\UPDATE, $class );

    /**
     * Check for existing instance
     */
    if( array_key_exists( $class, $__keys ) ) {
        message( \Wave\debug\NOTICE, 'Found class <b>' . $class . '</b> in register');
        return $__classes[ $__keys[$class] ];
    }

    $local  = new $class;
    $hash   = spl_object_hash( $local );
    $__keys[ $class ] = $hash;
    message( \Wave\debug\LOAD, 'Class <b>' . $class . '</b> loaded.');
    $__classes[ spl_object_hash($local) ] = $local;
    unset( $class );
    unset( $hash );

    return $local;
}

function load_component( $component, $package = '' ) {

        message(\Wave\debug\CALL, __FUNCTION__ . ' args: ' . $component . ', ' . $package );
        static $__components   = array();

        if( !is_string( $component ) ) {
            throw new \Wave\WrongValueTypeException( 'load_component() excepected 1st argument to be string. Recieved <b>' . gettype( $component ) . '</b>.' );
        }

        if( !is_string( $package ) ) {
            throw new \Wave\WrongValueTypeException( 'load_component() excepected 2nd argument to be string. Recieved <b>' . gettype( $component ) . '</b>.' );
        }
        
        $handler = ucfirst($component).'Handler';
        if( isset($__components[$handler])) {
            return $__components[$handler];
        } elseif( isset($__components[$component]) ) {
            return $__components[$component];
        }

        if( $package !== \Wave\KEYWORD\LOCAL && !empty($package) ) {
            // look into the user dir
            $path = \Wave\DIR\USER . $package . \Wave\DIR\SEP . \Wave\TYPE\COMPONENT . \Wave\DIRSEP;
        } else {
            $path = \Wave\DIR\LOCAL . \Wave\TYPE\COMPONENT . \Wave\DIR\SEP;
        }

        $path = $path . $component . '.' . \Wave\TYPE\COMPONENT . \Wave\DIR\EXT;

        load_file( $path );

        $class = '\Wave\\' . ucfirst($component).'Handler';
        
        if( class_exists($class) ) {
            $local = new $class();
            $__components[$local->name()] = $local;
            return $local;
        } else {
            message( \Wave\DEBUG\ERROR_CRITITCAL, 'Missing component class <b>' . $class .'</b>');
        }

        unset( $class );
}

function exception_error_handler($errno, $errstr = '', $errfile = '', $errline = 0) {
    if( is_object( $errno ) && $errno instanceof Exception ) {
        /**
         * Make copy of exception
         */
        $e = $errno;
        $errstr     = $e->getMessage();
        $errfile    = $e->getFile();
        $errline    = $e->getLine();
        $errno      = $e->getCode();
    }

    message( \Wave\debug\ERROR_NOTICE, "<h1>Uncought Exception</h1>Error #{$errno}: {$errstr} in {$errfile} on line {$errline}.<br />" );
}

function exception_handler( Exception $e ) {
    echo "<div class=\"error\"><h1>Uncought exception</h1>\nError #{$e->getCode()}: {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}</div>";
}


/**
 * Check for associative array
 *
 * Checks if its an associativ array or not.
 * Modified with typehinting for performance.
 *
 * @author JTS
 * @link http://www.php.net/manual/en/function.is-array.php#98305
 */
function is_assoc( array $array = null ) {
	return ( count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array) ) ) ) );
}
/**
 * Checks if its an absolute path or not
 *
 * Checks for certain values in the path
 * to determine if its an absolute path
 *
 * Incredible as it might seem there are
 * more OS'es around than unix based and
 * Microsoft.NonStopKernel,Stratus VOS, OpenVMS,
 * are all full-blowen OS'es used in mission-critical
 * enviroments. Might not be the standard
 * for web-servers running PHP but still
 * needs support
 *
 * @param string $path to check
 * @todo regex for systems not windows/unix-like
 */
function is_absolute( $path ) {

//    $segments = array(
//        '/\//',                                     //  Unix like & MenuetOS
//        '/\/\//',                                   //  Domain/OS
//        '/A-Z+]:\\/i',                           //  Windows & OS/2
//        '/[A-Z+]:\.\\/i',                          //  Windows alternative
//        '\\\\',                                     //  UNC Windows network resources
//        '/(A-Z0-9$]*?):/i',                      //  Amiga, DCL-Based [drive, device, volume or assign name]:
//        '/(A-Z0-9]*?)::(A-Z0-9)\.$\./i',      //  RISC OS: [fs type]::[drive number or disc name].$
//        '/$[/i', //  Stratos VOS: %[system_name]#[module_name]>
//    );

    return ( substr( $path, 0, 1) == '/' || substr( $path, 1, 2) == ':\\' ) ? true : false;
}


class Event {
    private $__handler = 'EventHandler';

    public static function __callStatic( $name, $arguments ) {
        $handler = load_class('EventHandler');

        if( is_callable( array($handler, $name) ) ) {
            return call_user_func( array($handler, $name), $arguments );
        } else {
            throw new FwCoreException('<b>Event</b> class could not load method ' . $handler);
        }
    }
}