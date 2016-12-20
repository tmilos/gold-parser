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

use Tmilos\GoldParser\Structure\Structure;

class Content
{
    /** @var Parameters */
    private $parameters;

    /** @var TableCounts */
    private $tableCounts;

    /** @var SymbolTable */
    private $symbolTable;

    /** @var CharacterSetTable */
    private $characterSetTable;

    /** @var RuleTable */
    private $ruleTable;

    /** @var InitialStatesRecord */
    private $initialStatesRecord;

    /** @var DFAStateTable */
    private $dfaStateTable;

    /** @var LALRStateTable */
    private $lalrStateTable;

    public function __construct(Structure $structure)
    {
        if ($structure->getRecords()->count() < 3) {
            throw new \RuntimeException('File does not have enough records');
        }
        $this->parameters = new Parameters($structure->getRecords()->item(0));
        $this->tableCounts = new TableCounts($structure->getRecords()->item(1));

        $initialStatesStart = 2;
        $characterSetStart = $initialStatesStart + 1;
        $symbolStart = $characterSetStart + $this->tableCounts->getCharacterSetTable();
        $ruleStart = $symbolStart + $this->tableCounts->getSymbolTable();
        $dfaStart = $ruleStart + $this->tableCounts->getRuleTable();
        $lalrStart = $dfaStart + $this->tableCounts->getDfaTable();
        $specifiedRecordCount = $lalrStart + $this->tableCounts->getLalrTable();
        if ($structure->getRecords()->count() != $specifiedRecordCount) {
            throw new \RuntimeException('Invalid number of records');
        }

        $this->characterSetTable = new CharacterSetTable($structure, $characterSetStart, $this->tableCounts->getCharacterSetTable());
        $this->symbolTable = new SymbolTable($structure, $symbolStart, $this->tableCounts->getSymbolTable());
        $this->ruleTable = new RuleTable($structure, $ruleStart, $this->tableCounts->getRuleTable());
        $this->initialStatesRecord = new InitialStatesRecord($structure->getRecords()->item($initialStatesStart));
        $this->dfaStateTable = new DFAStateTable($structure, $dfaStart, $this->tableCounts->getDfaTable());
        $this->lalrStateTable = new LALRStateTable($structure, $lalrStart, $this->tableCounts->getLalrTable());
    }

    /**
     * @return Parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return TableCounts
     */
    public function getTableCounts()
    {
        return $this->tableCounts;
    }

    /**
     * @return SymbolTable
     */
    public function getSymbolTable()
    {
        return $this->symbolTable;
    }

    /**
     * @return CharacterSetTable
     */
    public function getCharacterSetTable()
    {
        return $this->characterSetTable;
    }

    /**
     * @return RuleTable
     */
    public function getRuleTable()
    {
        return $this->ruleTable;
    }

    /**
     * @return InitialStatesRecord
     */
    public function getInitialStatesRecord()
    {
        return $this->initialStatesRecord;
    }

    /**
     * @return DFAStateTable
     */
    public function getDfaStateTable()
    {
        return $this->dfaStateTable;
    }

    /**
     * @return LALRStateTable
     */
    public function getLalrStateTable()
    {
        return $this->lalrStateTable;
    }
}
