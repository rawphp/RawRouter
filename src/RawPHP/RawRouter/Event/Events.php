<?php

/**
 * This file is part of Step in Deals application.
 *
 * Copyright (c) 2014 Tom Kaczocha
 *
 * This Source Code is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, you can obtain one at http://mozilla.org/MPL/2.0/.
 *
 * PHP version 5.4
 *
 * @category  PHP
 * @package   RawPHP\RawRouter\Event
 * @author    Tom Kaczocha <tom@crazydev.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://crazydev.org/licenses/mpl.txt MPL
 * @link      http://crazydev.org/
 */

namespace RawPHP\RawRouter\Event;

/**
 * Class Events
 *
 * @package RawPHP\RawRouter\Event
 */
class Events
{
    const EVENT_BEFORE_CONTROLLER_RUN                   = 'controller.before.run';
    const EVENT_AFTER_CONTROLLER_RUN                    = 'controller.after.run';
    const EVENT_GET_VIEWS_DIR                           = 'dir.get.views';
    const EVENT_GET_LANG_DIR                            = 'dir.get.lang';
    const EVENT_GET_DEFAULT_LANG                        = 'lang.default';
}