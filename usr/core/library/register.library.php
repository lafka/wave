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
 * The register, holding component object pools,
 * files loaded, library versions and config value.
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
namespace Wave\core;

if ( !defined('\Wave\DIR\BASEPATH') )
    return null;

class RegisterItem implements \Wave\CompositeItem {
    private $__name;
    private $__value;

    public function __construct( $name, $value = null, $options = null ) {
        if (debug( \Wave\debug\CONSTRUCT ))
            message( \Wave\debug\CONSTRUCT, $name );

        if (!is_string( $name )) {
            throw new \Wave\RegisterException( 'RegisterItem::__construct(), first argument must be of non-empty string. Recieved type ' . gettype( $name ) );
        }

        if (is_null( $value ) ) {
            throw new \Wave\RegisterException( 'RegisterItem::__construct(), second argument cannot be null.' );
        }


        $this->__name   = $name;
        $this->__value  = $value;

        /**
         * Check for register options keyword
         *
         * Using debug_backtrace is a long and
         * memory intensiv proccess. Providing
         * a __options keyword is not so bad.
         */
        if( null !== $options && is_array( $options )
            && array_key_exists('__option', $options ) ) {
            
            $size = sizeof( $options );
            $keys = array_keys( $options );
            for ($i = 0; $i < $size; $i++ ) {
                echo $keys[$i] . "<br />";
            }
        }
    }

     public function name() {
        if ( debug( \Wave\debug\CALL ) )
            message( \Wave\debug\CALL );

        return $this->__name;
    }

    public function call() {
        echo "#{$this->__name}: " . print_r( $this->__value, true ) . "<br />\r\n";
        return $this->__value;
    }
}

class RegisterCatalog extends RegisterItem implements \Wave\CompositeGroup, \Wave\ComponentInterface {
    /**
     * Name of catalog
     *
     * Holds the name set by the catalog.
     * @var <type>
     */
    protected $__name;
    /**
     * All CompositeItems associated
     * 
     * List of all the objects that
     * this CompositeGroup has
     * 
     * @var array child objects
     * @todo SplStack or SplHeap better solution, they  have a C-domain implementation of Iterator.
     */
    protected $__value;
    /**
     * Position of iterator
     * 
     * Holds the current position when iterating
     * through the catalog.
     * 
     * @access private
     * @var int current iterator position
     */
    private $__position;

    protected $__version = 0.1;
    protected $__desc;

    public function __construct( $name, array $children = null ) {
        if ( \Wave\debug( \Wave\debug\CONSTRUCT ))
           \Wave\ message( \Wave\debug\CONSTRUCT, $name );

        if (!is_string( $name )) {
            throw new \Wave\RegisterException( 'RegisterItem::__construct(), first argument must be of non-empty string. Recieved type ' . gettype( $name ) );
        }

        $this->__name       = $name;
        $this->__value      = $children;
    }

    public function __set( $name, $value ) {
        if( debug( \Wave\debug\OVERLOAD_CALL ) )
            message( \Wave\debug\OVERLOAD_CALL );
        
        $this->__value[ $name ] = new RegisterItem( $name, $value );
    }

     public function __get( $name ) {
        if( is_array( $this->__value ) && array_key_exists( $name, $this->__value ) )
        {
            return $this->__value[ $name ];
        }

        return '';
    }

    //
    // })) Implementation of CompositeGroup
    // (({
    //

    /**
     * Adds a CompositeItem to the group
     * 
     * @param CompositeItem $child 
     */
     public function add( \Wave\CompositeItem $item ) {
        if ( \Wave\debug( \Wave\debug\CALL ) )
            \Wave\message( \Wave\debug\CALL );

        if ( \Wave\debug( \Wave\debug\NOTICE ) )
        \Wave\message( \Wave\debug\NOTICE, 'Adding object ' . $item->name() . ' to ' . ( $this->name() !== null ? $this->name() : 'Base' ) );

        $this->__value[ $item->name() ] = $item;
    }

