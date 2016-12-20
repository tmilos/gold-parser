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

class Transition
{
    /** @var State */
    private $target;

    /** @var array */
    private $charset;

    /**
     * @param State  $target
     * @param string $characters
     */
    public function __construct(State $target, $characters)
    {
        $this->target = $target;
        $this->charset = preg_split('//u', $characters, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return State
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return string[]
     */
    public function getCharset()
    {
        return $this->charset;
    }
}
