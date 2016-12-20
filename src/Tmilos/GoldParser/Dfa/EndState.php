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

use Tmilos\GoldParser\Symbol\SymbolTerminal;

class EndState extends State
{
    /** @var SymbolTerminal */
    private $acceptSymbol;

    /**
     * @param int            $id
     * @param SymbolTerminal $acceptSymbol
     */
    public function __construct($id, SymbolTerminal $acceptSymbol)
    {
        parent::__construct($id);

        $this->acceptSymbol = $acceptSymbol;
    }

    /**
     * @return SymbolTerminal
     */
    public function getAcceptSymbol()
    {
        return $this->acceptSymbol;
    }
}
