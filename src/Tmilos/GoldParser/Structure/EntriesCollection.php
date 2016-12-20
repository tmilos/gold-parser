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

class EntriesCollection implements \Countable, \IteratorAggregate
{
    /** @var AbstractEntry[] */
    private $entries = [];

    public function add(AbstractEntry $entry)
    {
        $this->entries[] = $entry;
    }

    /**
     * @param int $index
     *
     * @return AbstractEntry
     */
    public function item($index)
    {
        return $this->entries[$index];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->entries);
    }

    public function count()
    {
        return count($this->entries);
    }
}
