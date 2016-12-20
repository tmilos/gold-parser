<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Structure;

class RecordCollection implements \Countable, \IteratorAggregate
{
    /** @var Record[] */
    private $records = [];

    public function add(Record $record)
    {
        $this->records[] = $record;
    }

    /**
     * @param int $index
     *
     * @return Record
     */
    public function item($index)
    {
        return $this->records[$index];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->records);
    }

    public function count()
    {
        return count($this->records);
    }
}
