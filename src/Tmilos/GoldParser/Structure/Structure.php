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

class Structure
{
    /** @var string */
    private $header;

    /** @var RecordCollection */
    private $records;

    /**
     * @param string           $header
     * @param RecordCollection $records
     */
    public function __construct($header, RecordCollection $records)
    {
        $this->header = $header;
        $this->records = $records;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return RecordCollection
     */
    public function getRecords()
    {
        return $this->records;
    }
}
