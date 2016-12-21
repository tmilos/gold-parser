<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tmilos\GoldParser\Error\ParseException;
use Tmilos\GoldParser\Error\TokenException;
use Tmilos\GoldParser\Event\AcceptEvent;
use Tmilos\GoldParser\Event\GotoEvent;
use Tmilos\GoldParser\Event\ParseErrorEvent;
use Tmilos\GoldParser\Event\ReduceEvent;
use Tmilos\GoldParser\Event\ShiftEvent;
use Tmilos\GoldParser\Event\TokenErrorEvent;
use Tmilos\GoldParser\Event\TokenReadEvent;
use Tmilos\GoldParser\Lalr\AcceptAction;
use Tmilos\GoldParser\Lalr\GotoAction;
use Tmilos\GoldParser\Lalr\ReduceAction;
use Tmilos\GoldParser\Lalr\ShiftAction;
use Tmilos\GoldParser\Lalr\State;
use Tmilos\GoldParser\Lalr\StateStack;
use Tmilos\GoldParser\Symbol\SymbolCollection;
use Tmilos\GoldParser\Symbol\SymbolCommentEnd;
use Tmilos\GoldParser\Symbol\SymbolCommentLine;
use Tmilos\GoldParser\Symbol\SymbolCommentStart;
use Tmilos\GoldParser\Symbol\SymbolEnd;
use Tmilos\GoldParser\Symbol\SymbolError;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;
use Tmilos\GoldParser\Symbol\SymbolWhiteSpace;
use Tmilos\GoldParser\Tokenizer\NonTerminalToken;
use Tmilos\GoldParser\Tokenizer\TerminalToken;
use Tmilos\GoldParser\Tokenizer\Tokenizer;
use Tmilos\GoldParser\Tokenizer\TokenStack;

class LalrParser
{
    /** @var Tokenizer */
    private $tokenizer;

    /** @var State */
    private $startState;

    /** @var StateStack */
    private $stateStack;

    /** @var TokenStack */
    private $tokenStack;

    /** @var TerminalToken */
    private $lookAhead;

    /** @var bool */
    private $continueParsing;

    /** @var bool */
    private $accepted;

    /** @var bool */
    private $trimReductions;

    /** @var StoreTokensMode */
    private $storeTokens;

    /** @var SymbolCollection */
    private $symbols;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var bool */
    private $throwExceptionsOnErrors;

    /**
     * @param Tokenizer        $tokenizer
     * @param State            $startState
     * @param SymbolCollection $symbols
     */
    public function __construct(Tokenizer $tokenizer, State $startState, SymbolCollection $symbols)
    {
        $this->tokenizer = $tokenizer;
        $this->startState = $startState;
        $this->symbols = $symbols;
        $this->storeTokens = StoreTokensMode::NO_USER_OBJECT();
        $this->eventDispatcher = new EventDispatcher();
        $this->setThrowExceptionsOnErrors(true);
    }

    /**
     * @return bool
     */
    public function getThrowExceptionsOnErrors()
    {
        return $this->throwExceptionsOnErrors;
    }

    /**
     * @param bool $throwExceptionsOnErrors
     */
    public function setThrowExceptionsOnErrors($throwExceptionsOnErrors)
    {
        $this->throwExceptionsOnErrors = (bool) $throwExceptionsOnErrors;
        if ($this->throwExceptionsOnErrors) {
            $this->eventDispatcher->addListener(Events::TOKEN_ERROR, [TokenException::class, 'throwException']);
            $this->eventDispatcher->addListener(Events::PARSE_ERROR, [ParseException::class, 'throwException']);
        } else {
            $this->eventDispatcher->removeListener(Events::TOKEN_ERROR, [TokenException::class, 'throwException']);
            $this->eventDispatcher->removeListener(Events::PARSE_ERROR, [ParseException::class, 'throwException']);
        }
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return $this->accepted;
    }

    /**
     * @return bool
     */
    public function isTrimReductions()
    {
        return $this->trimReductions;
    }

