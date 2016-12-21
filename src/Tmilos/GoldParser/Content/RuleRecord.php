<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Content;

use Tmilos\GoldParser\Structure\Record;

class RuleRecord
{
    /** @var int */
    private $index;

    /** @var int */
    private $nonTerminal;

    /** @var int[] */
    private $symbols;

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() < 4) {
            throw new \RuntimeException('Invalid number of entries for rule');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 82) {
            throw new \RuntimeException('Invalid rule header');
        }
        $this->index = $record->getEntries()->item(1)->toIntValue();
        $this->nonTerminal = $record->getEntries()->item(2)->toIntValue();
        //skip reserved empty entry
        $this->symbols = [];
        $count = $record->getEntries()->count();
        for ($i = 4; $i < $count; ++$i) {
            $this->symbols[] = $record->getEntries()->item($i)->toIntValue();
        }
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return int
     */
    public function getNonTerminal()
    {
        return $this->nonTerminal;
    }

    /**
     * @return \int[]
     */
    public function getSymbols()
    {
        return $this->symbols;
    }
}
