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

class ActionSubRecord
{
    /** @var int */
    private $symbolIndex;

    /** @var int */
    private $action;

    /** @var int */
    private $target;

    /**
     * @param int $symbolIndex
     * @param int $action
     * @param int $target
     */
    public function __construct($symbolIndex, $action, $target)
    {
        $this->symbolIndex = $symbolIndex;
        $this->action = $action;
        $this->target = $target;
    }

    /**
     * @return int
     */
    public function getSymbolIndex()
    {
        return $this->symbolIndex;
    }

    /**
     * @return int
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getTarget()
    {
        return $this->target;
    }
}
