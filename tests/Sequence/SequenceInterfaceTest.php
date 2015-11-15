<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Test\Perk;

use Komex\Perk\Condition\ConditionInterface;
use Komex\Perk\Sequence\SequenceInterface;

/**
 * Class SequenceInterfaceTest
 *
 * @package Komex\Test\Perk
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class SequenceInterfaceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test statuses has different values
     */
    public function testStatuses()
    {
        $this->assertNotSame(
            SequenceInterface::COLLECTED,
            ConditionInterface::MATCH
        );
        $this->assertNotSame(
            SequenceInterface::COLLECTED,
            ConditionInterface::MISMATCH
        );
    }
}
