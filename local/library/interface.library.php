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
 * Interfaces for system
 *
 * These interfaces are commenly used
 * to force singularity when using design
 * patterns like Observer, Singleton and
 * Composite
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
    return;
/**
 * Interfaces used by the system
 */
interface SingletonInterface
{
	/**
     * Fetches instance
     *
	 * Returns the object of the singleton instance
	 * if necessary it creates a new one.
	 * @access public
	 * @static
	 * @return <object>
	 */
	public static function instance();

    /**
     * Disable cloning
     *
     * Don't allow for the clone keyword to
     * create its own instance of the class.
     */
  //  public function __clone();
	/**
	 * Since this is limited to PHP >= 5.3
	 * and we will make this work for other
	 * versions since its just a prototype.
	 */
//	public static function __callStatic();
}

interface CallableInterface {
    public function call();
}

/**
 * Standard format for components
 *
 * Provides a standard way for polymorphing Components
 * as we will at some point support more advanced ways
 * of working with Components when they develop
 * further. 
 */
interface ComponentInterface extends CallableInterface
{
	//public function set_handler( $handler );

    /**
	 * Returns the name of the object
	 */
	public function name();
	/**
	 * Returns the version of the ojbect
	 */
	public function version();
	/**
	 * Returns the description of the object
	 */
	public function desc();
}

/**
 * Composite Design Pattern
 *
 * Provides a common way to implement the
 * Composite Design Pattern.
 *
 * This interface provides common way to
 * perform a action for a an CompositeItem
 *
 * @package Wave
 * @author Olav Frengstad
 * @version 0.1
 * @since Framework 0.1
 * @see Composite
 */
interface CompositeItem extends CallableInterface {
    
}

/**
 * Composite Design Pattern
 *
 * Provides a common way to implement the
 * Composite Design Pattern.
 *
 * This interface provides common way to
 * attach, remove and fetch children.
 * Inherits the command() methd from
 * CompositeItem
 *
 * @package Wave
 * @author Olav Frengstad
 * @version 0.1
 * @since Framework 0.1
 * @uses CompositItem, Iterator
 * @see Composite
 */
interface CompositeGroup extends CompositeItem {
    public function add( CompositeItem $item );
    public function remove( CompositeItem $item );
    public function children();
}