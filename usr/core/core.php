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
 *
 * A interface for loading up a series of components from external data and providing
 * a register for them which can be accessed for information
 *
 * @package     Wave
 * @subpackage  core
 * @version     0.1
 * @copyright   2010 Olav Frengstad
 * @author      Olav Frengstad
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since       Wave PHP Framework 0.1
 * @todo        phpDoc Comments
 */

/**
 * The super class for communication betwean the core
 * elements it uses ideas from Mediator/Facade/Decorator
 * design patterns along with some other twists to make
 * everything outside of the core work with the core.
 * Also implements the Singleton design pattern to make
 * sure we only have 1 core instance so we don't start
 * to fool around with one instance while half the system
 * uses another.
 *
 * The whole class would idealy be referenced through-out
 * the system ( consider the Object interface ) in the
 * self::__ref to work as a reference to the singleton
 * in Core::__instance
 *
 *
 * @package Wave
 * @subpackage core
 * @author Olav Frengstad
 * @version 0.1
 * @final
 * @uses Singleton
 */
namespace Wave\core;

if ( !defined('\Wave\DIR\BASEPATH') )
    return null;

const PACKAGE   = 'Core';
const DIR       = __DIR__;
const VERSION   = 0.1;
const DESC      = 'Contains the core implementation for API calls throughout the system.';


/**
 * Dirty dependency loading.
 *
 * Since we actualy havent bootstrapped
 * the core system yet. we can't use the
 * default methods for accessing things.
 */

/**
 * Load exceptions
 *
 * Add a dependency for the exceptions.class.php
 * in the Core package
 */
\Wave\dependency( \Wave\DIR\LOCAL . \Wave\TYPE\LIBRARY . \Wave\DIR\SEP . 'exceptions.' . \Wave\TYPE\LIBRARY . \Wave\DIR\EXT, \Wave\TYPE\FILE );

/**
 * Load interfaces
 *
 * Add a dependency for the core.interfaces.php
 * in the Core package
 */
\Wave\dependency( \Wave\DIR\LOCAL . \Wave\TYPE\LIBRARY . \Wave\DIR\SEP . \Wave\TYPE\INTERFACES . '.' . \Wave\TYPE\LIBRARY . \Wave\DIR\EXT, \Wave\TYPE\FILE );

/**
 * Load Register
 *
 * Adds a dependency for requireing the register
 * library
 */
\Wave\dependency( __DIR__ . \Wave\DIR\SEP .  \Wave\TYPE\LIBRARY . \Wave\DIR\SEP . 'register.' . \Wave\TYPE\LIBRARY . \Wave\DIR\EXT, \Wave\TYPE\LIBRARY );


/**
 * The super class for communication betwean the core
 * elements it uses ideas from Mediator/Facade/Decorator
 * design patterns along with some other twists to make
 * everything outside of the core work with the core.
 * Also implements the Singleton design pattern to make
 * sure we only have 1 Core instance so we don't start
 * to fool around with one instance while half the system
 * uses another.
 *
 * The whole class would idealy be referenced through-out
 * the system ( consider the Object interface ) in the
 * self::__ref to work as a reference to the singleton
 * in Core::__instance
 *
 *
 * @package Wave
 * @subpackage core
 * @author Olav Frengstad
 * @version 0.1
 * @final
 * @since Framework 0.1
 * @uses SingletonBase
 */
class Core extends \Wave\SingletonBase {
    protected static $__instance;
    /**
     * Loads something based on component.
     */
    public static function load() {
        
    }

    /**
     * Fetches or sets config value
     */
    public static function config() {

    }

    /**
     * Gets a component
     */
    public static function get() {

    }

    /**
     * Removes a component
     *
     * Removes all reference to a component.
     * This is not always possible since a
     * file that has been included it will
     * not be subjected for removal since its
     * already in the runtime.
     *
     * But removing objects from the pool or
     * component han handlers will work fine.
     * @access public
     * @static
     * @final
     *
     *
     */
    final public static function remove( $item, $keyword = null ) {
        
    }

    /**
     * Searches for a component
     *
     * Looks up the register for a key,
     * requires also a keyword.
     *
     * @static
     * @final
     * @access public
     * @return value of lookup or null if not found
     */
    final public static function lookup() {
        
    }
}