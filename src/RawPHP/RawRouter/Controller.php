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
use RawPHP\RawDispatcher\Contract\IEvent;
use RawPHP\RawDispatcher\Contract\IListener;
use RawPHP\RawRouter\Contract\IAction;
use RawPHP\RawRouter\Contract\IController;
use RawPHP\RawRouter\Event\AfterControllerRunEvent;
use RawPHP\RawRouter\Event\BeforeControllerRunEvent;
use RawPHP\RawRouter\Event\Events;
use RawPHP\RawRouter\Event\GetViewsDirEvent;
use RawPHP\RawSupport\Exception\RawException;

/**
 * Base controller class which all other controllers in the application
 * will extend.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
abstract class Controller implements IController, IListener
{
    /** @var  IAction */
    protected $action = NULL;
    /** @var  string */
    protected $pageView = '';
    /** @var  IDispatcher */
    protected $dispatcher;

    /**
     * Initialises the controller and attach default listeners.
     */
    public function addDefaultListeners()
    {
        $this->dispatcher->addListener( Events::EVENT_BEFORE_CONTROLLER_RUN, $this );
        $this->dispatcher->addListener( Events::EVENT_AFTER_CONTROLLER_RUN, $this );
    }

    /**
     * Default index action for controller.
     *
     * Subclasses should ALWAYS override this method.
     */
    public abstract function indexAction();

    /**
     * Runs the controller action.
     */
    public function run()
    {
        $event = new BeforeControllerRunEvent();

        $this->dispatcher->fire( Events::EVENT_BEFORE_CONTROLLER_RUN, $event );

        $action = $this->action->getName();

        if ( $this->action->hasParams() )
        {
            call_user_func_array( [
                                      $this,
                                      $action
                                  ],
                                  $this->action->getParams()
            );
        }
        else
        {
            $this->$action();
        }

        $event = new AfterControllerRunEvent();

        $this->dispatcher->fire( Events::EVENT_AFTER_CONTROLLER_RUN, $event );
    }

    /**
     * Callback called before the controller run method executes.
     */
    public function onBeforeAction()
    {
        $this->pageView = '';
    }

    /**
     * Callback called after the controller run method executes.
     *
     * This callback action is a good time to cleanup after the
     * controller.
     */
    public function onAfterAction()
    {
        $view = $this->pageView;

        echo $view;
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
     * @return mixed view html string on success, FALSE on error
     *
     * @throws RawException if there is something wrong.
     */
    public function loadView( $data = [ ], $return = FALSE )
    {
        $retVal = NULL;
        //$extraData = $this->filter( self::ON_LOAD_EXTRA_DATA_FILTER, [ ] );

        $extra = [
            'route' => $this->_getRoute(),
        ];

        extract( $extra, EXTR_OVERWRITE );
        //extract( $extraData, EXTR_OVERWRITE );
        extract( $data, EXTR_OVERWRITE );

        $level = error_reporting();
        error_reporting( 0 );

        ob_start();

        if ( isset( $view ) )
        {
            $viewsDirEvent = new GetViewsDirEvent();

            $this->dispatcher->fire( Events::EVENT_GET_VIEWS_DIR, $viewsDirEvent );

            $viewDir = $viewsDirEvent->getViewsDir();

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

            $newView = ob_get_clean();

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

        return $retVal;
    }

    /**
     * Helper method to work out the current route.
     *
     * @return string the route
     */
    private function _getRoute()
    {
        $action = str_replace( 'Action', '', $this->action->getName() );
        $action = explode( '\\', $action );
        $action = strtolower( $action[ 0 ] );

        $cls = str_replace( 'Controller', '', get_class( $this ) );
        $cls = strtolower( $cls );

        $path = explode( '\\', $cls );
        $path = $path[ count( $path ) - 1 ] . '/' . $action;

        if ( $this->action->hasParams() )
        {
            foreach ( $this->action->getParams() as $param )
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
     */
    public function redirect( $url )
    {
        $location = $url;

        header( 'Location: ' . $location );
        exit();
    }

    /**
     * Handle an event.
     *
     * @param IEvent      $event
     * @param string      $name
     * @param IDispatcher $dispatcher
     */
    public function handle( IEvent $event, $name, IDispatcher $dispatcher )
    {
        if ( $event instanceof BeforeControllerRunEvent )
        {
            return $this->onBeforeAction();
        }
        elseif ( $event instanceof AfterControllerRunEvent )
        {
            return $this->onAfterAction();
        }
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
     * Set the dispatcher.
     *
     * @param IDispatcher $dispatcher
     */
    public function setDispatcher( IDispatcher $dispatcher )
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the controller action.
     *
     * @return IAction
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the controller action.
     *
     * @param IAction $action
     */
    public function setAction( IAction $action )
    {
        $this->action = $action;
    }

    /**
     * Get the page view.
     *
     * @return string
     */
    public function getPageView()
    {
        return $this->pageView;
    }

    /**
     * Set the page view.
     *
     * @param string $pageView
     */
    public function setPageView( $pageView )
    {
        $this->pageView = $pageView;
    }
}