    public function remove( \Wave\CompositeItem $item ) {
        if ( debug( \Wave\debug\CALL ) )
            message( \Wave\debug\CALL );

        unset( $this->__value[ $item->name() ] );
    }

    public function children() {
        if ( \Wave\debug( \Wave\debug\CALL ) )
            \Wave\message( \Wave\debug\CALL );

        return $this->__value;
    }

    public function call() {
        if ( \Wave\debug( \Wave\debug\CALL ) )
            \Wave\message( \Wave\debug\CALL );

        foreach( $this->__value as $item )  {
            echo $item->name() . ' -> ';
            $item->call();
            echo "<br />\r\n";
        }
    }

    //
    // })) Implementation of Iterator
    // (({
    //
//
//    public function current() {
//        if ( debug( \Wave\debug\CALL ) )
//            message( \Wave\debug\CALL );
//        return $this->__value[ $this->__position ]->call();
//    }
//
//    public function key() {
//        if ( debug( \Wave\debug\CALL ) )
//            message( \Wave\debug\CALL );
//
//        return $this->__value[ $this->__position ]->name();
//    }
//
//    public function next() {
//        if ( debug( \Wave\debug\CALL ) )
//            message( \Wave\debug\CALL );
//
//        $this->__position++;
//    }
//
//    public function valid() {
//        if ( debug( \Wave\debug\CALL ) )
//            message( \Wave\debug\CALL );
//
//        return ( array_key_exists( $this->__position, $this->__value ) ? $this->__value[ $this->__position ] : false );
//    }
//
//    public function rewind() {
//        $this->__position = 0;
//    }

    //
    // })) Implementation of ComponentInterace
    // (({
    //
    public function name() {
        return $this->__name;
    }

    public function version() {
        return $this->__version;
    }

    public function desc() {
        if ( debug( \Wave\debug\CALL ) )
            message( \Wave\debug\CALL );

        return $this->__desc;
    }
}

/**
 * The register, holding component object pools,
 * files loaded, library versions and config value.
 *
 * @package Wave
 * @subpackage core
 * @author Olav Frengstad
 * @version 0.1
 * @final
 * @uses SingletonInterface, RegisterCatalog
 * @see RegisterCatalog
 */
class Register extends RegisterCatalog implements \Wave\SingletonInterface {
   private static $__instance;
   protected $__value;

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
     *  @since Framework 0.1
     */
    final public function __construct()
    {
        if( null !== self::$__instance )
        {
            throw new \Wave\CoreException ( __CLASS__ . '::' . __METHOD__ . '();Singleton only allows for one instance of itself.' );
        } else {
            if( \Wave\debug( \Wave\debug\CONSTRUCT ) )
            {
                \Wave\message( \Wave\debug\CONSTRUCT, get_called_class() );
            }
            self::$__instance = $this;
        }
        $this->__value = array();
        \Wave\message( \Wave\debug\NOTICE, 'Creating default register catalogs.' );
        $this->add( new RegisterCatalog('file') );
        $this->add( new RegisterCatalog('class') );
        $this->add( new RegisterCatalog('component') );

        return;
    }

    public static function instance() {
        return self::$__instance;
    }

    /**
     * Don't allow cloning
     *
     * Throws a nice error compared to the
     * <b>Fatal error: Call to protected SingletonBase:__clone() from context ''</b>
     * you would get for setting proteced
     *
     *
     */
    public function notify_event ($event, array $subscribers = null) {
        
    }

    /**
     * Don't allow cloning
     *
     * Throws a nice error compared to the
     * <b>Fatal error: Call to protected SingletonBase:__clone() from context ''</b>
     * you would get for setting proteced
     *
     *
     */
    final public function __clone() {
        throw new \Wave\CoreException( __CLASS__ . '::' . __METHOD__ . '(); Singleton only allows for one instance of itself.' );
    }
}

$register = new \Wave\core\Register();