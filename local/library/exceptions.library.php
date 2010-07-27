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
 * Generic exceptions
 *
 * System uses exceptions to a large extend
 * to handle errors. These are the core
 * exceptions for the base components.
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

/**
 * Generic exception for the framework
 * @package Wave
 */
class Exception extends \Exception
{
	/**
	 * Holds all exceptions thrown.
	 * @var <array>
	 * @access protected
	 */
	static protected $_exceptions = array();
	/**
	 * The title of the page or block
	 * being displayed.
	 * @access protected
	 * @var <string>
	 */
	public	$title;

	/**
	 * The exception message
	 * @var <string>
	 * @access protected
	 */
	protected $message;

	/**
	 * The string represenations as generated during construction
	 * @var <string>
	 * @access protected
	 */
	private $string;

	/** The code passed to the constructor
	 * @var <string>
	 * @access protected
	 */
	protected $code;

	/**
	 * The file name where the exception was instantiated
	 * @var <string>
	 * @access protected
	 */
	protected $file;

	/**
	 * The line number where the exception was instantiated
	 * @var <string>
	 * @access protected
	 */
	protected $line;

	/**
	 * The stack trace
	 * @var string
	 * @access protected
	 */
	private $trace;

	/**
	 * Sets title of the exception and
	 * runs init func
     * @access public
	 * @return void
	 */
	public function __construct( )
	{
		if( \Wave\DEBUG === true )
		{
			debug( \Wave\DEBUG\CONSTRUCT );
		}

        $type = substr( get_called_class(), 2, -9 );

		$this->setTitle( 'Framework <em>' . ( $type ? $type : 'generic' ) . '</em> exception' );

		$args = func_get_args();
		call_user_func_array( 'parent::__construct', $args );
		unset( $args );
		return;
	}

	/**
	 * Constructs an exception
	 * @access public
	 * @param $message	<string> Some text describing the exception
	 * @param $code    	<mixed> Some code describing the exception
	 */
	final protected function init( $message, $code = -1 )
	{
		if( DEBUG === true )
		{
			debug( \Wave\DEBUG\CALL );
		}

		//	Add to the growing list of exceptions
		//	And add compatability with default Exceptions
		if (func_num_args()) {
			$this->message		= $message;
			self::$_exceptions[] = $message;
		}

		$this->code = $code;
		$this->file = __FILE__; // of throw clause
		$this->line = __LINE__; // of throw clause
		$this->trace = debug_backtrace();
		$this->string = (string) $this;
	}

	/**
	 * Gets the previous exception, no support of this in SPL before
	 * version 5.3 so until that's the standard version we will still include
	 * it here.
	 * @access public
	 */


	/**
	 * Sets the title of an exception
	 * @access protected
	 * @param <string> $title
	 * @return <void>
	 */
	final protected function setTitle( $title )
	{
		$this->title = $title;
		return;
	}
}

class CoreException             extends \Wave\Exception { }

class ComponentException        extends \Wave\Exception { }

class PackageException          extends \Wave\Exception { }

class RegisterException         extends \Wave\Exception { }

class FwMissingValueException   extends \Wave\Exception { }

class FwWrongValueTypeException extends \Wave\Exception { }