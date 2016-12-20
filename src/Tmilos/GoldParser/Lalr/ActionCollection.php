<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Lalr;

use Tmilos\GoldParser\Symbol\Symbol;

class ActionCollection implements \Countable, \IteratorAggregate
{
    /** @var Action[] */
    private $list = [];

    public function add(Action $action)
    {
        $this->list[$action->getSymbol()->getId()] = $action;
    }

    /**
     * @param Symbol $symbol
     *
     * @return Action
     */
    public function item(Symbol $symbol)
    {
        return isset($this->list[$symbol->getId()]) ? $this->list[$symbol->getId()] : null;
    }

    /**
     * @return Action[]
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
