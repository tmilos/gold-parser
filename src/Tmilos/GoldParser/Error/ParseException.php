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

use Tmilos\GoldParser\Event\ParseErrorEvent;

class ParseException extends GoldException
{
    /**
     * @param ParseErrorEvent $event
     *
     * @throws ParseException
     */
    public static function throwException(ParseErrorEvent $event)
    {
        throw static::create($event);
    }

    public static function create(ParseErrorEvent $event)
    {
        return new static(sprintf(
            '[Syntax error] Expected %s but found %s',
            implode(' or ', $event->getExpectedTokens()->all()),
            $event->getUnexpectedToken()
        ));
    }
}
