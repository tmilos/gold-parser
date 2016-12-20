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
use Tmilos\GoldParser\Tokenizer\NonTerminalToken;

class AcceptEvent extends Event
{
    /** @var NonTerminalToken */
    private $token;

    /**
     * @param NonTerminalToken $token
     */
    public function __construct(NonTerminalToken $token)
    {
        $this->token = $token;
    }

    /**
     * @return NonTerminalToken
     */
    public function getToken()
    {
        return $this->token;
    }
}
