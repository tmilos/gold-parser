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

class SymbolRecord
{
    /** @var int */
    private $index;

    /** @var string */
    private $name;

    /** @var int */
    private $kind;

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() !== 4) {
            throw new \RuntimeException('Invalid number of entries for symbol');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 83) {
            throw new \RuntimeException('Invalid symbol header');
        }
        $this->index = $record->getEntries()->item(1)->toIntValue();
        $this->name = $record->getEntries()->item(2)->toStringValue();
        $this->kind = $record->getEntries()->item(3)->toIntValue();
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getKind()
    {
        return $this->kind;
    }
}
