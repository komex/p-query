<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\Out;

use Perk\Iterator\ClassIterator;

/**
 * Class ClassOutIterator
 *
 * @package Perk\Iterator\Out
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassOutIterator extends ClassIterator
{
    /**
     * @inheritdoc
     */
    public function accept()
    {
        list($leftRange, $rightRange) = $this->range;
        list($position, $finish) = $this->getInnerIterator()->current();

        return ($position < $leftRange && $finish > $rightRange);
    }
}