    /**
     * @param bool $trimReductions
     */
    public function setTrimReductions($trimReductions)
    {
        $this->trimReductions = (bool) $trimReductions;
    }

    /**
     * @return StoreTokensMode
     */
    public function getStoreTokens()
    {
        return $this->storeTokens;
    }

    /**
     * @param StoreTokensMode $storeTokens
     */
    public function setStoreTokens(StoreTokensMode $storeTokens)
    {
        $this->storeTokens = $storeTokens;
    }

    /**
     * @return SymbolCollection
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    private function reset()
    {
        $this->stateStack = new StateStack();
        $this->stateStack->push($this->startState);
        $this->tokenStack = new TokenStack();
        $this->lookAhead = null;
        $this->continueParsing = true;
        $this->accepted = false;
    }

    /**
     * @param string $input
     *
     * @return NonTerminalToken|null
     */
    public function parse($input)
    {
        $this->reset();
        $this->tokenizer->setInput($input);
        while ($this->continueParsing) {
            $token = $this->getLookAhead();
            if ($token) {
                $this->parseTerminal($token);
            }
        }

        if ($this->accepted) {
            $result = $this->tokenStack->pop();
            if ($result instanceof NonTerminalToken) {
                return $result;
            } else {
                throw new \LogicException('Expected non-terminal token as the result of parsing');
            }
        } else {
            return null;
        }
    }

    private function parseTerminal(TerminalToken $token)
    {
        $currentState = $this->stateStack->peek();
        $action = $currentState->getActions()->item($token->getSymbol());

        if ($action instanceof ShiftAction) {
            $this->doShift($token, $action);
        } elseif ($action instanceof ReduceAction) {
            $this->doReduce($action);
        } elseif ($action instanceof AcceptAction) {
            $this->doAccept();
        } else {
            $this->continueParsing = false;
            $this->fireParseError($token);
        }
    }

    private function doShift(TerminalToken $token, ShiftAction $action)
    {
        $this->stateStack->push($action->getState());
        $this->tokenStack->push($token);
        $this->lookAhead = null;
        $this->eventDispatcher->dispatch(Events::SHIFT, new ShiftEvent($token, $action->getState()));
    }

    private function doReduce(ReduceAction $action)
    {
        $rhs = $action->getRule()->getRhs();
        $reduceLength = count($rhs);
        $currentState = null;
        $skipReduce = $this->trimReductions && $reduceLength == 1 && $rhs[0] instanceof SymbolNonTerminal;
        if ($skipReduce) {
            $this->stateStack->pop();
            $currentState = $this->stateStack->peek();
        } else {
            $tokens = [];
            for ($i = 0; $i < $reduceLength; ++$i) {
                $this->stateStack->pop();
                $tokens[$reduceLength - $i - 1] = $this->tokenStack->pop();
            }
            $ntToken = new NonTerminalToken($action->getRule(), $tokens);
            $this->tokenStack->push($ntToken);
            $currentState = $this->stateStack->peek();

            $event = new ReduceEvent($action->getRule(), $ntToken, $currentState);
            $this->eventDispatcher->dispatch(Events::REDUCE, $event);
            $this->doReleaseTokens($event->getToken());
            $this->continueParsing = $event->isContinue();
        }

        $gotoAction = $currentState->getActions()->item($action->getRule()->getLhs());
        if ($gotoAction instanceof GotoAction) {
            $this->doGoto($gotoAction);
        } else {
            throw new \RuntimeException('Invalid action table in state');
        }
    }

    private function doAccept()
    {
        $this->continueParsing = false;
        $this->accepted = true;
        $token = $this->tokenStack->peek();
        if ($token instanceof NonTerminalToken) {
            $this->eventDispatcher->dispatch(Events::ACCEPT, new AcceptEvent($token));
        } else {
            throw new \LogicException('Expected non-terminal token when accepted');
        }
    }

    private function doGoto(GotoAction $action)
    {
        $this->stateStack->push($action->getState());
        $this->eventDispatcher->dispatch(Events::GOTO_EVENT, new GotoEvent($action->getSymbol(), $this->stateStack->peek()));
    }

