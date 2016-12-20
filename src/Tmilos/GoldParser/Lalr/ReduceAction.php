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

use Tmilos\GoldParser\Rule\Rule;
use Tmilos\GoldParser\Symbol\Symbol;
use Tmilos\GoldParser\Symbol\SymbolTerminal;

class ReduceAction extends Action
{
    /** @var Rule */
    private $rule;

    /**
     * @param SymbolTerminal $symbol
     * @param Rule           $rule
     */
    public function __construct(SymbolTerminal $symbol, Rule $rule)
    {
        $this->symbol = $symbol;
        $this->rule = $rule;
    }

    /**
     * @return SymbolTerminal
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }
}
