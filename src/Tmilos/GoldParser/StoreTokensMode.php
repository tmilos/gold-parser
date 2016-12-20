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
 * @method static StoreTokensMode ALWAYS()
 * @method static StoreTokensMode NO_USER_OBJECT()
 * @method static StoreTokensMode NEVER()
 */
class StoreTokensMode extends AbstractEnum
{
    const ALWAYS = 1;
    const NO_USER_OBJECT = 2;
    const NEVER = 0;
}
