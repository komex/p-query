<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Filter;

/**
 * Class ClassFilter
 *
 * @package PQuery\Filter
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassFilter extends \FilterIterator
{
    /**
     * @var string
     */
    private $mask;

    /**
     * @param NamespaceFilter $filter
     * @param string|null $mask
     */
    public function __construct(NamespaceFilter $filter, $mask)
    {
        $iterator = new \ArrayIterator();
        parent::__construct($iterator);
        $this->mask = $mask;
    }

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept()
    {
        // TODO: Implement accept() method.
    }
}
