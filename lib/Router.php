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

namespace RawPHP\RawRouter;

use RawPHP\RawRouter\IRouter;
use RawPHP\RawRouter\Action;
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
    public $defaultController   = 'home';
    public $defaultAction       = 'index';
    public $namespace           = '';
    
    /**
     * Initialises the router.
     * 
     * @param array $config configuration array
     * 
     * @action ON_BEFORE_ROUTER_INIT
     * @action ON_AFTER_ROUTER_INIT
     */
    public function init( $config = NULL )
    {
        parent::init( $config );
        
        $this->doAction( self::ON_BEFORE_ROUTER_INIT );
        
        if ( NULL !== $config  && is_array( $config ) )
        {
            foreach( $config as $key => $value )
            {
                switch( $key )
                {
                    case 'default_controller':
                        $this->defaultController = $value;
                        break;
                    
                    case 'default_action':
                        $this->defaultAction = $value;
                        break;
                    
                    case 'namespace':
                        $this->namespace = $value;
                        break;
                    
                    default:
                        // do nothing
                        break;
                }
            }
        }
        
        $this->doAction( self::ON_AFTER_ROUTER_INIT );
    }
    
    /**
     * Creates a controller and its associated action.
     * 
     * @param string $route  the controller/action string
     *                       format [controllerName/actionName]
     * 
     * @param array  $params list of parameters
     * 
     * @filter ON_CREATE_CONTROLLER_FILTER
     * 
     * @return Controller instance of a controller
     */
    public function createController( $route, $params )
    {
        $control = NULL;
        $route = ltrim( $route, '/' );
        $route = rtrim( $route, '/' );
        
        $vars = explode( '/', $route );
        
        if ( 1 === count( $vars ) && '' == $vars[ 0 ] )
        {
            $control = $this->_buildControllerName( $this->defaultController . 'Controller' );
            
            // build action
            $aName = $this->_buildActionName( $control, $this->defaultAction );
            
            $control = $this->namespace . $control;
        }
        else if ( 1 === count( $vars ) )
        {
            // controller /
            
            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $control, $this->defaultAction );
            
            $control = $this->namespace . $control;
        }
        else if ( 2 === count( $vars ) )
        {
            // controller / action
            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $control, $vars[ 0 ] );
            
            $control = $this->namespace . $control;
        }
        else if ( 2 < count( $vars ) )
        {
            // controller / action / params
            $control = $this->_buildControllerName( $vars[ 0 ] . 'Controller' );
            
            array_shift( $vars );
            
            // build action
            $aName = $this->_buildActionName( $control, $vars[ 0 ] );
            
            array_shift( $vars );
            
            $control = $this->namespace . $control;
        }
        else
        {
            $control = $this->_buildControllerName( $this->defaultController . 'Controller' );
        
            $aName = $this->defaultAction . 'Action';
        }
        
        $action = new Action( $aName, $params );
        
        $controller = new $control( );
        $controller->init( $action );
        
        return $this->filter( self::ON_CREATE_CONTROLLER_FILTER, $controller, $route, $params );
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
    
    const ON_BEFORE_ROUTER_INIT         = 'on_before_router_init';
    const ON_AFTER_ROUTER_INIT          = 'on_after_router_init';
    
    const ON_CREATE_CONTROLLER_FILTER   = 'on_create_controller_filter';
}
