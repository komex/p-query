<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator\In;

use PQuery\Iterator\ClassIterator;

/**
 * Class ClassInIterator
 *
 * @package PQuery\Iterator\In
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
        $iterator = $this->getInnerIterator();

        return ($iterator->key() > $leftRange && $iterator->current() < $rightRange);
    }
}
