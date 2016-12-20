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

class TokenErrorEvent extends Event
{
    /** @var TerminalToken */
    private $token;

    /** @var bool */
    private $continue = false;

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
     * The continue property can be set during the token error event,
     * to continue the parsing process. The current token will be ignored.
     * Default value is false.
     *
     * @param bool $continue
     */
    public function setContinue($continue)
    {
        $this->continue = $continue;
    }
}
