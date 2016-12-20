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

class TokenStack
{
    /** @var Token[] */
    private $stack = [];

    public function clear()
    {
        $this->stack = [];
    }

    /**
     * @return null|Token
     */
    public function peek()
    {
        if ($this->stack) {
            return end($this->stack);
        }

        return null;
    }

    /**
     * @return null|Token
     */
    public function pop()
    {
        return array_pop($this->stack);
    }

    public function push(Token $state)
    {
        array_push($this->stack, $state);
    }

    public function count()
    {
        return count($this->stack);
    }
}
