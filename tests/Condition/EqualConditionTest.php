<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Test\Perk\Condition;

use Komex\Perk\Condition\EqualCondition;

/**
 * Class EqualConditionTest
 *
 * @package Komex\Test\Perk\Condition
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class EqualConditionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test correct response on match
     */
    public function testMatch()
    {
        $condition = new EqualCondition(T_CLASS);
        $this->assertSame(EqualCondition::MATCH, $condition->check(T_CLASS));
    }

    /**
     * Test correct response on mismatch
     */
    public function testMismatch()
    {
        $condition = new EqualCondition(T_CLASS);
        $this->assertSame(EqualCondition::MISMATCH, $condition->check(T_ARRAY));
    }
}
