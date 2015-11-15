<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Test\Perk;

use Komex\Perk\Condition\ConditionInterface;

/**
 * Class ConditionInterfaceTest
 *
 * @package Komex\Test\Perk
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class ConditionInterfaceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test statuses has different values
     */
    public function testStatuses()
    {
        $this->assertNotSame(
            ConditionInterface::MATCH,
            ConditionInterface::MISMATCH,
            'Statuses must has different values'
        );
    }
}
