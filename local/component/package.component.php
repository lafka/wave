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
 * Component for handling packages
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

class PackageHandler extends ComponentBase {
    private $__packages = array();

    public function call() {
        message( \Wave\debug\CALL, __METHOD__ );

        $args = func_get_args();

        if( empty($args) ) {
            throw new \Wave\MissingValueException( 'No packages supplied.' );
        }

        $size = sizeof( $args );
        for ($i = 0; $i < $size; $i++) {
            $this->load( $args[$i] );
        }
    }

    public function load( $package ) {
        message( \Wave\debug\CALL, __METHOD__ );

        if( !is_readable( \Wave\dir\USER . $package ) ) {
            throw new \Wave\ComponentException( 'Could not locate the package <b>' . $package . '</b>. It should be located under <em>' . extract_file( \Wave\dir\USER ) . '</em>.');
        } else {
            if( is_readable( \Wave\dir\USER . $package . \Wave\dir\SEP . 'local' . \Wave\dir\EXT) ) {
                $path = \Wave\dir\USER . $package . \Wave\dir\SEP . 'local' . \Wave\dir\EXT;
            } elseif( is_readable( \Wave\dir\USER . $package . \Wave\dir\SEP . $package . \Wave\dir\EXT)) {
                $path = \Wave\dir\USER . $package . \Wave\dir\SEP . $package . \Wave\dir\EXT;
            } else {
                throw new PackageException( 'Missing local package file from <b>'.$package.'</b> package.');
            }

            if( \Wave\load_file( $path ) ) {
                $this->__packages[$package] = true;
            }
        }
    }
}