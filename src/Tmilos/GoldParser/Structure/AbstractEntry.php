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

abstract class AbstractEntry
{
    protected $value;

    /**
     * @return int
     */
    public function toByteValue()
    {
        if ($this instanceof ByteEntry) {
            return $this->value;
        }

        throw new \RuntimeException('Entry is not a byte');
    }

    /**
     * @return bool
     */
    public function toBoolValue()
    {
        if ($this instanceof BooleanEntry) {
            return $this->value;
        }

        throw new \RuntimeException('Entry is not a bool');
    }

    /**
     * @return int
     */
    public function toIntValue()
    {
        if ($this instanceof IntegerEntry) {
            return $this->value;
        }

        throw new \RuntimeException('Entry is not an integer');
    }

    /**
     * @return string
     */
    public function toStringValue()
    {
        if ($this instanceof StringEntry) {
            return $this->value;
        }

        throw new \RuntimeException('Entry is not a string');
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
