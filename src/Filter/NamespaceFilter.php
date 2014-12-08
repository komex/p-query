<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Filter;

use PQuery\Element\NamespaceElement;

/**
 * Class NamespaceFilter
 *
 * @package PQuery\Filter
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceFilter extends \FilterIterator
{
    /**
     * @var string
     */
    private $mask;

    /**
     * @param \Iterator $iterator
     * @param string|null $mask
     */
    public function __construct(\Iterator $iterator, $mask)
    {
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
        $element = $this->getInnerIterator()->current();
        if ($element instanceof NamespaceElement) {
            if ($this->mask === null) {
                return true;
            } else {
                return $this->testName($element);
            }
        } else {
            return false;
        }
    }

    /**
     * @param NamespaceElement $namespace
     *
     * @return bool
     */
    private function testName(NamespaceElement $namespace)
    {
        return preg_match('/' . $this->mask . '/', $namespace->getName()) === 1;
    }
}
