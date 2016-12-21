<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Dfa;

interface DfaInterface
{
    /**
     * Sets the DFA back to the starting state, so it can be used to get a new token.
     *
     * @return void
     */
    public function reset();

    /**
     * Goto the next state depending on an input character.
     *
     * @param string $char
     *
     * @return State|null
     */
    public function gotoNext($char);

    /**
     * The current state in the DFA.
     *
     * @return State
     */
    public function getCurrentState();
}
