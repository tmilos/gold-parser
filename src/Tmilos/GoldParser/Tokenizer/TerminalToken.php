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

use Tmilos\GoldParser\Symbol\SymbolTerminal;

class TerminalToken extends Token
{
    /** @var SymbolTerminal */
    private $symbol;

    /** @var string */
    private $text;

    /** @var Location */
    private $location;

    /**
     * @param SymbolTerminal $symbol
     * @param string         $text
     * @param Location       $location
     */
    public function __construct(SymbolTerminal $symbol, $text, Location $location)
    {
        $this->symbol = $symbol;
        $this->text = $text;
        $this->location = $location;
    }

    /**
     * @return SymbolTerminal
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function __toString()
    {
        return $this->text;
    }
}
