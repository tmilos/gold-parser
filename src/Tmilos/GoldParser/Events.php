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

class Events
{
    const PARSE_ERROR = 'gp.parse_error';
    const TOKEN_ERROR = 'gp.token_error';
    const TOKEN_READ = 'gp.token_read';
    const SHIFT = 'gp.shift';
    const REDUCE = 'gp.reduce';
    const ACCEPT = 'gp.accept';
    const GOTO_EVENT = 'gp.goto';
}
