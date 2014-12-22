<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\In;

use Perk\Iterator\FunctionIterator;

/**
 * Class FunctionInIterator
 *
 * @package Perk\Iterator\In
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionInIterator extends FunctionIterator
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
