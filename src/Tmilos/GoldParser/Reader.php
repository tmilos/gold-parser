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

class Reader
{
    /** @var resource */
    private $stream;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
        stream_set_read_buffer($stream, 4096);
    }

    /**
     * @return bool
     */
    public function eof()
    {
        return feof($this->stream);
    }

    /**
     * @return int
     */
    public function readByte()
    {
        return ord(fread($this->stream, 1));
    }

    /**
     * @return int
     */
    public function readInt16()
    {
        $str = fread($this->stream, 2);
        $result = unpack('v', $str);

        return reset($result);
    }

    /**
     * @return string
     */
    public function readUnicodeChar()
    {
        return mb_convert_encoding(fread($this->stream, 2), 'UTF-8', 'UTF-16LE');
    }

    /**
     * @return string
     */
    public function readUnicodeString()
    {
        $result = '';
        $char = $this->readUnicodeChar();
        while ($char != "\0") {
            $result .= $char;
            $char = $this->readUnicodeChar();
        }

        return $result;
    }
}
