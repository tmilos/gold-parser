<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Content;

use Tmilos\GoldParser\Structure\Record;

class CharacterSetRecord
{
    /** @var int */
    private $index;

    /** @var string */
    private $characters;

    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() !== 3) {
            throw new \RuntimeException('Invalid number of entries for character set');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 67) {
            throw new \RuntimeException('Invalid character set header');
        }
        $this->index = $record->getEntries()->item(1)->toIntValue();
        $this->characters = $record->getEntries()->item(2)->toStringValue();
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getCharacters()
    {
        return $this->characters;
    }
}
