<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\In;

use Perk\Iterator\ClassIterator;

/**
 * Class ClassInIterator
 *
 * @package Perk\Iterator\In
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassInIterator extends ClassIterator
{
    /**
     * @inheritdoc
     */
    public function accept()
    {
        list($leftRange, $rightRange) = $this->range;
        list($position, $finish) = $this->getInnerIterator()->current();

        return ($position > $leftRange && $finish < $rightRange);
    }
}
