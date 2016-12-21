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

use Tmilos\GoldParser\Dfa\DfaInterface;
use Tmilos\GoldParser\Dfa\EndState;
use Tmilos\GoldParser\Symbol\SymbolCollection;

class StringTokenizer implements Tokenizer
{
    /** @var DfaInterface */
    private $dfa;

    /** @var DfaInput */
    private $input;

    /**
     * @param DfaInterface $dfa
     */
    public function __construct(DfaInterface $dfa)
    {
        $this->dfa = $dfa;
    }

    public function getInput()
    {
        return $this->input->getText();
    }

    public function setInput($input)
    {
        $this->input = new DfaInput($input);
    }

    public function getCurrentLocation()
    {
        return $this->input->getLocation();
    }

    public function setCurrentLocation(Location $location)
    {
        $this->input->setLocation(Location::copy($location));
    }

    public function retrieveToken()
    {
        $this->dfa->reset();
        $startLocation = Location::copy($this->input->getLocation());
        $acceptInfo = null;

        if ($this->input->getPosition() >= strlen($this->input->getText())) {
            return new TerminalToken(SymbolCollection::eof(), SymbolCollection::eof()->getName(), $startLocation);
        }

        $newState = $this->dfa->gotoNext($this->input->readChar());
        while ($newState) {
            if ($newState instanceof EndState) {
                $acceptInfo = new AcceptInfo($newState, Location::copy($this->input->getLocation()));
            }
            if ($this->input->isEof()) {
                $newState = null;
            } else {
                $newState = $this->dfa->gotoNext($this->input->readChar());
            }
        }

        if (!$acceptInfo) {
            $len = $this->input->getPosition() - $startLocation->getPosition();
            $text = substr($this->input->getText(), $startLocation->getPosition(), $len);

            return new TerminalToken(SymbolCollection::error(), $text, $startLocation);
        } else {
            $this->input->setLocation($acceptInfo->getLocation());
            $len = $acceptInfo->getLocation()->getPosition() - $startLocation->getPosition();
            $text = substr($this->input->getText(), $startLocation->getPosition(), $len);

            return new TerminalToken($acceptInfo->getState()->getAcceptSymbol(), $text, $startLocation);
        }
    }

    public function skipToChar($char)
    {
        return $this->input->skipToChar($char);
    }

    public function skipAfterChar($char)
    {
        return $this->input->skipAfterChar($char);
    }
}
