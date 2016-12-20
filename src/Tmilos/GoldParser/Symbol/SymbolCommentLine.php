<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Symbol;

class SymbolCommentLine extends SymbolTerminal
{
    public function __construct($id)
    {
        parent::__construct($id, '(Symbol Line)');
    }
}
