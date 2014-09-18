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
 * @package   RawPHP/RawRouter/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawRouter\Tests;

use RawPHP\RawRouter\Controller;
use RawPHP\RawRouter\Action;

/**
 * The controllers tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
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
        
        $this->controller = new HomeController( );
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
    public function testInstantiatingController( )
    {
        $this->assertNotNull( $this->controller );
    }
    
    /**
     * Test loading a view using the HomeController and the default
     * indexAction.
     */
    public function testLoadingIndexView( )
    {
        $this->controller->addFilter( Controller::ON_GET_VIEWS_DIR_FILTER, 
                array( $this, 'getViewDirCallback' ) );
        
        $this->controller->addAction( Controller::ON_AFTER_ACTION_VIEW_ACTION, 
                array( $this, 'afterActionViewCallback' ) );
        
        $this->controller->run( );
    }
    
    /**
     * Helper method to pass the views directory to the controller.
     * 
     * @param string $dir the directory that is always NULL
     * 
     * @return string the path to the views directory
     */
    public function getViewDirCallback( $dir )
    {
        $dir = VIEWS_DIR;
        
        return $dir;
    }
    
    /**
     * Helper method callback when the view has been loaded by the
     * controller action.
     */
    public function afterActionViewCallback( )
    {
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<title>Welcome to RawPHP - RawPHP</title>' ) );
        
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<h1>Welcome to RawPHP</h1>' ) );
        
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<h4>You will absolutely love RawPHP!!!</h4>' ) );
    }
}