<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\Out;

use Perk\Iterator\NamespaceIterator;

/**
 * Class NamespaceOutIterator
 *
 * @package Perk\Iterator\Out
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceOutIterator extends NamespaceIterator
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
