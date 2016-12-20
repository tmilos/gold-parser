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

use Tmilos\GoldParser\Content\ActionSubRecord;
use Tmilos\GoldParser\Rule\RuleCollection;
use Tmilos\GoldParser\Symbol\SymbolCollection;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;
use Tmilos\GoldParser\Symbol\SymbolTerminal;

class ActionFactory
{
    /**
     * @param ActionSubRecord  $record
     * @param StateCollection  $states
     * @param SymbolCollection $symbols
     * @param RuleCollection   $rules
     *
     * @return Action
     */
    public static function createAction(ActionSubRecord $record, StateCollection $states, SymbolCollection $symbols, RuleCollection $rules)
    {
        switch ($record->getAction()) {
            case 1:
                return self::createShiftAction($record, $symbols, $states);
                break;
            case 2:
                return self::createReduceAction($record, $symbols, $rules);
                break;
            case 3:
                return self::createGotoAction($record, $symbols, $states);
                break;
            case 4:
                return self::createAcceptAction($record, $symbols);
                break;
            default:
                throw new \RuntimeException('Unknown action');
        }
    }

    /**
     * @param ActionSubRecord  $record
     * @param SymbolCollection $symbols
     * @param StateCollection  $states
     *
     * @return ShiftAction
     */
    private static function createShiftAction(ActionSubRecord $record, SymbolCollection $symbols, StateCollection $states)
    {
        $symbol = $symbols->item($record->getSymbolIndex());
        $state = $states->item($record->getTarget());

        if ($symbol instanceof SymbolTerminal) {
            return new ShiftAction($symbol, $state);
        }

        throw new \RuntimeException('Expected terminal symbol');
    }

    /**
     * @param ActionSubRecord  $record
     * @param SymbolCollection $symbols
     * @param RuleCollection   $rules
     *
     * @return ReduceAction
     */
    private static function createReduceAction(ActionSubRecord $record, SymbolCollection $symbols, RuleCollection $rules)
    {
        $symbol = $symbols->item($record->getSymbolIndex());
        $rule = $rules->item($record->getTarget());

        if ($symbol instanceof SymbolTerminal) {
            return new ReduceAction($symbol, $rule);
        }

        throw new \RuntimeException('Expected terminal symbol');
    }

    /**
     * @param ActionSubRecord  $record
     * @param SymbolCollection $symbols
     * @param StateCollection  $states
     *
     * @return GotoAction
     */
    private static function createGotoAction(ActionSubRecord $record, SymbolCollection $symbols, StateCollection $states)
    {
        $symbol = $symbols->item($record->getSymbolIndex());
        $state = $states->item($record->getTarget());

        if ($symbol instanceof SymbolNonTerminal) {
            return new GotoAction($symbol, $state);
        }

        throw new \RuntimeException('Expected non-terminal symbol');
    }

    /**
     * @param ActionSubRecord  $record
     * @param SymbolCollection $symbols
     *
     * @return AcceptAction
     */
    private static function createAcceptAction(ActionSubRecord $record, SymbolCollection $symbols)
    {
        $symbol = $symbols->item($record->getSymbolIndex());
        if ($symbol instanceof SymbolTerminal) {
            return new AcceptAction($symbol);
        }

        throw new \RuntimeException('Expected terminal symbol');
    }
}
