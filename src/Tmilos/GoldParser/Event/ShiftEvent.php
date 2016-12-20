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
use Tmilos\GoldParser\Tokenizer\TerminalToken;

class ShiftEvent extends Event
{
    /** @var TerminalToken */
    private $token;

    /** @var State */
    private $newState;

    /**
     * @param TerminalToken $token
     * @param State         $newState
     */
    public function __construct(TerminalToken $token, State $newState)
    {
        $this->token = $token;
        $this->newState = $newState;
    }

    /**
     * @return TerminalToken
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
}
