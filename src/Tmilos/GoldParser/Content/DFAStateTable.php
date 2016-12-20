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

class DFAStateTable implements \Countable, \IteratorAggregate
{
    /** @var DFAStateRecord[] */
    private $list = [];

    /**
     * @param Structure $structure
     * @param int       $start
     * @param int       $count
     */
    public function __construct(Structure $structure, $start, $count)
    {
        $end = $start + $count;
        for ($i = $start; $i < $end; ++$i) {
            $this->list[] = new DFAStateRecord($structure->getRecords()->item($i));
        }
    }

    public function item($index)
    {
        return $this->list[$index];
    }

    /**
     * @return DFAStateRecord[]
     */
    public function all()
    {
        return $this->list;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    public function count()
    {
        return count($this->list);
    }
}
