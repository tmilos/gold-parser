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
use Tmilos\GoldParser\Tokenizer\TerminalToken;

class TokenReadEvent extends Event
{
    /** @var TerminalToken */
    private $token;

    /** @var bool */
    private $continue = true;

    /**
     * @param TerminalToken $token
     */
    public function __construct(TerminalToken $token)
    {
        $this->token = $token;
    }

    /**
     * @return TerminalToken
     */
    public function getToken()
    {
        return $this->token;
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
     * @return TokenReadEvent
     */
    public function setContinue($continue)
    {
        $this->continue = $continue;

        return $this;
    }
}
