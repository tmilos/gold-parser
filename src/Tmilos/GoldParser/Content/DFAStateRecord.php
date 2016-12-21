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

class DFAStateRecord
{
    /** @var int */
    private $index;

    /** @var bool */
    private $acceptState;

    /** @var int */
    private $acceptIndex;

    /** @var EdgeSubRecord[] */
    private $edgeSubRecords = [];

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() < 5) {
            throw new \RuntimeException('Invalid number of entries for DFA state');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 68) {
            throw new \RuntimeException('Invalid DFA state header');
        }
        $this->index = $record->getEntries()->item(1)->toIntValue();
        $this->acceptState = $record->getEntries()->item(2)->toBoolValue();
        $this->acceptIndex = $record->getEntries()->item(3)->toIntValue();
        //skip empty reserved entry
        if (($record->getEntries()->count() - 5) % 3 !== 0) {
            throw new \RuntimeException('Invalid number of entries for edges in DFA state');
        }
        $count = $record->getEntries()->count();
        for ($i = 5; $i < $count; $i += 3) {
            $this->edgeSubRecords[] = new EdgeSubRecord($record->getEntries()->item($i)->toIntValue(), $record->getEntries()->item($i + 1)->toIntValue());
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
     * @return bool
     */
    public function isAcceptState()
    {
        return $this->acceptState;
    }

    /**
     * @return int
     */
    public function getAcceptIndex()
    {
        return $this->acceptIndex;
    }

    /**
     * @return EdgeSubRecord[]
     */
    public function getEdgeSubRecords()
    {
        return $this->edgeSubRecords;
    }
}
