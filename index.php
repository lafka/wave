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
 * Sets basic definition and autoloads system
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

    /**
     * Basic benchmarking from CodeIgniter project
     *
     * Loads the benchmark class.
     */
    include( 'benchmark.php' );

    $benchmark = new \CI\Benchmark();
    $benchmark->mark('start');

    /**
     * Set the error level and memory limit
     *
     * By default all error settings should be turned off in a production
     * enviroment. We will lower it or turn it off competely when we
     * exit the core throught the 'settings' extension.
     *
     * We aim at a low footprint, so no 128mb limit for the core.
     */
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', true);
    ini_set('memory_limit', "1M");

    CONST DEBUG = true;
}

namespace Wave\keyword {
    /**
     * Define keyword for scope lookup
     * !!!!WARNING!!!! May break system !!!!WARNING!!!!
     *
     * When switching packages, there is
     * always a file to initiate things.
     */
    CONST LOCAL     = 'local';
    /**
     * Define keyword for scope lookup
     * !!!!WARNING!!!! May break system !!!!WARNING!!!!
     *
     * When switching packages, there is
     * always a file to initiate things.
     */
    CONST CORE = 'core';
}

namespace Wave\dir {
    /**
     * Default directory seperator
     *
     * Seperator for directoires. Unix-like
     * systems (Linux, Unix, Mac and similar )
     * takes the forward slash as seperator.
     * But windows system also takes the
     * backward slash as a file path delimeter
     * by default.
     */
    CONST SEP       = DIRECTORY_SEPARATOR;
    /**
     * Default extension for files
     *
     * Take the extension from this file and sets it to use for rest of the system.
     * @todo Find better solution for using constants with the 'const' keyword
     */
    define('EXTENSION', '.' . pathinfo(__FILE__, \PATHINFO_EXTENSION ) );
    const EXT = EXTENSION;

    /**
     * Absolute path to top level directory
     *
     * The absolute path to the frameworks
     * top level directory.
     * @todo Find better solution for using constants with the 'const' keyword
     */
    define( 'BASEPATH', str_replace( "", '/', realpath( dirname( __FILE__ ) ) . \Wave\dir\SEP ) );
    const BASEPATH = BASEPATH;
    /**
     * Absolute path to user directory
     *
     * Absolute path to the directories containing core system
     * files usualy / %BASEPATH% /CORE/
     */
    define( 'USER', \Wave\dir\BASEPATH . 'usr' . \Wave\dir\SEP);
    const USER      = USER;

    /**
     * Absolute path to Core directory
     *
     * Absolute path to the directories containing core system
     * files usualy / %BASEPATH% /local/
     */
    define('LOCAL', \Wave\dir\BASEPATH . \Wave\keyword\LOCAL . \Wave\dir\SEP );
    CONST LOCAL  = LOCAL;

    /**
     * Absolute path to presentation dir
     *
     * Absolute path to the dir containing presentation for system
     */
    define('PRESENTATION', \Wave\dir\BASEPATH . 'Presentation' . \Wave\dir\SEP);
    const PRESENTATION = PRESENTATION;
}

