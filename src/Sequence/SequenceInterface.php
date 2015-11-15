<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Perk\Sequence;

use Komex\Perk\Condition\ConditionInterface;

/**
 * Interface SequenceInterface
 *
 * @package Komex\Perk\Sequence
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
interface SequenceInterface extends ConditionInterface
{
    /**
     * Token was collected
     */
    const COLLECTED = 3;

    /**
     * Get collected tokens sequence
     *
     * @return array
     */
    public function getSequence();

    /**
     * Rollback $count tokens.
     * If collected less then $count tokens does reset and returns count difference
     *
     * @param int $count
     *
     * @return int
     */
    public function rollback($count);

    /**
     * Reset sequence
     *
     * @return void
     */
    public function reset();
}
