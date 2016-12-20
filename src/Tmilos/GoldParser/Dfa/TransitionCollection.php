<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Dfa;

class TransitionCollection implements \Countable, \IteratorAggregate
{
    /** @var Transition[] */
    private $list = [];

    /** @var Transition[] char => Transition */
    private $map = [];

    public function add(Transition $transition)
    {
        foreach ($transition->getCharset() as $char) {
            $this->map[$char] = $transition;
        }
        $this->list[] = $transition;
    }

    /**
     * @param string $char
     *
     * @return Transition
     */
    public function find($char)
    {
        return isset($this->map[$char]) ? $this->map[$char] : null;
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