namespace Wave\type {
    /**
     * An object of type which is the base for extending any part of the system
     */
    CONST COMPONENT     = 'component';
    /**
     * A keyword for working with file type
     *
     * The type keyword for files, used
     * by Register, Components and other
     * thing to make sure it's the same
     * type of object we are working with.
     */
    CONST FILE          = 'file';
    /**
     * A keyword for configuration values
     *
     * The type keyword for config, used
     * to make sure it's the same type
     * of object we are working with.
     */
    CONST CONFIG        = 'config';
    /**
     * A keyword for classes
     *
     * The type keyword for config, used
     * to make sure it's the same type
     * of object we are working with.
     */
    CONST CLASSES       = 'class';
    /**
     * A keyword for interfaces
     *
     * The type keyword for interfaces
     */
    CONST INTERFACES    = 'interface';
    /**
     * A keyword for interfaces
     *
     * The type keyword for interfaces
     */
    CONST DEFINITION    = 'definition';
    /**
     * Keyword for libraries
     */
    CONST LIBRARY       = 'library';
    /**
     * @see \Wave\TYPE\LIBRARY
     */
    CONST LIB           = \Wave\type\LIBRARY;
}

 namespace Wave {
    /**
     * All system actions should be tried
     * so we don't get a "Uncaught Exception"
     * error.
     */
    try {
        /**
         * Initiate the local package
         *
         * Dirty way as we don't know of any handlers yet
         */
        require \Wave\dir\LOCAL . \Wave\keyword\LOCAL      . \Wave\dir\EXT;

        /**
         * Set error handler
         *
         * Sets error handler to use internal
         * function for handling errors.
         */
        set_error_handler('\Wave\exception_error_handler');
        /**
         * Set exception handling
         */
        set_exception_handler("\Wave\exception_handler");

        /**
         * Set the include path
         *
         * Allow search in CORE_DIR, COR_DIR / LIB-DIR
         * CORE_DIR / COMPONENT_DIR and in BASEPATH
         */
        set_include_path(
                \Wave\dir\LOCAL . PATH_SEPARATOR .
                \Wave\dir\LOCAL . \Wave\type\LIBRARY . PATH_SEPARATOR .
                \Wave\dir\LOCAL . \Wave\type\COMPONENT . PATH_SEPARATOR .
                \Wave\dir\BASEPATH . PATH_SEPARATOR .
                get_include_path()
        );

        /**
         * Extensions to use for autoloader
         *
         * The extensions to use for loading the
         * files to the system. Only allow files
         * with extension similar to {EXT} to be
         * used for autoloading.
         */
        $extensions = array(
            '.class'        . \Wave\dir\EXT,
            '.defintion'    . \Wave\dir\EXT,
            '.library'      . \Wave\dir\EXT,
            '.component'    . \Wave\dir\EXT,
            \Wave\dir\EXT,
        );

        spl_autoload_extensions(implode(',', $extensions));
        /**
         * Always be nice to memory
         */
        unset( $extensions );

        /**
         * Use default autoloader function
         *
         * The default autoloader is written
         * in C and will outperform any user
         * written implementation anytime.
         */
        spl_autoload_register();

        /**
         * Load the component handlers
         *
         * First we load the default handler for
         * component itself, then we load component
         * for librarys and packages, automaticly
         * chainging the package call to load up
         * the core pacakge
         */
        $component = \Wave\load_component( \Wave\type\COMPONENT, \Wave\keyword\LOCAL);
        $component->call('library');
        $component->call('package')->call(\Wave\keyword\CORE);


        /**
         * Extend the core
         *
         * Right now we only have the basic
         * of the ore done, loaded some files
         * ( interface, exceptions, common, definitions )
         * and basic components are supported.
         *
         * Now lets load the core package which
         * will extend the core and add starts
         * the actualy process.
         */
        //\Wave\load_package( FW_KEYWORD_CORE );

        \Wave\message(\Wave\debug\DUMP);
    } catch ( Exception $e ) {
        if( is_readable( \Wave\dir\PRESENTATION . \Wave\keyword\LOCAL . \Wave\dir\SEP . 'page.error.php' ) ) {
                include( \Wave\dir\PRESENTATION . \Wave\keyword\LOCAL . \Wave\dir\SEP . 'page.error.php' );
        } else {
            echo '<h1>An error occured</h1>';
            echo '<p>'.$e->getMessage().'</p>';
            echo '<p>'.$e->getTrace().'</p>';
            echo '<p><h3>Take note:</h3>Could not load system error file from: ' . \Wave\dir\PRESENTATION . \Wave\keyword\LOCAL . \Wave\dir\SEP . 'page.error.php' . '</p>';
        }
        \Wave\message();

    }

    \Wave\Core\Register::instance()->call();
    $benchmark->mark('end');
    echo "<p><b>Total execution time:</b> {$benchmark->elapsed_time( 'start', 'end' )}</p>";
}