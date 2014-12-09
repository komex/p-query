<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Collection;

use PQuery\Filter\Type\FunctionTypeFilter;

/**
 * Class FunctionCollection
 *
 * @package PQuery\Collection
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionCollection extends AbstractCollection
{
    /**
     * @param \ArrayIterator $elements
     */
    public function __construct(\ArrayIterator $elements)
    {
        $this->elements = new FunctionTypeFilter($elements);
    }
}
