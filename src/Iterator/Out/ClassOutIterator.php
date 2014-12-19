<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator\Out;

use PQuery\Iterator\ClassIterator;

/**
 * Class ClassOutIterator
 *
 * @package PQuery\Iterator\Out
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
        $iterator = $this->getInnerIterator();

        return ($iterator->key() < $leftRange && $iterator->current() > $rightRange);
    }
}