    private function doReleaseTokens(NonTerminalToken $token)
    {
        if (StoreTokensMode::NEVER()->equals($this->storeTokens) ||
            (StoreTokensMode::NO_USER_OBJECT()->equals($this->storeTokens) && $token->getUserObject())
        ) {
            $token->clearTokens();
        }
    }

    /**
     * @return TerminalToken|null
     */
    private function getLookAhead()
    {
        if ($this->lookAhead) {
            return $this->lookAhead;
        }

        do {
            $token = $this->tokenizer->retrieveToken();
            if ($token->getSymbol() instanceof SymbolCommentLine) {
                if (!$this->processCommentLine()) {
                    $this->continueParsing = false;
                }
            } elseif ($token->getSymbol() instanceof SymbolCommentStart) {
                if (!$this->processCommentStart()) {
                    $this->continueParsing = false;
                }
            } elseif ($token->getSymbol() instanceof SymbolWhiteSpace) {
                if (!$this->processWhiteSpace()) {
                    $this->continueParsing = false;
                }
            } elseif ($token->getSymbol() instanceof SymbolError) {
                if (!$this->processError($token)) {
                    $this->continueParsing = false;
                }
            } else {
                $this->lookAhead = $token;
            }

            if (!$this->continueParsing) {
                break;
            }
        } while (!$this->lookAhead);

        if ($this->lookAhead) {
            $event = new TokenReadEvent($this->lookAhead);
            $this->eventDispatcher->dispatch(Events::TOKEN_READ, $event);
            if (!$event->isContinue()) {
                $this->continueParsing = false;
                $this->lookAhead = null;
            }
        }

        return $this->lookAhead;
    }

    private function processWhiteSpace()
    {
        return true;
    }

    /**
     * @return bool
     */
    private function processCommentStart()
    {
        $commentDepth = 1;
        $token = null;
        while ($commentDepth > 0) {
            $token = $this->tokenizer->retrieveToken();
            if ($token->getSymbol() instanceof SymbolCommentEnd) {
                --$commentDepth;
            } elseif ($token->getSymbol() instanceof SymbolCommentStart) {
                ++$commentDepth;
            } elseif ($token->getSymbol() instanceof SymbolEnd) {
                $this->fireEofError();
                break;
            }
        }

        if ($commentDepth == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    private function processCommentLine()
    {
        // skip to end of line
        $result = $this->tokenizer->skipAfterChar("\n");
        if (!$result) {
            $this->fireEofError();
        }

        return $result;
    }

    /**
     * @param TerminalToken $token
     *
     * @return bool
     */
    private function processError(TerminalToken $token)
    {
        $event = new TokenErrorEvent($token);
        $this->eventDispatcher->dispatch(Events::TOKEN_ERROR, $event);

        return $event->isContinue();
    }

    /**
     * @return SymbolCollection
     */
    private function findExpectedTokens()
    {
        $symbols = new SymbolCollection();
        $state = $this->stateStack->peek();
        foreach ($state->getActions() as $action) {
            if ($action instanceof ShiftAction || $action instanceof ReduceAction || $action instanceof AcceptAction) {
                $symbols->add($action->getSymbol());
            }
        }

        return $symbols;
    }

    private function fireEofError()
    {
        $eofToken = new TerminalToken(
            SymbolCollection::eof(),
            SymbolCollection::eof()->getName(),
            $this->tokenizer->getCurrentLocation()
        );

        $this->fireParseError($eofToken);
    }

    private function fireParseError(TerminalToken $token)
    {
        $event = new ParseErrorEvent($token, $this->findExpectedTokens());
        $this->eventDispatcher->dispatch(Events::PARSE_ERROR, $event);
        $this->continueParsing = ContinueMode::STOP()->equals($event->getContinue());
        $this->lookAhead = $event->getNextToken();
        if ($event->getNextToken() && ContinueMode::INSERT()->equals($event->getContinue())) {
            $this->tokenizer->setCurrentLocation($token->getLocation());
        }
    }
}
