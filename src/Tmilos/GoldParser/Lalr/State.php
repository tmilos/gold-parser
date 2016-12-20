<?php

/*
 * This file is part of the tmilos/gold-parser package.
 *
 * (c) Milos Tomic <tmilos@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tmilos\GoldParser\Lalr;

class State
{
    /** @var int */
    private $id;

    /** @var ActionCollection */
    private $actions;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->actions = new ActionCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ActionCollection
     */
    public function getActions()
    {
        return $this->actions;
    }
}
