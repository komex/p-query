<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Test\Perk\Sequence;

use Komex\Perk\Condition\EqualCondition;
use Komex\Perk\Sequence\Sequence;

/**
 * Class SequenceTest
 *
 * @package Komex\Test\Perk\Sequence
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class SequenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test simple check of Equals conditions
     */
    public function testCheckEqualsConditions()
    {
        $sequence = new Sequence([new EqualCondition(T_CLASS), new EqualCondition(T_FUNCTION)]);
        $this->assertSame(Sequence::COLLECTED, $sequence->check([T_CLASS, 'class']));
        $this->assertSame(Sequence::MATCH, $sequence->check([T_FUNCTION, 'function']));
        $this->assertSame([[T_CLASS, 'class'], [T_FUNCTION, 'function']], $sequence->getSequence());
    }

    /**
     * Test check hierarchy of sequences
     */
    public function testCheckSequenceHierarchy()
    {
        $sequence = new Sequence(
            [
                new EqualCondition(T_CLASS),
                new Sequence([new EqualCondition(T_FUNCTION), new EqualCondition(T_VARIABLE)])
            ]
        );
        $this->assertSame(Sequence::COLLECTED, $sequence->check([T_CLASS, 'class']));
        $this->assertSame(Sequence::COLLECTED, $sequence->check([T_FUNCTION, 'function']));
        $this->assertSame(Sequence::MATCH, $sequence->check([T_VARIABLE, 'abc']));
        $this->assertSame(
            [[T_CLASS, 'class'], [T_FUNCTION, 'function'], [T_VARIABLE, 'abc']],
            $sequence->getSequence()
        );
    }
}
