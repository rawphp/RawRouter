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
use RawPHP\RawRouter\Contract\IController;
use RawPHP\RawRouter\Event\Events;
use RawPHP\RawRouter\Event\GetDefaultLanguageEvent;
use RawPHP\RawRouter\Event\GetLanguageDirEvent;
use RawPHP\RawRouter\Event\GetViewsDirEvent;
use RawPHP\RawRouter\Support\Controller\HomeController;
use RawPHP\RawRouter\Support\Controller\LangController;

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
class LEControllerTest extends PHPUnit_Framework_TestCase implements IListener
{
    /**
     * @var IController
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
    public function testInstantiatingController()
    {
        global $config;

        $action = new Action( 'indexAction' );

        $controller = new HomeController();
        $controller->setDispatcher( new Dispatcher() );
        $controller->addDefaultListeners( $config );
        $controller->setAction( $action );

        $this->assertNotNull( $controller );
    }

    /**
     * Test loading english home page.
     */
    public function testLoadingEnglishHomePage()
    {
        global $config;

        $action = new Action( 'english' );

        $this->controller = new LangController();
        $this->controller->setDispatcher( new Dispatcher() );
        $this->controller->addDefaultListeners( $config );
        $this->controller->setAction( $action );

        $this->setControllerEvents();

        $this->controller->getDispatcher()->addListener( Events::EVENT_AFTER_CONTROLLER_RUN, $this );

        $this->controller->run();
    }

    /**
     * Helper callback for testing the loaded ENGLISH view.
     */
    public function englishIndexViewCallback()
    {
        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<title>Welcome to RawPHP - RawPHP</title>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<h1>Welcome to RawPHP</h1>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<h4>You will absolutely love RawPHP!!!</h4>'
                               )
        );
    }

    /**
     * Test loading french home page.
     */
    public function testLoadingFrenchHomePage()
    {
        $action = new Action( 'french' );

        $this->controller = new LangController();
        $this->controller->setDispatcher( new Dispatcher() );
        $this->controller->setAction( $action );

        $this->setControllerEvents();

        $this->controller->run();
    }

    /**
     * Helper callback for testing the loaded FRENCH view.
     */
    public function frenchIndexViewCallback()
    {
        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<title>Bienvenue à RawPHP - RawPHP</title>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<h1>Bienvenue à RawPHP</h1>'
                               )
        );

        $this->assertNotFalse( strstr(
                                   $this->controller->pageView,
                                   '<h4>Vous serez absolument aimer RawPHP!!!</h4>'
                               )
        );
    }

    /**
     * Sets the controller filter callbacks for
     *
     * - ON_GET_LANG_DIR_FILTER
     *
     * - ON_GET_DEFAULT_LANG_FILTER
     */
    public function setControllerEvents()
    {
        $this->controller->getDispatcher()->addListener( Events::EVENT_GET_VIEWS_DIR, $this );
        $this->controller->getDispatcher()->addListener( Events::EVENT_GET_LANG_DIR, $this );
        $this->controller->getDispatcher()->addListener( Events::EVENT_GET_DEFAULT_LANG, $this );
    }

    /**
     * Callback for get language directory filter.
     *
     * @param GetLanguageDirEvent $event
     *
     * @return string the language directory path
     */
    public function getLanguageDirectory( GetLanguageDirEvent $event )
    {
        $event->setLangDir( LANGUAGE_DIR );

        echo PHP_EOL . 'Loading Language Directory' . PHP_EOL;
    }

    /**
     * Callback for get default language filter.
     *
     * @param GetDefaultLanguageEvent $event
     *
     * @return string the default language
     */
    public function getDefaultLanguage( GetDefaultLanguageEvent $event )
    {
        $event->setDefaultLanguage( 'en_US' );

        echo PHP_EOL . 'Loading Default Language' . PHP_EOL;
    }

    /**
     * Helper method to pass the views directory to the controller.
     *
     * @param GetViewsDirEvent $event
     *
     * @return string the path to the views directory
     */
    public function getViewDirCallback( GetViewsDirEvent $event )
    {
        $event->setViewsDir( VIEWS_DIR );

        echo PHP_EOL . 'Loading View Directory' . PHP_EOL;
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
        elseif ( $event instanceof GetLanguageDirEvent )
        {
            $this->getLanguageDirectory( $event );
        }
        elseif ( $event instanceof GetDefaultLanguageEvent )
        {
            $this->getDefaultLanguage( $event );
        }
    }
}