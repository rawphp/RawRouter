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
 * @package   RawPHP\RawRouter
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRouter;

use RawPHP\RawRouter\Contract\IAction;

/**
 * This class represents a controllers action as controller/action route.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Action implements IAction
{
    private $_name;
    private $_params;

    /**
     * Action constructor.
     *
     * @param string $name   action name
     * @param array  $params action parameters
     */
    public function __construct( $name, $params = NULL )
    {
        if ( FALSE === strstr( $name, 'Action' ) )
        {
            $name = $name . 'Action';
        }

        $this->_name = str_replace( '.php', '', $name );

        $this->_init( $params );
    }

    /**
     * Initialises the action.
     *
     * @param array $params configuration array
     */
    private function _init( $params )
    {
        if ( NULL !== $params && 0 < count( $params ) )
        {
            foreach ( $params as $key => $value )
            {
                $this->_params[ ] = $value;
            }
        }
    }

    /**
     * Checks if this action has any parameters.
     *
     * @return bool TRUE if parameters exist, else FALSE
     */
    public function hasParams()
    {
        $result = ( 0 < count( $this->_params ) );

        return $result;
    }

    /**
     * Returns the action parameters.
     *
     * @return array the parameters (if any)
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Returns the name of the action.
     *
     * @return string the action name
     */
    public function getName()
    {
        return $this->_name;
    }
}