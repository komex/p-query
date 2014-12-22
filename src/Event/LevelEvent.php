<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class LevelEvent
 *
 * @package Perk\Event
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class LevelEvent extends Event
{
    /**
     * @var int
     */
    private $level = 0;
    /**
     * @var int
     */
    private $position = 0;

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Increase level
     *
     * @return $this
     */
    public function up()
    {
        $this->level++;

        return $this;
    }

    /**
     * Decrease level
     *
     * @return $this
     */
    public function down()
    {
        $this->level--;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
