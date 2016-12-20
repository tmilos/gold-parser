<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Event;

use Symfony\Component\EventDispatcher\Event;
use Tmilos\GoldParser\Lalr\State;
use Tmilos\GoldParser\Rule\Rule;
use Tmilos\GoldParser\Tokenizer\NonTerminalToken;

class ReduceEvent extends Event
{
    /** @var Rule */
    private $rule;

    /** @var NonTerminalToken */
    private $token;

    /** @var State */
    private $newState;

    /** @var bool */
    private $continue = true;

    /**
     * @param Rule             $rule
     * @param NonTerminalToken $token
     * @param State            $newState
     */
    public function __construct(Rule $rule, NonTerminalToken $token, State $newState)
    {
        $this->rule = $rule;
        $this->token = $token;
        $this->newState = $newState;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return NonTerminalToken
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return State
     */
    public function getNewState()
    {
        return $this->newState;
    }

    /**
     * @return bool
     */
    public function isContinue()
    {
        return $this->continue;
    }

    /**
     * Determines if the parse process should continue
     * after this event. True by default.
     *
     * @param bool $continue
     *
     * @return ReduceEvent
     */
    public function setContinue($continue)
    {
        $this->continue = (bool) $continue;

        return $this;
    }
}
