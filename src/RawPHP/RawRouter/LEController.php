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
use RawPHP\RawRouter\Contract\ILanguageController;
use RawPHP\RawRouter\Event\Events;
use RawPHP\RawRouter\Event\GetDefaultLanguageEvent;
use RawPHP\RawRouter\Event\GetLanguageDirEvent;
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
abstract class LEController extends Controller implements ILanguageController
{
    /** @var array */
    public $language = [ ];

    /**
     * Initialises for LE (language-enabled) Controller.
     *
     * This method hooks into the Controller::ON_LOAD_EXTRA_DATA_FILTER
     * hook to be able to extract language translations in the $e (extra)
     * variable.
     *
     * @param array $config configuration array
     */
    public function addDefaultListeners( $config = NULL )
    {
        parent::addDefaultListeners( $config );

//        $this->addFilter( self::ON_LOAD_EXTRA_DATA_FILTER,
//                          [ $this, 'loadLanguageCallback' ]
//        );
    }

    /**
     * Loads a language file for a controller.
     *
     * This method requires that LEController::ON_GET_LANG_DIR_FILTER hook has
     * a valid callback that sets the language directory for the controller.
     *
     * It also requires that LEController::ON_GET_DEFAULT_LANG_FILTER hook has
     * a valid callback that sets the default language to be used if the requested
     * language file is not found.
     *
     * @param string $controller the controller name
     * @param string $language   the language to load
     * @param bool   $return     whether to return the translations or to send them
     *                           to the view
     *
     * @return array returns the language array
     *
     * @throws RawException if there is something wrong with loading the language
     */
    public function loadLanguage( $controller, $language = 'en_US', $return = FALSE )
    {
        $langDirEvent = new GetLanguageDirEvent();

        $this->dispatcher->fire( Events::EVENT_GET_LANG_DIR, $langDirEvent );

        $langDir = $langDirEvent->getLangDir();

        $retVal = FALSE;

        if ( NULL === $langDir )
        {
            $retVal = FALSE;
        }
        else
        {
            $file = $langDir . $controller . DS . $language . '_lang.php';

            if ( !file_exists( $file ) )
            {
                $defaultLangEvent = new GetDefaultLanguageEvent();

                $this->dispatcher->fire( Events::EVENT_GET_DEFAULT_LANG, $defaultLangEvent );

                $defaultLang = $defaultLangEvent->getDefaultLanguage();

                if ( NULL === $defaultLang )
                {
                    $retval = FALSE;
                }
                else
                {
                    $file = $langDir
                        . $controller . DS
                        . $defaultLang
                        . '_lang.php';
                }
            }

            if ( NULL === $file )
            {
                throw new RawException( 'Failed to load language file for ' . $controller );
            }

            $lang = include_once $file;

            $this->language = array_merge( $this->language, $lang );

            $retVal = $lang;
        }

        return $retVal;
    }

    /**
     * The callback for Controller::ON_LOAD_EXTRA_DATA_FILTER. This method
     * adds the language translations to the global $_t variable.
     *
     * Make sure that you add key->value pairs to the array and not replace
     * the previous data in the $data array (unless that is your intention)
     *
     * @param array $data data already added by other callbacks
     *
     * @return array the data array with any added data
     */
    public function loadLanguageCallback( $data = [ ] )
    {
        $data[ '_t' ] = $this->language;

        return $data;
    }

    public function handle( IEvent $event, $name, IDispatcher $dispatcher )
    {
        parent::handle( $event, $name, $dispatcher );
    }
}