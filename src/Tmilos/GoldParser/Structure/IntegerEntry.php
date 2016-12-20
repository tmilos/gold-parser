<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Structure;

use Tmilos\GoldParser\Reader;

class IntegerEntry extends AbstractEntry
{
    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->value = $reader->readInt16();
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
