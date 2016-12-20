<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Tokenizer;

interface Tokenizer
{
    /**
     * @return string
     */
    public function getInput();

    /**
     * @param $input
     */
    public function setInput($input);

    /**
     * @return Location
     */
    public function getCurrentLocation();

    /**
     * @param Location $location
     */
    public function setCurrentLocation(Location $location);

    /**
     * @return TerminalToken
     */
    public function retrieveToken();

    /**
     * @param string $char
     *
     * @return bool
     */
    public function skipToChar($char);

    /**
     * @param string $char
     *
     * @return bool
     */
    public function skipAfterChar($char);
}
