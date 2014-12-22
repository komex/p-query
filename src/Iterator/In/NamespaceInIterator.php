<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator\In;

use Perk\Iterator\NamespaceIterator;

/**
 * Class NamespaceInIterator
 *
 * @package Perk\Iterator\In
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceInIterator extends NamespaceIterator
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
