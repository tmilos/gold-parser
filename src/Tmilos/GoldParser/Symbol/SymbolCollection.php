<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Symbol;

class SymbolCollection implements \Countable, \IteratorAggregate
{
    /** @var Symbol[] */
    private $list = [];

    /**
     * @return SymbolEnd
     */
    public static function eof()
    {
        static $value = null;
        if (!$value) {
            $value = new SymbolEnd(0);
        }

        return $value;
    }

    /**
     * @return SymbolError
     */
    public static function error()
    {
        static $value = null;
        if (!$value) {
            $value = new SymbolError(1);
        }

        return $value;
    }

    public function add(Symbol $symbol)
    {
        $this->list[] = $symbol;
    }

    /**
     * @return Symbol[]
     */
    public function all()
    {
        return $this->list;
    }

    /**
     * @param int $index
     *
     * @return Symbol
     */
    public function item($index)
    {
        return $this->list[$index];
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
