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
 * Component for handling libraries
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

class LibraryHandler extends \Wave\ComponentHandler {
    public function call() {
        message( \Wave\DEBUG\CALL, __METHOD__ );

        foreach (func_get_args() as $param => $arg) {
            if( load_component(\Wave\TYPE\FILE)->call( $arg ) ) {
                message( \Wave\DEBUG\LOAD, $arg );
            }
        }
    }
}