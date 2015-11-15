<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Perk\Condition;

/**
 * Interface ConditionInterface
 *
 * @package Komex\Perk\Condition
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
interface ConditionInterface
{
    /**
     * Token matched
     */
    const MATCH = 1;
    /**
     * Token mismatched
     */
    const MISMATCH = 2;

    /**
     * Apply token to condition
     *
     * @param int|string $token
     *
     * @return int
     */
    public function apply($token);
}
