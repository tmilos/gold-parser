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

abstract class Token
{
    /** @var mixed */
    private $userObject;

    /**
     * @return mixed
     */
    public function getUserObject()
    {
        return $this->userObject;
    }

    /**
     * @param mixed $userObject
     */
    public function setUserObject($userObject)
    {
        $this->userObject = $userObject;
    }
}
