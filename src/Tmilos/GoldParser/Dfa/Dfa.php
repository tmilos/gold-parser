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

class Dfa implements DfaInterface
{
    /** @var State */
    private $startState;

    /** @var State */
    private $currentState;

    /**
     * @param State $startState
     */
    public function __construct(State $startState)
    {
        $this->startState = $startState;
        $this->currentState = $startState;
    }

    public function reset()
    {
        $this->currentState = $this->startState;
    }

    public function gotoNext($char)
    {
        $transition = $this->currentState->getTransitions()->find($char);
        if ($transition) {
            $this->currentState = $transition->getTarget();

            return $this->currentState;
        } else {
            return null;
        }
    }

    public function getCurrentState()
    {
        return $this->currentState;
    }
}
