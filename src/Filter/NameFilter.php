<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Filter;

use PQuery\Element\AbstractElement;

/**
 * Class NameFilter
 *
 * @package PQuery\Filter
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NameFilter extends \FilterIterator
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $caseLess;

    /**
     * @param \Iterator $iterator
     * @param string $name
     * @param bool $caseLess
     */
    public function __construct(\Iterator $iterator, $name, $caseLess)
    {
        parent::__construct($iterator);
        $this->name = (string)$name;
        $this->caseLess = (bool)$caseLess;
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
        if ($element instanceof AbstractElement) {
            $pattern = '/' . $this->name . '/';
            if ($this->caseLess === true) {
                $pattern .= 'i';
            }

            return (preg_match($pattern, $element->getName()) === 1);
        } else {
            return false;
        }
    }
}
