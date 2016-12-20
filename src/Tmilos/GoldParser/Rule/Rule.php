<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Rule;

use Tmilos\GoldParser\Symbol\Symbol;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;

class Rule
{
    /** @var int */
    private $id;

    /** @var SymbolNonTerminal */
    private $lhs;

    /** @var Symbol[] */
    private $rhs;

    /**
     * @param int               $id
     * @param SymbolNonTerminal $lhs
     * @param Symbol[]          $rhs
     */
    public function __construct($id, SymbolNonTerminal $lhs, array $rhs)
    {
        $this->id = $id;
        $this->lhs = $lhs;
        $this->rhs = $rhs;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return SymbolNonTerminal
     */
    public function getLhs()
    {
        return $this->lhs;
    }

    /**
     * @return Symbol[]
     */
    public function getRhs()
    {
        return $this->rhs;
    }

    public function __toString()
    {
        $result = $this->lhs->__toString().' ::= ';
        $result .= implode(' ', array_map(function (Symbol $s) {
            return (string) $s;
        }, $this->rhs));

        return $result;
    }
}
