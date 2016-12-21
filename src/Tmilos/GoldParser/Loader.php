<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser;

use Tmilos\GoldParser\Content\Content;
use Tmilos\GoldParser\Dfa\EndState;
use Tmilos\GoldParser\Dfa\Transition;
use Tmilos\GoldParser\Lalr\ActionFactory;
use Tmilos\GoldParser\Lalr\StateCollection;
use Tmilos\GoldParser\Rule\Rule;
use Tmilos\GoldParser\Rule\RuleCollection;
use Tmilos\GoldParser\Structure\AbstractEntry;
use Tmilos\GoldParser\Structure\EntryFactory;
use Tmilos\GoldParser\Structure\Record;
use Tmilos\GoldParser\Structure\RecordCollection;
use Tmilos\GoldParser\Structure\Structure;
use Tmilos\GoldParser\Symbol\SymbolCollection;
use Tmilos\GoldParser\Symbol\SymbolFactory;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;
use Tmilos\GoldParser\Symbol\SymbolTerminal;
use Tmilos\GoldParser\Tokenizer\StringTokenizer;
use Tmilos\GoldParser\Tokenizer\Tokenizer;

class Loader
{
    /** @var Structure */
    private $structure;

    /** @var Content */
    private $content;

    /** @var SymbolCollection */
    private $symbols;

    /** @var Dfa\StateCollection */
    private $dfaStates;

    /** @var Lalr\StateCollection */
    private $parserStates;

    /** @var RuleCollection */
    private $rules;

    /**
     * @param string $filename
     *
     * @return Loader
     */
    public static function fromFile($filename)
    {
        $f = fopen($filename, 'r');
        $loader = new static($f);
        fclose($f);

        return $loader;
    }

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $reader = new Reader($stream);
        $this->readFile($reader);
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return SymbolCollection
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @return Dfa\StateCollection
     */
    public function getDfaStates()
    {
        return $this->dfaStates;
    }

    /**
     * @return StateCollection
     */
    public function getParserStates()
    {
        return $this->parserStates;
    }

    /**
     * @return RuleCollection
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return Tokenizer
     */
    public function createNewTokenizer()
    {
        $startState = $this->dfaStates->item($this->content->getInitialStatesRecord()->getDfa());
        $dfa = new Dfa\Dfa($startState);

        return new StringTokenizer($dfa);
    }

    public function createNewParser()
    {
        $startState = $this->parserStates->item($this->content->getInitialStatesRecord()->getLalr());

        return new LalrParser(
            $this->createNewTokenizer(),
            $startState,
            $this->symbols
        );
    }

    private function readFile(Reader $reader)
    {
        $header = $reader->readUnicodeString();
        if (!strpos($header, 'GOLD') === 0) {
            throw new \RuntimeException('Invalid file header');
        }
        $records = new RecordCollection();
        while (!$reader->eof()) {
            $r = $this->readRecord($reader);
            if (!$r) {
                break;
            }
            $records->add($r);
        }

        $this->structure = new Structure($header, $records);
        $this->content = new Content($this->structure);
        $this->dfaStates = $this->createDfaStates($this->content);
        $this->parserStates = $this->createParserStates($this->content);
    }

    private function createParserStates(Content $content)
    {
        $this->rules = $this->createRules($content);

        $states = new StateCollection();
        foreach ($content->getLalrStateTable()->all() as $record) {
            $states->add(new Lalr\State($record->getIndex()));
        }

        foreach ($content->getLalrStateTable()->all() as $record) {
            $state = $states->item($record->getIndex());
            foreach ($record->getSubActions() as $subRecord) {
                $action = ActionFactory::createAction($subRecord, $states, $this->symbols, $this->rules);
                $state->getActions()->add($action);
            }
        }

        return $states;
    }

    /**
     * @param Content $content
     *
     * @return RuleCollection
     */
    private function createRules(Content $content)
    {
        $rules = new RuleCollection();
        foreach ($content->getRuleTable()->all() as $ruleRecord) {
            $lhs = $this->symbols->item($ruleRecord->getNonTerminal());
            $rhs = [];
            foreach ($ruleRecord->getSymbols() as $symbolId) {
                $rhs[] = $this->symbols->item($symbolId);
            }

            if ($lhs instanceof SymbolNonTerminal) {
                $rules->add(new Rule($ruleRecord->getIndex(), $lhs, $rhs));
            } else {
                throw new \RuntimeException('Expected non-terminal symbol');
            }
        }

        return $rules;
    }

    /**
     * @param Content $content
     *
     * @return Dfa\StateCollection
     */
    private function createDfaStates(Content $content)
    {
        $this->symbols = $this->createSymbols($content);
        $states = new Dfa\StateCollection();
        foreach ($content->getDfaStateTable()->all() as $stateRecord) {
            if ($stateRecord->isAcceptState()) {
                $symbol = $this->symbols->item($stateRecord->getAcceptIndex());
                if ($symbol instanceof SymbolTerminal) {
                    $state = new EndState($stateRecord->getIndex(), $symbol);
                } else {
                    throw new \RuntimeException('Expected terminal symbol');
                }
            } else {
                $state = new Dfa\State($stateRecord->getIndex());
            }
            $states->add($state);
        }

        foreach ($content->getDfaStateTable()->all() as $stateRecord) {
            foreach ($stateRecord->getEdgeSubRecords() as $edgeSubRecord) {
                $source = $states->item($stateRecord->getIndex());
                $target = $states->item($edgeSubRecord->getTargetIndex());
                $charsetRecord = $content->getCharacterSetTable()->item($edgeSubRecord->getCharSetIndex());
                $transition = new Transition($target, $charsetRecord->getCharacters());
                $source->getTransitions()->add($transition);
            }
        }

        return $states;
    }

    /**
     * @param Content $content
     *
     * @return SymbolCollection
     */
    private function createSymbols(Content $content)
    {
        $symbols = new SymbolCollection();
        foreach ($content->getSymbolTable() as $symbolRecord) {
            $symbols->add(SymbolFactory::createSymbol($symbolRecord));
        }

        return $symbols;
    }

    /**
     * @param Reader $reader
     *
     * @return Record
     */
    private function readRecord(Reader $reader)
    {
        $record = new Record();
        $entriesHeader = $reader->readByte();
        if ($entriesHeader === 0) {
            return null;
        }
        if ($entriesHeader !== 77) {
            throw new \RuntimeException(sprintf('Invalid entries header: %s', $entriesHeader));
        }
        $entriesCount = $reader->readInt16();
        for ($i = 0; $i < $entriesCount; ++$i) {
            $record->getEntries()->add($this->readEntry($reader));
        }

        return $record;
    }

    /**
     * @param Reader $reader
     *
     * @return AbstractEntry
     */
    private function readEntry(Reader $reader)
    {
        $entry = EntryFactory::createEntry($reader);

        return $entry;
    }
}
