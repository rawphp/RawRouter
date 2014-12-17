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
use RawPHP\RawDispatcher\Dispatcher;
use RawPHP\RawRouter\Contract\IController;
use RawPHP\RawRouter\Contract\IRouter;
use RawPHP\RawRouter\Router;

/**
 * The Router tests.
 *
 * @category  PHP
 * @package   RawPHP\RawRouter\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class RouterTest extends PHPUnit_Framework_TestCase
{
    /** @var  IRouter */
    protected $router;
    /** @var  IDispatcher */
    protected $dispatcher;
    /** @var  array */
    protected static $config;

    /**
     * Test setup.
     */
    protected function setUp()
    {
        global $config;

        $this->dispatcher = new Dispatcher();

        $this->router = new Router();
        $this->router->init( $config );
        $this->router->setDispatcher( $this->dispatcher );
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
        $route  = '';
        $params = [ ];

        /** @var IController $controller */
        $controller = $this->router->createController( $route, $params );
        $this->_testCreateController( $controller );
    }

    /**
     * Test creating a home controller with default action.
     */
    public function testCreateHomeControllerWithDefaultAction()
    {
        $route  = 'home';
        $params = [ ];

        $controller = $this->router->createController( $route, $params );

        $this->_testCreateController( $controller );
    }

    /**
     * Test creating a home controller with about action.
     */
    public function testCreateHomeControllerWithAboutAction()
    {
        $route  = 'home/about';
        $params = [ ];

        $controller = $this->router->createController( $route, $params );

        $this->_testCreateController( $controller, 'aboutAction' );
    }

    /**
     * Test creating a controller with invalid action.
     */
    public function testCreateControllerWithInvalidAction()
    {
        $route  = 'home/non_existent_action';
        $params = [ 'id' => 1 ];

        $controller = $this->router->createController( $route, $params );

        $this->_testCreateController( $controller, 'indexAction' );
    }

    /**
     * Test creating a controller with page id parameter
     */
    public function testCreateControllerWithPageId()
    {
        $route  = 'home/pages';
        $params = [ 'id' => 1 ];

        $controller = $this->router->createController( $route, $params );

        $this->_testCreateController( $controller, 'pagesAction' );

        $this->assertTrue( $controller->getAction()->hasParams() );

        $setParams = $controller->getAction()->getParams();

        $this->assertEquals( 1, $setParams[ 0 ] );
    }

    /**
     * Test creating a users controller with default action.
     */
    public function testCreateUsersControllerWithDefaultAction()
    {
        $route = 'users';

        $controller = $this->router->createController( $route, [ ] );

        $this->assertNotNull( $controller );
        $this->assertInstanceOf( 'RawPHP\RawRouter\Support\Controller\UsersController', $controller );
        $this->assertNotNull( $controller->getAction() );
    }

    /**
     * Helper method to test create controller.
     *
     * @param IController $controller the controller to test
     * @param string      $action     the expected action
     */
    private function _testCreateController( IController $controller, $action = 'indexAction' )
    {
        $this->assertNotNull( $controller );
        $this->assertInstanceOf( 'RawPHP\RawRouter\Support\Controller\HomeController', $controller );
        $this->assertNotNull( $controller->getAction() );
        $this->assertEquals( $action, $controller->getAction()->getName() );
    }
}