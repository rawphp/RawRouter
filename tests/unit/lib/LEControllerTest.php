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

use RawPHP\RawRouter\LEController;
use RawPHP\RawRouter\Action;

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
class LEControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Controller
     */
    public $controller;
    
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
        global $config;
        
        $action = new Action( 'indexAction' );
        
        $controller = new HomeController( );
        $controller->init( $config );
        $controller->setAction( $action );
        
        $this->assertNotNull( $controller );
    }
    
    /**
     * Test loading english home page.
     */
    public function testLoadingEnglishHomePage( )
    {
        global $config;
        
        $action = new Action( 'english' );
        
        $this->controller = new LangController( );
        $this->controller->init( $config );
        $this->controller->setAction( $action );
        
        $this->setControllerFilters( );
        
        $this->controller->addAction( LEController::ON_AFTER_ACTION_VIEW_ACTION, 
                array( $this, 'englishIndexViewCallback' ) );
        
        $this->controller->run( );
    }
    
    /**
     * Helper callback for testing the loaded ENGLISH view.
     */
    public function englishIndexViewCallback( )
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
    
    /**
     * Test loading french home page.
     */
    public function testLoadingFrenchHomePage( )
    {
        $action = new Action( 'french' );
        
        $this->controller = new LangController( );
        $this->controller->setAction( $action );
        
        $this->setControllerFilters( );
        
        $this->controller->addAction( LEController::ON_AFTER_ACTION_VIEW_ACTION, 
                array( $this, 'frenchIndexViewCallback' ) );
        
        $this->controller->run( );
    }
    
    /**
     * Helper callback for testing the loaded FRENCH view.
     */
    public function frenchIndexViewCallback( )
    {
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<title>Bienvenue à RawPHP - RawPHP</title>' ) );
        
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<h1>Bienvenue à RawPHP</h1>' ) );
        
        $this->assertNotFalse( strstr( 
                $this->controller->pageView, 
                '<h4>Vous serez absolument aimer RawPHP!!!</h4>' ) );
    }
    
    /**
     * Sets the controller filter callbacks for 
     * 
     * - ON_GET_LANG_DIR_FILTER
     * 
     * - ON_GET_DEFAULT_LANG_FILTER
     */
    public function setControllerFilters( )
    {
        $this->controller->addFilter( LEController::ON_GET_VIEWS_DIR_FILTER, 
                array( $this, 'getViewDirCallback' ) );
        
        $this->controller->addFilter( LEController::ON_GET_LANG_DIR_FILTER, 
                array( $this, 'getLanguageDirectory' ) );
        
        $this->controller->addFilter( LEController::ON_GET_DEFAULT_LANG_FILTER, 
                array( $this, 'getDefaultLanguage' ) );
    }
    
    /**
     * Callback for get language directory filter.
     * 
     * @param string $langDir the language directory which is always NULL
     * 
     * @return string the language directory path
     */
    public function getLanguageDirectory( $langDir )
    {
        $langDir = LANGUAGE_DIR;
        
        echo PHP_EOL . 'Loading Language Directory' . PHP_EOL;
        
        return $langDir;
    }
    
    /**
     * Callback for get default language filter.
     * 
     * @param string $defaultLang the language which is always NULL
     * 
     * @return string the default language
     */
    public function getDefaultLanguage( $defaultLang )
    {
        $defaultLang = 'en_US';
        
        echo PHP_EOL . 'Loading Default Language' . PHP_EOL;
        
        return $defaultLang;
    }
    
    /**
     * Helper method to pass the views directory to the controller.
     * 
     * @param string $viewDir the directory that is always NULL
     * 
     * @return string the path to the views directory
     */
    public function getViewDirCallback( $viewDir )
    {
        $viewDir = VIEWS_DIR;
        
        echo PHP_EOL . 'Loading View Directory' . PHP_EOL;
        
        return $viewDir;
    }
}