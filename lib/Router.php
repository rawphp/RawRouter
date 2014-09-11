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
 * PHP version 5.4
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawRouter;

use RawRouter\IRouter;
use RawRouter\Action;
use RawPHP\RawBase\Component;

/**
 * The Routing class.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Router extends Component implements IRouter
{
    public $defaultController   = NULL;
    public $defaultAction       = NULL;
    public $namespace           = NULL;
    
    /**
     * Router Constructor.
     * 
     * @param string $controller default controller name
     * @param string $action     default action name
     * @param string $namespace  namespace (if used)
     */
    public function __construct( $controller = 'home', $action = 'index', $namespace = '' )
    {
        parent::__construct( );
        
        $this->defaultController = $controller;
        $this->defaultAction     = $action;
        $this->namespace         = $namespace;
    }
    
    /**
     * Creates a controller and its associated action.
     * 
     * @param string $route  the controller/action string
     * @param array  $params list of parameters
     * 
     * @return RawController instance of a controller
     */
    public function createController( $route, $params )
    {
        $controller = NULL;
        $route = ltrim( $route, '/' );
        $route = rtrim( $route, '/' );
        
        $vars = explode( '/', $route );
        
        if ( 1 === count( $vars ) && '' == $vars[ 0 ] )
        {
            $controller = $this->_buildControllerName( $this->defaultController . 'Controller' );
            
            // build action
            $aName = $this->_buildActionName( $controller, $this->defaultAction );
            
            $action = new Action( $aName, $params );
            
            $controller = $this->namespace . $controller;
            
            $controller = new $controller( $action );
        }
        else if ( 1 === count( $vars ) )
        {
            // controller /
            
            $controller = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $controller, $this->defaultAction );
            $action = new Action( $aName, $params );
            
            $controller = $this->namespace . $controller;
            
            $controller = new $controller( $action );
        }
        else if ( 2 === count( $vars ) )
        {
            // controller / action
            $controller = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $controller, $vars[ 0 ] );
            $action = new Action( $aName, $params );
            
            $controller = $this->namespace . $controller;
            
            $controller = new $controller( $action );
        }
        else if ( 2 < count( $vars ) )
        {
            // controller / action / params
            $controller = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $controller, $vars[ 0 ] );
            
            array_shift( $vars );
            
            $action = new Action( $aName, $params );
            
            $controller = $this->namespace . $controller;
            
            $controller = new $controller( $action );
        }
        else
        {
            $controller = $this->_buildControllerName( $this->defaultController . 'Controller' );
        
            $action = new Action( 
                    'action' 
                    . strtoupper( $this->defaultAction[ 0 ] ) 
                    . substr( $this->defaultAction, 1 ) 
            );
            
            $controller = new $controller( $action );
        }
        
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
        
        if ( !class_exists( $this->namespace . $controller ) )
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
        
        if ( !method_exists( $this->namespace . $controllerName, $actionName ) )
        {
            $actionName = $this->defaultAction . 'Action';
        }
        
        return $actionName;
    }
}
