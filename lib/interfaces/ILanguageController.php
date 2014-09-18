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

/**
 * The language controller interface.
 * 
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface ILanguageController
{
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
     * @param type $controller the controller name
     * @param type $language   the language to load
     * @param bool $return     whether to return the translations or to send them
     *                         to the view
     * 
     * @filter ON_GET_LANG_DIR_FILTER(4)
     * @filter ON_GET_DEFAULT_LANG_FILTER(4)
     * 
     * @action ON_LOAD_LANG_ACTION
     * 
     * @filter ON_LOAD_LANG_FILTER(4)
     * 
     * @return array returns the language array
     * 
     * @throws RawException if there is something wrong with loading the language
     */
    public function loadLanguage( $controller, $language = 'en_US', $return = FALSE );
}