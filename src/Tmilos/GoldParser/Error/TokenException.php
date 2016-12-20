<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Error;

use Tmilos\GoldParser\Event\TokenErrorEvent;

class TokenException extends GoldException
{
    /**
     * @param TokenErrorEvent $event
     *
     * @throws TokenException
     */
    public static function throwException(TokenErrorEvent $event)
    {
        throw static::create($event);
    }

    public static function create(TokenErrorEvent $event)
    {
        return new static(sprintf(
            '[TOKEN ERROR] Unexpected text %s at line %s col %s',
            $event->getToken()->getText(),
            $event->getToken()->getLocation()->getLine(),
            $event->getToken()->getLocation()->getColumn()
        ));
    }
}
