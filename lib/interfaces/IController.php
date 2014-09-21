<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.3
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRouter;

/**
 * The base controller interface.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IController
{
    /**
     * Sets the controller action.
     * 
     * @param IAction $action the action instance
     * 
     * @filter ON_INIT_ACTION_FILTER(1)
     * 
     * @action ON_INIT_ACTION
     */
    public function setAction( IAction $action );
    
    /**
     * Runs the controller action.
     * 
     * @action ON_BEFORE_CONTROLLER_RUN_ACTION
     * @action ON_AFTER_CONTROLLER_RUN_ACTION
     */
    public function run( );
    
    /**
     * Loads a view file.
     * 
     * This method requires Controller::ON_GET_VIEWS_DIR_FILTER to have
     * a valid callback assigned to the path of the view files.
     * 
     * @param array $data   data to make available to the views as variables
     * @param bool  $return whether to return the view to the calling method
     * 
     * @fitler ON_GET_VIEWS_DIR_FILTER(3)
     * @filter ON_LOAD_VIEW_FILTER(3)
     * 
     * @return mixed view html string on success, FALSE on error
     * 
     * @throws RawException if there is something wrong.
     */
    public function loadView( $data = array( ), $return = FALSE );
    
    /**
     * Redirects the browser to new location.
     * 
     * @param string $url the redirection url
     * 
     * @action ON_BEFORE_REDIRECT_ACTION
     * 
     * @filter ON_REDIRECT_LOCATION_FILTER
     */
    public function redirect( $url );
}