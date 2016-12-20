<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Tokenizer;

class DfaInput
{
    /** @var string */
    private $text;

    /** @var Location */
    private $location;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
        $this->location = Location::zero();
    }

    /**
     * @return string
     */
    public function readChar()
    {
        $position = $this->getPosition();
        $result = $this->text[$position];
        if ($result == "\n") {
            $this->location->nextLine();
        } else {
            $this->location->nextColumn();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function readCharNoUpdate()
    {
        return $this->text[$this->getPosition()];
    }

    /**
     * @param string $char
     *
     * @return bool
     */
    public function skipToChar($char)
    {
        while (!$this->isEof()) {
            $result = $this->readCharNoUpdate();
            if ($result == $char) {
                return true;
            }
            if ($result == "\n") {
                $this->location->nextLine();
            } else {
                $this->location->nextColumn();
            }
        }

        return false;
    }

    /**
     * @param string $char
     *
     * @return bool
     */
    public function skipAfterChar($char)
    {
        while (!$this->isEof()) {
            $result = $this->readChar();
            if ($result == $char) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEof()
    {
        return $this->getPosition() >= strlen($this->text);
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->location->getPosition();
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}
