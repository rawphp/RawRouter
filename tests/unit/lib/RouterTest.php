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

use RawPHP\RawRouter\Router;

/**
 * The Router tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    protected $router;
    
    /**
     * @var array
     */
    protected static $config;
    
    /**
     * Test setup.
     */
    protected function setUp()
    {
        global $config;
        
        $this->router = new Router( );
        $this->router->init( $config );
    }
    
    /**
     * Test cleanup.
     */
    protected function tearDown()
    {
        $this->router = NULL;
    }
    
    /**
     * Test creating a default controller and action.
     */
    public function testCreateDefaultControllerAndAction()
    {
        $route = '';
        $params = array();
        
        $controller = $this->router->createController( $route, $params );
        
        $this->_testCreateController( $controller );
    }
    
    /**
     * Test creating a home controller with default action.
     */
    public function testCreateHomeControllerWithDefaultAction( )
    {
        $route = 'home';
        $params = array();
        
        $controller = $this->router->createController( $route, $params );
        
        $this->_testCreateController( $controller );
    }
    
    /**
     * Test creating a home controller with about action.
     */
    public function testCreateHomeControllerWithAboutAction( )
    {
        $route = 'home/about';
        $params = array();
        
        $controller = $this->router->createController( $route, $params );
        
        $this->_testCreateController( $controller, 'aboutAction' );
    }
    
    /**
     * Test creating a controller with invalid action.
     */
    public function testCreateControllerWithInvalidAction( )
    {
        $route = 'home/non_existent_action';
        $params = array( 'id' => 1 );
        
        $controller = $this->router->createController( $route, $params );
        
        $this->_testCreateController( $controller, 'indexAction' );
    }
    
    /**
     * Test creating a controller with page id parameter
     */
    public function testCreateControllerWithPageId( )
    {
        $route = 'home/pages';
        $params = array( 'id' => 1 );
        
        $controller = $this->router->createController( $route, $params );
        
        $this->_testCreateController( $controller, 'pagesAction' );
        
        $this->assertTrue( $controller->action->hasParams( ) );
        
        $setParams = $controller->action->getParams( );
        
        $this->assertEquals( 1, $setParams[ 0 ] );
    }
    
    /**
     * Helper method to test create controller.
     * 
     * @param RawController $controller the controller to test
     * @param string        $action     the expected action
     */
    private function _testCreateController( $controller, $action = 'indexAction' )
    {
        $this->assertNotNull( $controller );
        $this->assertInstanceOf( $this->router->namespace . 'HomeController', $controller );
        $this->assertNotNull( $controller->action );
        $this->assertEquals( $action, $controller->action->getName() );
    }
}
