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

use RawPHP\RawRouter\IController;
use RawPHP\RawBase\Component;
use RawPHP\RawRouter\IAction;
use RawPHP\RawBase\RawException;

/**
 * Base controller class which all other controllers in the application
 * will extend.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
abstract class Controller extends Component implements IController
{
    /**
     * @var IAction
     */
    public $action              = NULL;
    /**
     * @var string
     */
    public $pageView            = '';
    
    /**
     * Initialises the controller and attaches callbacks for the
     * ON_BEFORE_CONTROLLER_RUN_ACTION and ON_AFTER_CONTROLLER_RUN_ACTION
     * actions.
     * 
     * @param array $config configuration array
     */
    public function init( $config = NULL )
    {
        parent::init( $config );
        
        $this->addAction( self::ON_BEFORE_CONTROLLER_RUN_ACTION, array( $this, 'onBeforeAction' ) );
        $this->addAction( self::ON_AFTER_CONTROLLER_RUN_ACTION, array( $this, 'onAfterAction' ) );
    }
    
    /**
     * Sets the controller action.
     * 
     * @param IAction $action the action instance
     * 
     * @filter ON_INIT_ACTION_FILTER(1)
     * 
     * @action ON_INIT_ACTION
     */
    public function setAction( IAction $action )
    {
        $this->action = $this->filter( self::ON_INIT_ACTION_FILTER, $action );
        
        $this->doAction( self::ON_INIT_ACTION_ACTION );
    }
    
    /**
     * Default index action for controller.
     * 
     * Subclasses should ALWAYS override this method.
     */
    public abstract function indexAction( );
    
    /**
     * Runs the controller action.
     * 
     * @action ON_BEFORE_CONTROLLER_RUN_ACTION
     * @action ON_AFTER_CONTROLLER_RUN_ACTION
     */
    public function run( )
    {
        $this->doAction( self::ON_BEFORE_CONTROLLER_RUN_ACTION );
        
        $action = $this->action->getName( );
        
        if ( $this->action->hasParams( ) )
        {
            call_user_func_array( array(
                $this,
                $action
            ), 
            $this->action->getParams() );
        }
        else
        {
            $this->$action( );
        }
        
        $this->doAction( self::ON_AFTER_CONTROLLER_RUN_ACTION );
    }
    
    /**
     * Callback called before the controller run method executes.
     * 
     * @action ON_BEFORE_ACTION_VIEW
     */
    public function onBeforeAction( )
    {
        $this->pageView = '';
        
        $this->doAction( self::ON_BEFORE_ACTION_VIEW_ACTION );
    }
    
    /**
     * Callback called after the controller run method executes.
     * 
     * This callback action is a good time to cleanup after the
     * controller.
     * 
     * @action ON_AFTER_ACTION_VIEW
     */
    public function onAfterAction( )
    {
        $view = $this->pageView;
        
        echo $view;
        
        $this->doAction( self::ON_AFTER_ACTION_VIEW_ACTION );
    }
    
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
    public function loadView( $data = array( ), $return = FALSE )
    {
        $retVal = NULL;
        $extraData = $this->filter( self::ON_LOAD_EXTRA_DATA_FILTER, array() );
        
        $extra = array(
            'route' => $this->_getRoute( ),
        );
        
        extract( $extra, EXTR_OVERWRITE );
        extract( $extraData, EXTR_OVERWRITE );
        extract( $data, EXTR_OVERWRITE );
        
        $level = error_reporting( );
        error_reporting( 0 );
        
        ob_start( );
        
        if ( isset( $view ) )
        {
            $viewDir = $this->filter( self::ON_GET_VIEWS_DIR_FILTER, NULL, $data, $return );
            
            if ( NULL === $viewDir )
            {
                throw new RawException( 'The views directory has not been set.' );
            }
            
            if ( FALSE !== strstr( $view, '.php' ) )
            {
                include $viewDir . $view;
            }
            else
            {
                include $viewDir . $view . '.php';
            }
            
            $newView = ob_get_clean( );
            
            if ( $return )
            {
                $retVal = $newView;
            }
            else
            {
                $this->pageView .= $newView;
            }
        }
        else
        {
            $retVal = '';
        }
        
        error_reporting( $level );
        
        return $this->filter( self::ON_LOAD_VIEW_FILTER, $retVal, $data, $return );
    }
    
    /**
     * Helper method to work out the current route.
     * 
     * @return string the route
     */
    private function _getRoute( )
    {
        $action = str_replace( 'Action', '', $this->action->getName( ) );
        $action = explode( '\\', $action );
        $action = strtolower( $action[ 0 ] );
        
        $cls = str_replace( 'Controller', '', get_class( $this ) );
        $cls = strtolower( $cls );
        
        $path = explode( '\\', $cls );
        $path = $path[ count( $path ) - 1 ] . '/' . $action;
        
        if ( $this->action->hasParams( ) )
        {
            foreach( $this->action->getParams( ) as $param )
            {
                $path .= '/' . $param;
            }
        }
        
        return $path;
    }
    
    /**
     * Redirects the browser to new location.
     * 
     * @param string $url the redirection url
     * 
     * @action ON_BEFORE_REDIRECT_ACTION
     * 
     * @filter ON_REDIRECT_LOCATION_FILTER(1)
     */
    public function redirect( $url )
    {
        $this->doAction( self::ON_BEFORE_REDIRECT_ACTION );
        
        $location = $this->filter( self::ON_REDIRECT_LOCATION_FILTER, $url );
        
        header( 'Location: ' . $location );
        exit();
    }
    
    // actions
    const ON_INIT_ACTION_ACTION             = 'on_init_action';
    const ON_BEFORE_CONTROLLER_RUN_ACTION   = 'on_before_controller_run_action';
    const ON_AFTER_CONTROLLER_RUN_ACTION    = 'on_after_controller_run_action';
    const ON_BEFORE_ACTION_VIEW_ACTION      = 'on_before_action_view';
    const ON_AFTER_ACTION_VIEW_ACTION       = 'on_after_action_view';
    const ON_BEFORE_REDIRECT_ACTION         = 'on_before_redirect_action';
    
    // filters
    const ON_INIT_ACTION_FILTER             = 'on_init_action_filter';
    const ON_REDIRECT_LOCATION_FILTER       = 'on_redirect_filter';
    const ON_GET_VIEWS_DIR_FILTER           = 'on_get_views_dir_filter';
    const ON_LOAD_EXTRA_DATA_FILTER         = 'on_load_extra_data_filter';
    const ON_LOAD_VIEW_FILTER               = 'on_load_view_filter';
}