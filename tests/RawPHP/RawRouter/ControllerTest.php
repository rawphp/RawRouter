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
 * @package   RawPHP\RawRouter\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRouter\Tests;

use PHPUnit_Framework_TestCase;
use RawPHP\RawDispatcher\Contract\IDispatcher;
use RawPHP\RawDispatcher\Contract\IEvent;
use RawPHP\RawDispatcher\Contract\IListener;
use RawPHP\RawDispatcher\Dispatcher;
use RawPHP\RawRouter\Action;
use RawPHP\RawRouter\Controller;
use RawPHP\RawRouter\Event\AfterControllerRunEvent;
use RawPHP\RawRouter\Event\Events;
use RawPHP\RawRouter\Event\GetViewsDirEvent;
use RawPHP\RawRouter\Support\Controller\HomeController;

/**
 * The controllers tests.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ControllerTest extends PHPUnit_Framework_TestCase implements IListener
{
    /**
     * @var Controller
     */
    public $controller;

    /**
     * Setup before each test.
     */
    protected function setUp()
    {
        $action = new Action( 'indexAction' );

        $this->controller = new HomeController();
        $this->controller->setDispatcher( new Dispatcher() );
        $this->controller->addDefaultListeners();
        $this->controller->setAction( $action );
    }

    /**
     * Cleanup after each test.
     */
    protected function tearDown()
    {
        $this->controller = NULL;
    }

    /**
     * Test controller instantiated correctly.
     */
    public function testInstantiatingController()
    {
        $this->assertNotNull( $this->controller );
    }

    /**
     * Test loading a view using the HomeController and the default
     * indexAction.
     */
    public function testLoadingIndexView()
    {
        $this->controller->getDispatcher()->addListener( Events::EVENT_GET_VIEWS_DIR, $this );

        $this->controller->getDispatcher()->addListener( Events::EVENT_AFTER_CONTROLLER_RUN, $this );

        $this->controller->run();
    }

    /**
     * Helper method to pass the views directory to the controller.
     *
     * @param GetViewsDirEvent $event
     *
     * @return string the path to the views directory
     *
     */
    public function getViewDirCallback( GetViewsDirEvent $event )
    {
        $event->setViewsDir( VIEWS_DIR );
    }

    /**
     * Helper method callback when the view has been loaded by the
     * controller action.
     */
    public function afterActionViewCallback()
    {
        $this->assertNotFalse( strstr(
                                   $this->controller->getPageView(),
                                   '<title>Welcome to RawPHP - RawPHP</title>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->getPageView(),
                                   '<h1>Welcome to RawPHP</h1>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->getPageView(),
                                   '<h4>You will absolutely love RawPHP!!!</h4>'
                               )
        );
    }

    /**
     * @param IEvent      $event
     * @param string      $name
     * @param IDispatcher $dispatcher
     */
    public function handle( IEvent $event, $name, IDispatcher $dispatcher )
    {
        if ( $event instanceof GetViewsDirEvent )
        {
            $this->getViewDirCallback( $event );
        }
        elseif ( $event instanceof AfterControllerRunEvent )
        {
            $this->afterActionViewCallback();
        }
    }
}