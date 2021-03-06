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

class Record
{
    /** @var EntriesCollection */
    private $entries;

    public function __construct()
    {
        $this->entries = new EntriesCollection();
    }

    /**
     * @return EntriesCollection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    public function __toString()
    {
        $result = '';
        foreach ($this->entries as $entry) {
            $result .= (string) $entry;
            $result .= "\n";
        }

        return $result;
    }
}
