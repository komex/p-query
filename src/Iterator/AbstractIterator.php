<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Parser\Stream;

/**
 * Class AbstractIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractIterator extends \FilterIterator
{
    /**
     * @var Stream
     */
    protected $stream;
    /**
     * @var \ArrayIterator[]
     */
    protected $elements;
    /**
     * @var array
     */
    protected $range;

    /**
     * @param Stream $stream
     * @param \ArrayIterator $elements
     * @param array $range
     */
    public function __construct(Stream $stream, \ArrayIterator $elements, array $range = [])
    {
        $this->stream = $stream;
        $this->elements = $elements;
        $this->range = $range;
        parent::__construct($this->getElement());
    }

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept()
    {
        if (empty($this->range)) {
            return true;
        } else {
            list($position, $finish) = $this->range;
            $iterator = $this->getInnerIterator();

            return ($iterator->key() > $position && $iterator->current() < $finish);
        }
    }

    /**
     * @return \ArrayIterator
     */
    abstract protected function getElement();
}
