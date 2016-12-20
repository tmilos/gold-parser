<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Event;

use Symfony\Component\EventDispatcher\Event;
use Tmilos\GoldParser\Lalr\State;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;

class GotoEvent extends Event
{
    /** @var SymbolNonTerminal */
    private $symbol;

    /** @var State */
    private $newState;

    /**
     * @param SymbolNonTerminal $symbol
     * @param State             $newState
     */
    public function __construct(SymbolNonTerminal $symbol, State $newState)
    {
        $this->symbol = $symbol;
        $this->newState = $newState;
    }

    /**
     * @return SymbolNonTerminal
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return State
     */
    public function getNewState()
    {
        return $this->newState;
    }
}
