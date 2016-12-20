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

class EdgeSubRecord
{
    /** @var int */
    private $targetIndex;

    /** @var int */
    private $charSetIndex;

    /**
     * @param int $charSetIndex
     * @param int $targetIndex
     */
    public function __construct($charSetIndex, $targetIndex)
    {
        $this->charSetIndex = $charSetIndex;
        $this->targetIndex = $targetIndex;
    }

    /**
     * @return int
     */
    public function getTargetIndex()
    {
        return $this->targetIndex;
    }

    /**
     * @return int
     */
    public function getCharSetIndex()
    {
        return $this->charSetIndex;
    }
}
