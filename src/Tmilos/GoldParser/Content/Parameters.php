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

class Parameters
{
    /** @var string */
    private $name;

    /** @var string */
    private $version;

    /** @var string */
    private $author;

    /** @var string */
    private $about;

    /** @var bool */
    private $caseSensitive;

    /** @var int */
    private $startSymbol;

    /**
     * @param Record $record
     */
    public function __construct(Record $record)
    {
        if ($record->getEntries()->count() != 7) {
            throw new \RuntimeException('Invalid number of entries for parameters');
        }
        if ($record->getEntries()->item(0)->toByteValue() !== 80) {
            throw new \RuntimeException('Invalid parameters header');
        }

        $this->name = $record->getEntries()->item(1)->toStringValue();
        $this->version = $record->getEntries()->item(2)->toStringValue();
        $this->author = $record->getEntries()->item(3)->toStringValue();
        $this->about = $record->getEntries()->item(4)->toStringValue();
        $this->caseSensitive = $record->getEntries()->item(5)->toBoolValue();
        $this->startSymbol = $record->getEntries()->item(6)->toIntValue();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @return bool
     */
    public function isCaseSensitive()
    {
        return $this->caseSensitive;
    }

    /**
     * @return int
     */
    public function getStartSymbol()
    {
        return $this->startSymbol;
    }
}
