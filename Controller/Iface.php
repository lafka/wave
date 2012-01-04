<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2011, 2010 - 2011 Frengstad Web Teknologi and contributors
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the Wave PHP Team nor the names of its contributors may be used
 * to endorse or promote products derived from this software without specific prior
 * written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS AS IS AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Basics for dispatching requests 
 *
 * @package     wave 
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi 
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt\Controller;

use  \Fwt\Base;

interface Iface
{
    /**
     * Flag for current view
     * @var int
     */
    const USE_CURRENT_VIEW = 0;

    /**
     * Initializes a controller
     *
     * @param array $uri The dispatched uri
     * @param \Fwt\Base $base The core kernel object
     * @return \Fwt\Controller\Iface
     */
	public function __construct ( array $uri, Base $base );

	/**
	 * Get the current view
	 *
	 * @return string the current view
	 */
	public function currentView ();

	/**
	 * Load the view
	 *
	 * @param string $view the view to load
	 * @since 0.1
	 */
	public function loadView ( $view );

	/**
	 * Checks if a view is available
	 *
	 * @param string $view the view to load
	 * @return boolean true if view is found, false otherwise
	 */
	public function hasView ( $view );
	
	/**
	 * Initialize the controller to make sure we are abel to output the page
	 * 
	 * @return boolean True if all went well, false otherwise
	 */
	public function init ();
}
