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

class EntryFactory
{
    public static function createEntry(Reader $reader)
    {
        $entryType = $reader->readByte();
        switch ($entryType) {
            case 69: // E
                return new EmptyEntry();
                break;
            case 98: // b
                // byte entry
                return new ByteEntry($reader);
                break;
            case 66: // B
                return new BooleanEntry($reader);
                break;
            case 73: // I
                return new IntegerEntry($reader);
                break;
            case 83: // S
                return new StringEntry($reader);
                break;
            default:
                throw new \RuntimeException('Unknown entry type');
        }
    }
}
