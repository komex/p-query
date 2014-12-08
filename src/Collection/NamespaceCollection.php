<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Collection;

use PQuery\Element\NamespaceElement;
use PQuery\Filter\NamespaceFilter;

/**
 * Class NamespaceCollection
 *
 * @package PQuery\Collection
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceCollection implements \Iterator, \Countable
{
    /**
     * @var NamespaceFilter
     */
    private $filter;

    /**
     * @param NamespaceFilter $filter
     */
    public function __construct(NamespaceFilter $filter)
    {
        $this->filter = $filter;
    }


    /**
     * @return NamespaceElement
     */
    public function current()
    {
        return $this->filter->current();
    }


    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->filter->next();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->filter->key();
    }


    /**
     * @return bool
     */
    public function valid()
    {
        return $this->filter->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->filter->rewind();
    }

    /**
     * @return int
     */
    public function count()
    {
        $count = 0;
        $this->filter->rewind();
        while ($this->filter->valid()) {
            $count++;
            $this->filter->next();
        }

        return $count;
    }
}
