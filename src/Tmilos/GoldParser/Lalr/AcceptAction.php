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

use Tmilos\GoldParser\Symbol\SymbolTerminal;

class AcceptAction extends Action
{
    /**
     * @param SymbolTerminal $symbol
     */
    public function __construct(SymbolTerminal $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return SymbolTerminal
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}
