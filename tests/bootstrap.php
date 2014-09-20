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
 * @category  PHP
 * @package   RawPHP/RawRouter
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

use RawPHP\RawYaml\Yaml;

defined( 'DS' )                 || define( 'DS', DIRECTORY_SEPARATOR );
defined( 'SUPPORT_DIR' )        || define( 'SUPPORT_DIR', dirname( __FILE__ ) . DS . '_support' . DS );
defined( 'CONTROLLERS_DIR' )    || define( 'CONTROLLERS_DIR', SUPPORT_DIR . 'controllers' . DS );
defined( 'VIEWS_DIR' )          || define( 'VIEWS_DIR', SUPPORT_DIR . 'views' . DS );
defined( 'LANGUAGE_DIR' )       || define( 'LANGUAGE_DIR', SUPPORT_DIR . 'language' . DS );

require_once dirname( dirname( __FILE__ ) ) . DS . 'vendor' . DS . 'autoload.php';

$config = ( new Yaml( ) )->load( SUPPORT_DIR . 'config.yml' );

require_once CONTROLLERS_DIR . 'HomeController.php';
require_once CONTROLLERS_DIR . 'LangController.php';
require_once CONTROLLERS_DIR . 'UsersController.php';

echo PHP_EOL . PHP_EOL . '************* BOOTSTRAP ********************' . PHP_EOL . PHP_EOL;