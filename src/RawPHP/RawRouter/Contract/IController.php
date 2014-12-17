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
 * @package   RawPHP\RawRouter\Contract
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRouter\Contract;

use RawPHP\RawDispatcher\Contract\IDispatcher;
use RawPHP\RawSupport\Exception\RawException;

/**
 * The base controller interface.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter\Contract
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IController
{
    /**
     * Initialises the controller and attach default listeners.
     */
    public function addDefaultListeners();

    /**
     * Sets the controller action.
     *
     * @param IAction $action the action instance
     */
    public function setAction( IAction $action );

    /**
     * Get the controller action.
     *
     * @return IAction
     */
    public function getAction();

    /**
     * Runs the controller action.
     */
    public function run();

    /**
     * Loads a view file.
     *
     * This method requires Controller::ON_GET_VIEWS_DIR_FILTER to have
     * a valid callback assigned to the path of the view files.
     *
     * @param array $data   data to make available to the views as variables
     * @param bool  $return whether to return the view to the calling method
     *
     * @return mixed view html string on success, FALSE on error
     *
     * @throws RawException if there is something wrong.
     */
    public function loadView( $data = [ ], $return = FALSE );

    /**
     * Redirects the browser to new location.
     *
     * @param string $url the redirection url
     */
    public function redirect( $url );

    /**
     * Get the event dispatcher.
     *
     * @return IDispatcher
     */
    public function getDispatcher();

    /**
     * Set the event dispatcher.
     *
     * @param IDispatcher $dispatcher
     */
    public function setDispatcher( IDispatcher $dispatcher );
}