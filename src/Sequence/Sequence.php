<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Perk\Sequence;

use Komex\Perk\Condition\ConditionInterface;

/**
 * Class Sequence
 *
 * @package Komex\Perk\Sequence
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class Sequence implements SequenceInterface
{
    /**
     * @var ConditionInterface[]
     */
    private $conditionSequence = [];
    /**
     * @var int
     */
    private $pointer = 0;
    /**
     * @var array
     */
    private $tokensSequence = [];

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * Sequence constructor.
     *
     * @param ConditionInterface[] $sequence
     */
    public function __construct(array $sequence)
    {
        foreach ($sequence as $condition) {
            $this->addCondition($condition);
        }
    }

    /**
     * @param ConditionInterface $condition
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditionSequence[] = $condition;
    }


    /**
     * Check on the conditions of the token
     *
     * @param string|array $token
     *
     * @return int
     */
    public function check($token)
    {
        if (isset($this->conditionSequence[$this->pointer])) {
            $this->counter++;
            $condition = $this->conditionSequence[$this->pointer];
            $status = $condition->check($token);
            if ($status === self::MATCH) {
                $this->pointer++;
                if ($condition instanceof SequenceInterface) {
                    $this->tokensSequence = array_merge(
                        $this->tokensSequence,
                        $condition->getSequence()
                    );
                } else {
                    $this->tokensSequence[] = $token;
                }
                if (isset($this->conditionSequence[$this->pointer])) {
                    $status = self::COLLECTED;
                }
            } elseif ($status === self::MISMATCH) {
                $this->reset();
            }

            return $status;
        }
        $this->reset();

        return self::MISMATCH;
    }

    /**
     * @return array
     */
    public function getSequence()
    {
        return $this->tokensSequence;
    }

    /**
     * Rollback $count tokens.
     * If collected less then $count tokens does reset and returns count difference
     *
     * @param int $count
     *
     * @return int
     */
    public function rollback($count)
    {
        // TODO: Implement rollback() method.
    }


    /**
     * Reset sequence
     *
     * @return void
     */
    public function reset()
    {
        $this->tokensSequence = [];
        $this->pointer = 0;
        $this->counter = 0;
    }
}
