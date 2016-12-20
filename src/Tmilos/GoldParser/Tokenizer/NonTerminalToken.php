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

use Tmilos\GoldParser\Rule\Rule;
use Tmilos\GoldParser\Symbol\SymbolNonTerminal;

class NonTerminalToken extends Token
{
    /** @var Token[] */
    private $tokens = [];

    /** @var Rule */
    private $rule;

    /**
     * @param Rule    $rule
     * @param Token[] $tokens
     */
    public function __construct(Rule $rule, array $tokens)
    {
        $this->rule = $rule;
        $this->tokens = $tokens;
    }

    public function clearTokens()
    {
        $this->tokens = [];
    }

    /**
     * @return SymbolNonTerminal
     */
    public function getSymbol()
    {
        return $this->rule->getLhs();
    }

    /**
     * @return Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    public function __toString()
    {
        return sprintf(
            '%s = [%s]',
            (string) $this->rule->getLhs(),
            implode(' ', array_map(function (Token $token) {
                return (string) $token;
            }, $this->tokens))
        );
    }
}
