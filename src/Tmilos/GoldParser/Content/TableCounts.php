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

class TableCounts
{
    /** @var int */
    private $symbolTable;

    /** @var int */
    private $characterSetTable;

    /** @var int */
    private $ruleTable;

    /** @var int */
    private $dfaTable;

    /** @var int */
    private $lalrTable;

    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() != 6) {
            throw new \RuntimeException('Invalid number of entries for table counts');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 84) {
            throw new \RuntimeException('Invalid table counts header');
        }

        $this->symbolTable = $record->getEntries()->item(1)->toIntValue();
        $this->characterSetTable = $record->getEntries()->item(2)->toIntValue();
        $this->ruleTable = $record->getEntries()->item(3)->toIntValue();
        $this->dfaTable = $record->getEntries()->item(4)->toIntValue();
        $this->lalrTable = $record->getEntries()->item(5)->toIntValue();
    }

    /**
     * @return int
     */
    public function getSymbolTable()
    {
        return $this->symbolTable;
    }

    /**
     * @return int
     */
    public function getCharacterSetTable()
    {
        return $this->characterSetTable;
    }

    /**
     * @return int
     */
    public function getRuleTable()
    {
        return $this->ruleTable;
    }

    /**
     * @return int
     */
    public function getDfaTable()
    {
        return $this->dfaTable;
    }

    /**
     * @return int
     */
    public function getLalrTable()
    {
        return $this->lalrTable;
    }
}
