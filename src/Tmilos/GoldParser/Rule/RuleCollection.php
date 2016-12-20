<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Rule;

class RuleCollection implements \Countable, \IteratorAggregate
{
    /** @var Rule[] */
    private $list = [];

    public function add(Rule $rule)
    {
        $this->list[] = $rule;
    }

    public function item($index)
    {
        return $this->list[$index];
    }

    /**
     * @return Rule[]
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
