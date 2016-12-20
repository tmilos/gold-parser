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
use Tmilos\GoldParser\ContinueMode;
use Tmilos\GoldParser\Symbol\SymbolCollection;
use Tmilos\GoldParser\Tokenizer\TerminalToken;

class ParseErrorEvent extends Event
{
    /** @var TerminalToken */
    private $unexpectedToken;

    /** @var SymbolCollection */
    private $expectedTokens;

    /** @var ContinueMode */
    private $continue;

    /** @var TerminalToken */
    private $nextToken;

    /**
     * @param TerminalToken    $unexpectedToken
     * @param SymbolCollection $expectedTokens
     */
    public function __construct(TerminalToken $unexpectedToken, SymbolCollection $expectedTokens)
    {
        $this->unexpectedToken = $unexpectedToken;
        $this->expectedTokens = $expectedTokens;
    }

    /**
     * @return TerminalToken
     */
    public function getUnexpectedToken()
    {
        return $this->unexpectedToken;
    }

    /**
     * @return SymbolCollection
     */
    public function getExpectedTokens()
    {
        return $this->expectedTokens;
    }

    /**
     * @return ContinueMode
     */
    public function getContinue()
    {
        return $this->continue;
    }

    /**
     * The continue property can be set during the parse error event.
     * It can be set to the following:
     * (1) Stop to not try to parse the rest of the input.
     * (2) Insert will pretend that the next token is the one set in
     *     NextToken after which the current "bad" token will be parsed again.
     * (3) Skip will just ignore the current bad token and proceed to parse
     *     the input as if nothing happened.
     * The default value is Stop.
     *
     * @param ContinueMode $continue
     */
    public function setContinue(ContinueMode $continue)
    {
        $this->continue = $continue;
    }

    /**
     * @return TerminalToken
     */
    public function getNextToken()
    {
        return $this->nextToken;
    }

    /**
     * If the continue property is set to true, then NextToken will be the
     * next token to be used as input to the parser (it will become the lookahead token).
     * The default value is null, which means that the next token will be read from the
     * normal input stream.
     *
     * @param TerminalToken $nextToken
     */
    public function setNextToken(TerminalToken $nextToken)
    {
        $this->nextToken = $nextToken;
    }
}
