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

use Tmilos\GoldParser\Dfa\EndState;

class AcceptInfo
{
    /** @var EndState */
    private $state;

    /** @var Location */
    private $location;

    /**
     * @param EndState $state
     * @param Location $location
     */
    public function __construct(EndState $state, Location $location)
    {
        $this->state = $state;
        $this->location = $location;
    }

    /**
     * @return EndState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }
}
