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
    /** @var StateCollection */
    private $states;

    /** @var State */
    private $startState;

    /** @var State */
    private $currentState;

    /**
     * @param StateCollection $states
     * @param State           $startState
     */
    public function __construct(StateCollection $states, State $startState)
    {
        $this->states = $states;
        $this->startState = $startState;
        $this->currentState = $startState;
    }

    public function reset()
    {
        $this->currentState = $this->startState;
    }

    public function GotoNext($char)
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
