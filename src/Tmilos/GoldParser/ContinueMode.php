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

use Tmilos\Value\AbstractEnum;

/**
 * @method static ContinueMode STOP()
 * @method static ContinueMode INSERT()
 * @method static ContinueMode SKIP()
 */
class ContinueMode extends AbstractEnum
{
    const STOP = 0;
    const INSERT = 1;
    const SKIP = 2;
}
