<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator\In;

use PQuery\Iterator\NamespaceIterator;

/**
 * Class NamespaceInIterator
 *
 * @package PQuery\Iterator\In
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
        $iterator = $this->getInnerIterator();

        return ($iterator->key() > $leftRange && $iterator->current() < $rightRange);
    }
}
