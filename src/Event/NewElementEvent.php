<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class NewElementEvent
 *
 * @package Perk\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NewElementEvent extends Event
{
    /**
     * @var int
     */
    private $type;
    /**
     * @var int
     */
    private $position;
    /**
     * @var int
     */
    private $start;
    /**
     * @var int
     */
    private $finish;

    /**
     * @param int $type
     * @param int $position
     * @param int $start
     * @param int $finish
     */
    public function __construct($type, $position, $start, $finish)
    {
        $this->type = $type;
        $this->position = $position;
        $this->start = $start;
        $this->finish = $finish;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }
}
