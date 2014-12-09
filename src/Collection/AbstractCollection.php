<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Collection;

use PQuery\Filter\NameFilter;

/**
 * Class AbstractCollection
 *
 * @package PQuery\Collection
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    protected $elements;

    /**
     * @param string $name
     * @param bool $caseLess
     *
     * @return $this
     */
    public function name($name, $caseLess = true)
    {
        if (empty($name) === false && is_string($name) === true) {
            $this->elements = new NameFilter($this->elements, $name, $caseLess);
        }

        return $this;
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable
     */
    public function getIterator()
    {
        return $this->elements;
    }
}
