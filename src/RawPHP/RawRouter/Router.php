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

use RawPHP\RawDispatcher\Contract\IDispatcher;
use RawPHP\RawRouter\Contract\IController;
use RawPHP\RawRouter\Contract\IRouter;

/**
 * The Routing class.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Router implements IRouter
{
    public $defaultController = 'home';
    public $defaultAction = 'index';
    /** @var  array */
    protected $namespaces = [ ];
    /** @var  array */
    protected $config;
    /** @var  IDispatcher */
    protected $dispatcher;

    /**
     * Create new router.
     *
     * @param array $config
     */
    public function __construct( array $config = [ ] )
    {
        $this->config = $config;

        $this->init( $config );
    }

    /**
     * Initialises the router.
     *
     * @param array $config configuration array
     */
    public function init( array $config = [ ] )
    {
        foreach ( $config as $key => $value )
        {
            switch ( $key )
            {
                case 'default_controller':
                    $this->defaultController = $value;
                    break;

                case 'default_action':
                    $this->defaultAction = $value;
                    break;

                case 'namespaces':
                    $this->namespaces = $value;
                    break;

                default:
                    // do nothing
                    break;
            }
        }
    }

    /**
     * Creates a controller and its associated action.
     *
     * @param string $route  the controller/action string
     *                       format [controllerName/actionName]
     * @param array  $params list of parameters
     *
     * @return Controller instance of a controller
     */
    public function createController( $route, $params )
    {
        $control = NULL;
        $route   = ltrim( $route, '/' );
        $route   = rtrim( $route, '/' );

        $vars = explode( '/', $route );

        if ( 1 === count( $vars ) && '' == $vars[ 0 ] )
        {
            $control = $this->_buildControllerName( $this->defaultController . 'Controller' );

            // build action
            $aName = $this->_buildActionName( $control, $this->defaultAction );
        }
        else if ( 1 === count( $vars ) )
        {
            // controller /

            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );

            array_shift( $vars );

            // build action
            $aName = $this->_buildActionName( $control, $this->defaultAction );
        }
        else if ( 2 === count( $vars ) )
        {
            // controller / action
            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );

            array_shift( $vars );

            // build action
            $aName = $this->_buildActionName( $control, $vars[ 0 ] );
        }
        else if ( 2 < count( $vars ) )
        {
            // controller / action / params
            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );

            array_shift( $vars );

            // build action
            $aName = $this->_buildActionName( $control, $vars[ 0 ] );

            array_shift( $vars );
        }
        else
        {
            $control = $this->_buildControllerName( $this->defaultController . 'Controller' );

            $aName = $this->defaultAction . 'Action';
        }

        $action = new Action( $aName, $params );

        /** @var IController $controller */
        $controller = new $control( $this->config );
        $controller->setAction( $action );
        $controller->setDispatcher( $this->dispatcher );
        $controller->addDefaultListeners();

        return $controller;
    }

    /**
     * Constructs the controller name.
     *
     * @param string $name the controller name
     *
     * @return string the name
     */
    private function _buildControllerName( $name )
    {
        $name = strtoupper( $name[ 0 ] ) . substr( $name, 1 );

        $controller = $name;

        $namespace = NULL;

        foreach ( $this->namespaces as $ns )
        {
            if ( class_exists( $ns . $controller ) )
            {
                $namespace  = $ns;
                $controller = $ns . $controller;

                break;
            }
        }

        if ( NULL === $namespace )
        {
            return strtoupper( $this->defaultController[ 0 ] )
            . substr( $this->defaultController, 1 ) . 'Controller';
        }
        else
        {
            return $controller;
        }
    }

    /**
     * Builds the action name.
     *
     * @param string $controllerName the controller name
     * @param string $actionName     the action name
     *
     * @return string the action name
     */
    private function _buildActionName( $controllerName, $actionName )
    {
        $actionName = $actionName . 'Action';

        if ( !method_exists( $controllerName, $actionName ) )
        {
            $actionName = $this->defaultAction . 'Action';
        }

        return $actionName;
    }

    /**
     * Set the event dispatcher.
     *
     * @param IDispatcher $dispatcher
     */
    public function setDispatcher( IDispatcher $dispatcher )
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the event dispatcher.
     *
     * @return IDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Get the controller namespace.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Set the controller namespace.
     *
     * @param array $namespaces
     */
    public function setNamespaces( array $namespaces )
    {
        $this->namespaces = $namespaces;
    }
}