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

class InitialStatesRecord
{
    /** @var int */
    private $dfa;

    /** @var int */
    private $lalr;

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() !== 3) {
            throw new \RuntimeException('Invalid number of entries for initial states');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 73) {
            throw new \RuntimeException('Invalid initial states header');
        }
        $this->dfa = $record->getEntries()->item(1)->toIntValue();
        $this->lalr = $record->getEntries()->item(2)->toIntValue();
    }

    /**
     * @return int
     */
    public function getDfa()
    {
        return $this->dfa;
    }

    /**
     * @return int
     */
    public function getLalr()
    {
        return $this->lalr;
    }
}
