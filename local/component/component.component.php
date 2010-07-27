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
 * Default handler for components
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
/**
 * Default component functionality
 *
 * Provides the basic for loading and
 * calling components
 *
 * @package Wave
 * @author Olav Frengstad
 * @version 0.1
 * @abstract
 * @since Framework 0.1
 * @uses CallableInterface
 */
abstract class ComponentBase  implements \Wave\CallableInterface, \Wave\ComponentInterface {
    private $__name         = 'ComponentHandler';
    private $__version      = 0.1;
    private $__desc         = 'Container for default components';
    private $__handler      = array();

    public function __construct( $name = null, $handler = null ) {
        if( debug( \Wave\debug\CONSTRUCT) )
            message( \Wave\debug\CONSTRUCT );

        if( !is_string($name) && null !== $name ) {
            throw new \Wave\WrongValueTypeException('Excpects 1st argument to be of type string. Recieved <b>' . gettype($name) . '</b>.');
        }

        if( !is_callable($handler) && null !== $handler ) {
            throw new \Wave\WrongValueTypeException('Excpects 2nd argument to valid callback function or <b>null</b>    ');
        }

        $this->__name       = ( null === $name      ? get_called_class()        : $name );
        $this->__handler    = ( null === $handler   ? array( $this, 'call' )    : $handler );
    }

    public function name() {
        return $this->__name;
    }

    public function version() {
        return $this->__version;
    }

    public function desc() {
        return $this->__desc;
    }
}

class ComponentHandler extends \Wave\ComponentBase {

    public function call() {
        message( \Wave\DEBUG\CALL, __METHOD__ );
        
        $args = func_get_args();

        if( !empty( $args ) ) {
            foreach( $args as $value ) {
                return load_component( $value );
            }
        } else {
            throw new \Wave\MissingValueException( __METHOD__ . ' needs atleast 1 component name to work ');
        }
    }
}