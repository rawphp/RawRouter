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

use RawPHP\RawDispatcher\Event;

/**
 * Class GetDefaultLanguageEvent
 *
 * @package RawPHP\RawRouter\Event
 */
class GetDefaultLanguageEvent extends Event
{
    /** @var  string */
    protected $defaultLanguage;

    /**
     * Get default language.
     *
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * Set default language.
     *
     * @param string $defaultLanguage
     */
    public function setDefaultLanguage( $defaultLanguage )
    {
        $this->defaultLanguage = $defaultLanguage;
    }
}