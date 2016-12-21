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

class LALRStateRecord
{
    private $index;

    /** @var ActionSubRecord[] */
    private $subActions = [];

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() < 3) {
            throw new \RuntimeException('Invalid number of entries for LALR state');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 76) {
            throw new \RuntimeException('Invalid LALR state header');
        }
        $this->index = $record->getEntries()->item(1)->toIntValue();
        //skip empty reserved entry
        if (($record->getEntries()->count() - 3) % 4 !== 0) {
            throw new \RuntimeException('Invalid number of entries for actions in LALR state');
        }
        $count = $record->getEntries()->count();
        for ($i = 3; $i < $count; $i += 4) {
            $this->subActions[] = new ActionSubRecord(
                $record->getEntries()->item($i)->toIntValue(),
                $record->getEntries()->item($i + 1)->toIntValue(),
                $record->getEntries()->item($i + 2)->toIntValue()
            );
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
     * @return ActionSubRecord[]
     */
    public function getSubActions()
    {
        return $this->subActions;
    }
}
