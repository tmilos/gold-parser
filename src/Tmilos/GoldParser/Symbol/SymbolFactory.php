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

use Tmilos\GoldParser\Content\SymbolRecord;

class SymbolFactory
{
    /**
     * @param SymbolRecord $record
     *
     * @return Symbol
     */
    public static function createSymbol(SymbolRecord $record)
    {
        switch ($record->getKind()) {
            case 0:
                return new SymbolNonTerminal($record->getIndex(), $record->getName());
                break;
            case 1:
                return new SymbolTerminal($record->getIndex(), $record->getName());
                break;
            case 2:
                return new SymbolWhiteSpace($record->getIndex());
                break;
            case 3:
                return SymbolCollection::eof();
                break;
            case 4:
                return new SymbolCommentStart($record->getIndex());
                break;
            case 5:
                return new SymbolCommentEnd($record->getIndex());
                break;
            case 6:
                return new SymbolCommentLine($record->getIndex());
                break;
            case 7:
                return SymbolCollection::error();
                break;
            default:
                return new SymbolError(-1);
        }
    }
}
