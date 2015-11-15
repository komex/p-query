<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Perk\Condition;

/**
 * Class EqualCondition
 *
 * @package Komex\Perk\Condition
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class EqualCondition implements ConditionInterface
{
    /**
     * @var int|string
     */
    private $token;

    /**
     * EqualCondition constructor.
     *
     * @param int|string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Apply token to condition
     *
     * @param int|string $token
     *
     * @return int
     */
    public function apply($token)
    {
        return $token === $this->token ? self::MATCH : self::MISMATCH;
    }
}
