<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Lalr;

use Tmilos\GoldParser\Symbol\Symbol;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;

class GotoAction extends Action
{
    /** @var State */
    private $state;

    /**
     * @param SymbolNonTerminal $symbol
     * @param State             $state
     */
    public function __construct(SymbolNonTerminal $symbol, State $state)
    {
        $this->symbol = $symbol;
        $this->state = $state;
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
    public function getState()
    {
        return $this->state;
    }
}
