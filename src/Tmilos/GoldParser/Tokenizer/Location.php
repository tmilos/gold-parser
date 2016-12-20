<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Tokenizer;

class Location
{
    /** @var int */
    private $position;

    /** @var int */
    private $line;

    /** @var int */
    private $column;

    /**
     * @param int $position
     * @param int $line
     * @param int $column
     */
    public function __construct($position, $line, $column)
    {
        $this->position = $position;
        $this->line = $line;
        $this->column = $column;
    }

    /**
     * @return Location
     */
    public static function zero()
    {
        return new static(0, 0, 0);
    }

    /**
     * @param Location $location
     *
     * @return Location
     */
    public static function copy(Location $location)
    {
        return new static($location->getPosition(), $location->getLine(), $location->getColumn());
    }

    public function nextLine()
    {
        ++$this->position;
        ++$this->line;
        $this->column = 0;
    }

    public function nextColumn()
    {
        ++$this->position;
        ++$this->column;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    public function __toString()
    {
        return sprintf('(pos: %s, ln: %s, col: %s)', $this->position + 1, $this->line + 1, $this->column + 1);
    }

    public function __clone()
    {
        return self::copy($this);
    }
}
