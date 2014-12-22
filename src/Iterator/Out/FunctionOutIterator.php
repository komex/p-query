<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\Out;

use Perk\Iterator\FunctionIterator;

/**
 * Class FunctionOutIterator
 *
 * @package Perk\Iterator\Out
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionOutIterator extends FunctionIterator
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
